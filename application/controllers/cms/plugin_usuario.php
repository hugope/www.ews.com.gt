<?php
/**
 * Plugin para administrar el catalogo del sitio
 * @author 	Guido A. Orellana
 * @name	Plugin_contenidos
 * @since	abril 2013
 * 
 */
class Plugin_usuario extends PL_Controller {
	
	function __construct(){
		parent::__construct();
		
		//Load the plugin data
		$this->plugin_action_table			= 'PLUGIN_USERS';
		$this->plugin_button_create			= "Agregar Usuario";
		$this->plugin_button_cancel			= "Cancelar";
		$this->plugin_button_update			= "Guardar Cambios";
		$this->plugin_button_delete			= "Eliminar";
		$this->plugin_page_title			= "Usuario";
		$this->plugin_page_create			= "Agragar usuario";
		$this->plugin_page_read				= "Mostrar usuario";
		$this->plugin_page_update			= "Editar usuario";
		$this->plugin_page_delete			= "Eliminar";
		
		
		$this->plugin_display_array[0]		= "ID";
		$this->plugin_display_array[1]		= "Nombre de usuario";
		$this->plugin_display_array[2]		= "Estatus";
		$this->plugin_display_array[3]		= "Correo";
		$this->plugin_display_array[4]		= "Password";
		$this->plugin_display_array[5]		= "Confirmacion de pasword";
		$this->plugin_display_array[6]		= "Telefono";
		$this->plugin_display_array[7]		= "Direccion";		
		$this->plugin_display_array[8]		= "Ciudad / Pais";
		$this->plugin_display_array[9]		= "Empresa";
		$this->plugin_display_array[10]		= "Nit";
		
		$this->plugin_display_array[11]		= "P&aacute;gina";
		
		$this->plugins_model->initialise($this->plugin_action_table);
		
		//Extras to send
		$this->display_pagination			= TRUE; //Mostrar paginación en listado
		$this->pagination_per_page			= 10; //Numero de registros por página
		
		$this->display_filter				= 'LIST'; //Mostrar filtro de búsqueda 'SEARCH' o según listado 'LIST' o no mostrar FALSE
		
		$this->output->enable_profiler(TRUE);
		
		$this->users_status_array 			= array('ENABLED' => 'Habilitado', 'DISABLED' => 'Inhabilitado', 'STANDBY' => 'En Espera');
		
		$this->label_status_array			= array('ENABLED' => 'label-success', 'DISABLED' => 'label-important', 'STANDBY' => '');
		
		//funcion que envia opciones de listado de filtro 
		$this->filter_options= $this->users_status_array;
}
	
