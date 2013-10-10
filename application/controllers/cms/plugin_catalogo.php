<?php
/**
 * Plugin para administrar los contenidos editables del sitio
 * @author 	Guido A. Orellana
 * @name	Plugin_contenidos
 * @since	abril 2013
 * 
 */
class Plugin_catalogo extends PL_Controller {
	
	function __construct(){
		parent::__construct();
		
		//Load the plugin data
		$this->plugin_action_table			= 'PLUGIN_PRODUCTS';
		$this->plugin_button_create			= "Agregar producto";
		$this->plugin_button_cancel			= "Cancelar";
		$this->plugin_button_update			= "Guardar Cambios";
		$this->plugin_button_delete			= "Eliminar";
		$this->plugin_page_title			= "Catalogo";
		$this->plugin_page_create			= "Agragar producto";
		$this->plugin_page_read				= "Mostrar Catalogo";
		$this->plugin_page_update			= "Editar Producto";
		$this->plugin_page_delete			= "Eliminar";
		
		
		$this->plugin_image_route			= "/user_files/uploads/images/";
		$this->plugin_display_array[0]		= "ID";
		$this->plugin_display_array[1]		= "Codigo Producto";
		$this->plugin_display_array[2]		= "Producto";
		$this->plugin_display_array[3]		= "Stock";
		$this->plugin_display_array[4]		= "Descuento por volumen en unidades";
		$this->plugin_display_array[5]		= "Categoria";
		$this->plugin_display_array[6]		= "Imagen";
		$this->plugin_display_array[7]		= "Imagen secundaria";
		$this->plugin_display_array[8]		= "P&aacute;gina";
		$this->plugin_display_array[9]		= "Guatemala";
		$this->plugin_display_array[10]		= "Honduras";
		$this->plugin_display_array[11]		= "El Salvador";
		$this->plugin_display_array[12]		= "Costa Rica";
		$this->plugin_display_array[13]		= "Nicaragua";
		$this->plugin_display_array[14]		= "Panama";
		
		$this->plugins_model->initialise($this->plugin_action_table);
		
		//Extras to send
		$this->display_pagination			= TRUE; //Mostrar paginación en listado
		$this->pagination_per_page			= 10; //Numero de registros por página
		$this->pagination_total_rows		= $this->plugins_model->total_rows(); //Número total de items a desplegar
		
		$this->display_filter				= FALSE; //Mostrar filtro de búsqueda 'SEARCH' o según listado 'LIST' o no mostrar FALSE
		$this->product_stock_array 			= array('AVAILABLE' => 'Disponible', 'NO_AVAILABLE'=>'No disponible');
		
		$this->label_stock_array			= array('AVAILABLE' => 'label-success', 'NO_AVAILABLE' => 'label-important');
		$this->filter_options= $this->product_stock_array;
		$this->output->enable_profiler(FALSE);
	}
	
	/**
	 * Funciï¿½n para desplegar listado completo de datos guardados, enviar los tï¿½tulos en array con clave header y el cuerpo en un array dentro de otro con clave body
	 * 
	 * @param	$result_array 		array 		Array con la listado devuelto por query de la DB
	 * @return	$data_array 		array 		Arreglo con la informaciï¿½n del header y body
	 */
	public function _html_plugin_display($result_array){
		
		//Header data
		$data_array['header'][3]			= $this->plugin_display_array[3];
		$data_array['header'][1]			= $this->plugin_display_array[1];
		$data_array['header'][2]			= $this->plugin_display_array[2];		
		

				
		//Body data
		$data_array['body'] = '';
		foreach($result_array as $field):
		$data_array['body']					.= '<tr>';
		$data_array['body']					.= '<td><span class="label '.$this->label_stock_array[$field->PRODUCT_STOCK].'">'.$this->product_stock_array[$field->PRODUCT_STOCK].'</span></td>';	
		$data_array['body']					.= '<td>'.$field->PRODUCT_CODE.'</td>';
		$data_array['body']					.= '<td><a href="'.base_url('cms/'.strtolower($this->current_plugin).'/update_table_row/'.$field->ID).'">'.($field->PRODUCT_NAME).'</a></td>';
		$data_array['body']					.= '</tr>';
		endforeach;
		
		//
		return $data_array;
	}
	
	/*
	 * Funciï¿½n para crear nuevo contenido, desde aquï¿½ se especifican los campos a enviar en el formulario.
	 * El formulario se envï¿½a mediante objectos preestablecidos de codeigniter. 
	 * El formulario se envï¿½a con un array con la clave form_html.
	 * Para enviar un formulario extra se agrega en el array la clave extra_form.
	 * Se puede encontrar una guï¿½a en: http://ellislab.com/codeigniter/user-guide/helpers/form_helper.html
	 */
	public function _html_plugin_create(){
        
		//Formulario
		$data_array['form_html']			= "<div class='control-group'>".form_label($this->plugin_display_array[5],'',array('class' => 'control-label'))."<div class='controls'>".form_dropdown('PRODUCT_CATEGORY', $this->opciones_categoria())."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[3],'',array('class' => 'control-label'))."<div class='controls'>".form_dropdown('PRODUCT_STOCK', $this->product_stock_array)."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[1],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'PRODUCT_CODE', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[2],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'PRODUCT_NAME', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[4],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'PRODUCT_DISCOUNTVOL', 'class' => 'span3'))."</div></div>";	
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[9],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>Q</span>".form_input(array('name' => 'PRODUCT_PRICE_G', 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[10],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>L</span>".form_input(array('name' => 'PRODUCT_PRICE_H', 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[11],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>$</span>".form_input(array('name' => 'PRODUCT_PRICE_ES', 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[12],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>&#162</span>".form_input(array('name' => 'PRODUCT_PRICE_CR', 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[13],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>C$</span>".form_input(array('name' => 'PRODUCT_PRICE_N', 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[14],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>&szlig</span>".form_input(array('name' => 'PRODEUCT_PRICE_P', 'class' => 'span2'))."</div></div></div>";		
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[6],'',array('class' => 'control-label'))."<div class='controls'>".form_upload(array('name' => 'PRODUCT_PRIMARY_IMAGE', 'class' => 'span6'))."<span class='help-block'>300x400 px</span></div></div>";		
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[7],'',array('class' => 'control-label'))."<div class='controls'>".form_uploader(site_url('cms/plugin_uploader'))."</div></div>";		

		return $data_array;
    }
	public function _html_plugin_update($result_data){
		
		//Formulario
		$data_array['form_html']			= "<div class='control-group'>".form_label($this->plugin_display_array[5],'',array('class' => 'control-label'))."<div class='controls'>".form_dropdown('PRODUCT_CATEGORY',$this->opciones_categoria(),$result_data->PRODUCT_CATEGORY)."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[3],'',array('class' => 'control-label'))."<div class='controls'>".form_dropdown('PRODUCT_STOCK',$this->product_stock_array,$result_data->PRODUCT_STOCK)."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[1],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'PRODUCT_CODE', 'value' => $result_data->PRODUCT_CODE, 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[2],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'PRODUCT_NAME', 'value' => $result_data->PRODUCT_NAME, 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[4],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'PRODUCT_DISCOUNTVOL', 'value' => $result_data->PRODUCT_DISCOUNTVOL, 'class' => 'span3'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[9],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>Q</span>".form_input(array('name' => 'PRODUCT_PRICE_G','value'=> $result_data->PRODUCT_PRICE_G, 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[10],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>L</span>".form_input(array('name' => 'PRODUCT_PRICE_H','value'=> $result_data->PRODUCT_PRICE_H, 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[11],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>$</span>".form_input(array('name' => 'PRODUCT_PRICE_ES','value'=> $result_data->PRODUCT_PRICE_ES, 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[12],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>&#162</span>".form_input(array('name' => 'PRODUCT_PRICE_CR','value'=> $result_data->PRODUCT_PRICE_CR, 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[13],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>C$</span>".form_input(array('name' => 'PRODUCT_PRICE_N','value'=> $result_data->PRODUCT_PRICE_N, 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[14],'',array('class' => 'control-label'))."<div class='controls'><div class='input-prepend'><span class='add-on'>&szlig</span>".form_input(array('name' => 'PRODEUCT_PRICE_P','value'=> $result_data->PRODEUCT_PRICE_P, 'class' => 'span2'))."</div></div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[6],'',array('class' => 'control-label'))."<div class='controls'>".form_upload(array('name' => 'PRODUCT_PRIMARY_IMAGE', 'value' => $result_data->PRODUCT_PRIMARY_IMAGE,'class' => 'span6'))."<span class='help-block'>300x400 px</span></div></div>";		
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[7],'',array('class' => 'control-label'))."<div class='controls'>".form_uploader(site_url('cms/plugin_uploader'),json_decode($result_data->PRODUCT_SECONDARY_IMAGE))."</div></div>";		
		
		
		return $data_array;
	}
	
	/**
	 * Funciones para editar Querys o Datos a enviar desde cada plugin
	 */
	//Funciï¿½n para desplegar listado, desde aquï¿½ se puede modificar el query
	public function _plugin_display($filter){
		$offset = (isset($filter[1]))?$filter[1]:0;
		$result_array = $this->plugins_model->list_rows('', '', $this->pagination_per_page, $offset);
		
		
		return $this->_html_plugin_display($result_array);
	}
	//Funciones de los posts a enviar
	public function post_new_val(){
		$submit_posts 				= $this->input->post();
		//Si se carga una imagen
			$submit_posts['PRODUCT_SECONDARY_IMAGE']= json_encode($this->input->post('galleryImgs'));
			UNSET($submit_posts['galleryImgs']);
			if(!empty($_FILES["PRODUCT_PRIMARY_IMAGE"]["name"])):
				$upload_config['upload_path'] 		= '.'.$this->plugin_image_route;
				$upload_config['allowed_types'] 	= 'gif|jpg|png';
				$upload_config['max_width']  		= '300';
				$upload_config['max_height'] 		= '400';
				$this->upload->initialize($upload_config);
				
				if (!$this->upload->do_upload('PRODUCT_PRIMARY_IMAGE')):
					$this->fw_alerts->add_new_alert(4001, 'SUCESS');
					echo $this->upload->display_errors();
				else:
					$uploaded_data = $this->upload->data();
					$submit_posts['PRODUCT_PRIMARY_IMAGE'] = $this->plugin_image_route.$uploaded_data['file_name'];
					$this->fw_alerts->add_new_alert(4001, 'SUCCESS');
				endif;
			endif;
		$submit_posts				= array_map("entities_to_ascii", $submit_posts);
		return $this->_set_new_val($submit_posts);
	}
	public function post_update_val($data_id){
		$submit_posts 				= $this->input->post();
		
		//Si se carga una imagen
			$submit_posts['PRODUCT_SECONDARY_IMAGE']= json_encode($this->input->post('galleryImgs'));
			UNSET($submit_posts['galleryImgs']);
			if(!empty($_FILES["PRODUCT_PRIMARY_IMAGE"]["name"])):
				$upload_config['upload_path'] 		= '.'.$this->plugin_image_route;
				$upload_config['allowed_types'] 	= 'gif|jpg|png';
				$upload_config['max_width']  		= '300';
				$upload_config['max_height'] 		= '400';
				$this->upload->initialize($upload_config);
				
				
				if (!$this->upload->do_upload('PRODUCT_PRIMARY_IMAGE')):
					$this->fw_alerts->add_new_alert(4002, 'ERROR');
				else:
					$uploaded_data = $this->upload->data();
					$submit_posts['PRODUCT_PRIMARY_IMAGE'] = $this->plugin_image_route.$uploaded_data['file_name'];
					$this->fw_alerts->add_new_alert(4001, 'SUCCESS');
				endif;
			endif;
		$submit_posts				= array_map("entities_to_ascii", $submit_posts);
		
		return $this->_set_update_val($submit_posts);
	}
	
	/**
	 * Funciones especï¿½ficas del plugin
	 */
	 //Funcion que despliega las filas de una tabla (FROM)
	 private function opciones_categoria(){
	 	return $this->plugins_model->desplegar_cat();
	 }
}