	/**
	 * Funciï¿½n para desplegar listado completo de datos guardados, enviar los tï¿½tulos en array con clave header y el cuerpo en un array dentro de otro con clave body
	 * 
	 * @param	$result_array 		array 		Array con la listado devuelto por query de la DB
	 * @return	$data_array 		array 		Arreglo con la informaciï¿½n del header y body
	 */
	public function _html_plugin_display($result_array){
		
		$data_array['filteroptions'] = $this->filter_options;
		$data_array['currentFilter'] = $result_array['filter'];
		
		
		//Header data
		$data_array['header'][2]			= $this->plugin_display_array[2];
		$data_array['header'][1]			= $this->plugin_display_array[1];
		$data_array['header'][3]			= $this->plugin_display_array[3];
		$data_array['header'][6]			= $this->plugin_display_array[6];
		$data_array['header'][7]			= $this->plugin_display_array[7];
		$data_array['header'][8]			= $this->plugin_display_array[8];
		$data_array['header'][9]			= $this->plugin_display_array[9];
		$data_array['header'][10]			= $this->plugin_display_array[10];
		
		
		//$data_array['header'][4]			= $this->plugin_display_array[4];
		
				
		//Body data
		$data_array['body'] = '';
		foreach($result_array['body'] as $field):
		$data_array['body']					.= '<tr>';
		    
		$data_array['body']					.= '<td><span class="label '.$this->label_status_array[$field->USER_STATUS].'">'.$this->users_status_array[$field->USER_STATUS].'</span></td>';
		$data_array['body']					.= '<td><a href="'.base_url('cms/'.strtolower($this->current_plugin).'/update_table_row/'.$field->ID).'">'.($field->USER_NAME).'</a></td>';		
		$data_array['body']					.= '<td>'.$field->  USER_EMAIL.'</td>';
		$data_array['body']					.= '<td>'.$field-> 	TELEPHONE.'</td>';
		$data_array['body']					.= '<td>'.$field-> 	ADDRESS.'</td>';
		$data_array['body']					.= '<td>'.$field-> 	COUNTRY_CITY.'</td>';
		$data_array['body']					.= '<td>'.$field-> 	NOW.'</td>';
		$data_array['body']					.= '<td>'.$field-> 	NIT.'</td>';
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
		$shirts_on_sale = array('small', 'large');
        
		//Formulario
		
		$data_array['form_html']			= "<div class='control-group'>".form_label($this->plugin_display_array[1],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'USER_NAME', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[3],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'USER_EMAIL', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[6],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'TELEPHONE', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[7],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'ADDRESS', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[8],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'COUNTRY_CITY', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[9],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'NOW', 'class' => 'span6'))."</div></div>";		
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[10],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'NIT', 'class' => 'span6'))."</div></div>";		
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[4],'',array('class' => 'control-label'))."<div class='controls'>".form_password(array('name' => 'USER_PASSWORD', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[5],'',array('class' => 'control-label'))."<div class='controls'>".form_password(array('name' => 'USER_CONFIRMATION_CODE', 'class' => 'span6'))."</div></div>";		
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[2],'',array('class' => 'control-label'))."<div class='controls'>".form_dropdown('USER_STATUS',$this->users_status_array)."</div></div>";
		
		
		return $data_array;
    }
	public function _html_plugin_update($result_data){
		
		//Formulario
		$data_array['form_html']			= "<div class='control-group'>".form_label($this->plugin_display_array[1],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'USER_NAME', 'value' => $result_data->USER_NAME, 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[3],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'USER_EMAIL', 'value' => $result_data->USER_EMAIL, 'class' => 'span6'))."</div></div>";  
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[6],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'TELEPHONE', 'value' => $result_data->TELEPHONE, 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[7],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'ADDRESS', 'value' => $result_data->ADDRESS, 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[8],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'COUNTRY_CITY', 'value' => $result_data->COUNTRY_CITY, 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[9],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'NOW', 'value' => $result_data->NOW, 'class' => 'span6'))."</div></div>";	
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[10],'',array('class' => 'control-label'))."<div class='controls'>".form_input(array('name' => 'NIT', 'value' => $result_data->NIT, 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[4],'',array('class' => 'control-label'))."<div class='controls'>".form_password(array('name' => 'USER_PASSWORD', 'class' => 'span6'))."</div></div>";
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[5],'',array('class' => 'control-label'))."<div class='controls'>".form_password(array('name' => 'USER_CONFIRMATION_CODE', 'class' => 'span6'))."</div></div>";		
		$data_array['form_html']			.= "<div class='control-group'>".form_label($this->plugin_display_array[2],'',array('class' => 'control-label'))."<div class='controls'>".form_dropdown('USER_STATUS', $this->users_status_array, $result_data->USER_STATUS)."</div></div>";
		
		return $data_array;
	}
	
	/**
	 * Funciones para editar Querys o Datos a enviar desde cada plugin
	 */
	//Funciï¿½n para desplegar listado, desde aquï¿½ se puede modificar el query
	public function _plugin_display($filter){
		
		$offset 	= (isset($filter[1]))?$filter[1]:0;
		$listfilter	=(isset($filter[0]))?$filter[0]:'STANDBY';
		$this->pagination_total_rows = $this->plugins_model->total_rows("USER_STATUS = '".$listfilter."'"); //Número total de items a desplegar
		
		$result_array['body'] 	= $this->plugins_model->list_rows('', "USER_STATUS = '".$listfilter."'", $this->pagination_per_page, $offset);
		$result_array['filter'] = $listfilter;
		
		return $this->_html_plugin_display($result_array);
	}
	//Funciones de los posts a enviar
	public function post_new_val(){
		$submit_posts 				= $this->input->post();
		$submit_posts				= array_map("entities_to_ascii", $submit_posts);
		$submit_posts['USER_PASSWORD']=md5($submit_posts['USER_PASSWORD']);
		$submit_posts['USER_CONFIRMATION_CODE']=md5($submit_posts['USER_CONFIRMATION_CODE']);
		
		if ($submit_posts['USER_PASSWORD']!=$submit_posts['USER_CONFIRMATION_CODE']){
			$this->fw_alerts->add_new_alert(4014,"ERROR");
			redirect('cms/'.strtolower($this->current_plugin));//viene de la carpeta delpers para redireccionar
		}
		else {
		return $this->_set_new_val($submit_posts);	
		}
		
	}
	public function post_update_val(){
		$submit_posts 				= $this->input->post();
		$submit_posts				= array_map("entities_to_ascii", $submit_posts);
		$submit_posts['USER_PASSWORD']=md5($submit_posts['USER_PASSWORD']);
		$submit_posts['USER_CONFIRMATION_CODE']=md5($submit_posts['USER_CONFIRMATION_CODE']);
		
		
		if ($submit_posts['USER_PASSWORD']!=$submit_posts['USER_CONFIRMATION_CODE']){
			$this->fw_alerts->add_new_alert(4014,"ERROR");
			redirect('cms/'.strtolower($this->current_plugin));//viene de la carpeta delpers para redireccionar
		
			}else {
			return $this->_set_update_val($submit_posts);	
		}
		
	}
	
	
	 
}
