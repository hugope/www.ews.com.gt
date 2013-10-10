<?php
/**
 * Plugin para administrar los contenidos editables del sitio
 * @author 	Guido A. Orellana
 * @name	Plugin_contenidos
 * @since	abril 2013
 *
 */
class Plugin_cotizacion extends PL_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('utilities');

		//Load the plugin data
		$this -> plugin_action_table = 	'PLUGIN_QUOTE';
		$this -> plugin_button_create = "Agregar Cotizaci&oacuten";
		$this -> plugin_button_cancel = "Cancelar";
		$this -> plugin_button_update = "Guardar Cambios";
		$this -> plugin_button_delete = "Eliminar";
		$this -> plugin_page_title = 	"Cotizaci&oacuten";
		$this -> plugin_page_create = 	"Agragar Productos";
		$this -> plugin_page_read = 	"Mostrar Productos";
		$this -> plugin_page_update = 	"Vista de Cotizaciones";
		$this -> plugin_page_delete = 	"Eliminar";

		//Vistas de plugin
		$this -> template_display = "plugin_cotizaciones";
		$this -> template_update = "plugin_cotizaciones_update";
		

		$this -> plugin_display_array[0] = "ID";
		$this -> plugin_display_array[1] = "Codigo de Cotizaci&oacuten";
		$this -> plugin_display_array[2] = "Usuario";
		$this -> plugin_display_array[3] = "Fecha de Cotizaci&oacuten";
		$this -> plugin_display_array[4] = "Productos a Cotizar";
		$this -> plugin_display_array[5] = "Cantidad";
		$this -> plugin_display_array[6] = "Pa&iacute;s";
		
		$this -> plugin_display_array[7] = "Codigo";
		$this -> plugin_display_array[8] = "Producto";
		$this -> plugin_display_array[9] = "Cantidad";
		$this -> plugin_display_array[10] = "Precio";
		$this -> plugin_display_array[12] = "Stock";

		$this -> plugin_display_array[11] = "P&aacute;gina";

		$this -> plugins_model -> initialise($this -> plugin_action_table);

		//Extras to send
		$this->display_pagination			= TRUE; //Mostrar paginación en listado
		$this->pagination_per_page			= 10; //Numero de registros por página
		$this->pagination_total_rows		= $this->plugins_model->total_rows(); //Extras to send
		$this->output->enable_profiler(FALSE);
		
		$this -> display_filter = 'LIST';
		
		$this->paises_array 			= array(
		'GUATEMALA' => 'Guatemala',
		'HONDURAS' => 'Honduras',
		'EL_SALVADOR'=> 'El Salvador',
		'COSTA_RICA'=> 'Costa Rica',
		'NICARAGUA'=>'Nicaragua',
		'PANAMA'=> 'Panama');
		
		$this->meses_array 				= array(
		'01'=>'Enero' , 
		'02'=>'Febrero'  , 
		'03'=>'Marzo' , 
		'04'=>'Abril',
		'05'=>'Mayo',
		'06'=>'Junio',
		'07'=>'Julio',
		'08'=>'Agosto',
		'09'=>'Septiembre',
		'10'=>'Octubre',
		'11'=>'Noviembre',
		'12'=>'Diciembre');		
			
	}
	private function rellena_anio(){
			
		
		$anio_array = array();
  		foreach (range(2000, date('Y')) as $numero) {
  			$anio_array[$numero]=$numero;
  			
 			} 
 			return $anio_array;
		}
	
	
		
	/**
	 * Funciï¿½n para desplegar listado completo de datos guardados, enviar los tï¿½tulos en array con clave header y el cuerpo en un array dentro de otro con clave body
	 *
	 * @param	$result_array 		array 		Array con la listado devuelto por query de la DB
	 * @return	$data_array 		array 		Arreglo con la informaciï¿½n del header y body
	 */
	public function _html_plugin_display($result_array) {
		
		$data_array['paisesoptions'] = $this->paises_array;
		$data_array['currentPaises'] = $result_array['filter']['paises'];
		
		$data_array['mesoptions'] = $this->meses_array;
		$data_array['currentMes'] = $result_array['filter']['meses'];
		
		$data_array['aniooptions'] = $this->rellena_anio();
		$data_array['currentAnio'] = $result_array['filter']['anios'];
				
		//Header data
		$data_array['header'][6]			= $this->plugin_display_array[6];		
		$data_array['header'][2]			= $this->plugin_display_array[2];
		$data_array['header'][1]			= $this->plugin_display_array[1];
		$data_array['header'][3]			= $this->plugin_display_array[3];
		
		$usuarios=$this->plugins_model->id_usuario ();
		
		//Body data
		$data_array['body'] = '';
		foreach($result_array['body'] as $field):
		$data_array['body']					.= '<tr>';

		$data_array['body']					.= '<td>'.$this->paises_array[$field->QUOTE_PAIS].'</td>';
		$data_array['body']					.= '<td>'.$usuarios[$field->QUOTE_USER].'</td>';    
		$data_array['body']					.= '<td><a href="'.base_url('cms/'.strtolower($this->current_plugin).'/update_table_row/'.$field->ID).'">'.codigo_cotizacion($field->QUOTE_DATE, $field->ID).'</a></td>';		
		$data_array['body']					.= '<td>'.mysql_date_to_dmy($field->QUOTE_DATE ).'</td>';	
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

	public function _html_plugin_update($result_data) {

		//Formulario
		$usuarios=$this->plugins_model->id_usuario ();
		$productos = $this->plugins_model->tabla_carrito_id($result_data->ID);
		$this->plugins_model->carrito_cantidad($result_data->ID);
		
		
		$data_array['form_html'] ="<div class='control-group'>"."<dl class='dl-horizontal'><dt>".($this -> plugin_display_array[2])."<dd>".$usuarios[$result_data -> QUOTE_USER]."</dt></dd></dl>"."</div>";
		$data_array['form_html'] .="<div class='control-group'>"."<dl class='dl-horizontal'><dt>".($this -> plugin_display_array[1])."<dd>".codigo_cotizacion($result_data ->QUOTE_DATE, $result_data ->ID)."</dt></dd></dl>"."</div>";
		$data_array['form_html'] .="<div class='control-group'>"."<dl class='dl-horizontal'><dt>".($this -> plugin_display_array[3])."<dd>".mysql_date_to_dmy($result_data -> QUOTE_DATE)."</dt></dd></dl>"."</div>";
		$data_array['form_html'] .="<div class='control-group'>"."<dl class='dl-horizontal'><dt>".($this -> plugin_display_array[6])."<dd>".$result_data -> QUOTE_PAIS."</dt></dd></dl>"."</div>";
		
		
		$data_array['form_html'] .="<table class='table table-striped'>"."<thead><tr><th>".($this -> plugin_display_array[7])."</th><th>".($this -> plugin_display_array[8])."</th><th>".($this -> plugin_display_array[12])."</th><th>".($this -> plugin_display_array[10])."</th></tr></thead>";
		
		$countryprice = array(
							'GUATEMALA' => array('column' => 'PRODUCT_PRICE_G', 'currency' => 'Q.'),
							'HONDURAS' => array('column' => 'PRODUCT_PRICE_H', 'currency' => 'L.'),
							'EL_SALVADOR' => array('column' => 'PRODUCT_PRICE_ES', 'currency' => '$.'),
							'COSTA_RICA' => array('column' => 'PRODUCT_PRICE_CR', 'currency' => '&#162.'),
							'NICARAGUA' => array('column' => 'PRODUCT_PRICE_N', 'currency' => 'C$.'),
							'PANAMA' => array('column' => 'PRODEUCT_PRICE_P', 'currency' => '&szlig.')
							);
		$this->label_stock_array			= array('AVAILABLE' => 'label-success', 'NO_AVAILABLE' => 'label-important');
		$stock_array=  array('AVAILABLE'=>'Disponible', 'NO_AVAILABLE'=>'No Disponible');
		
		$data_array['form_html'] .="<tbody>";
		foreach($productos  as $producto):
		$data_array['form_html']					.= '<tr>';
		$data_array['form_html']					.= '<td>'.$producto->PRODUCT_CODE.'</td>';
		$data_array['form_html']					.= '<td>'.$producto->PRODUCT_NAME.'</td>';
		$data_array['form_html']					.= '<td><span class="label '.$this->label_stock_array[$producto->PRODUCT_STOCK].'">'.$stock_array[$producto->PRODUCT_STOCK].'</td>';
		$data_array['form_html']					.= '<td>'.$countryprice[$result_data->QUOTE_PAIS]['currency'].$producto->$countryprice[$result_data->QUOTE_PAIS]['column'].'</td>';
		$data_array['form_html']					.= '</tr>';
		endforeach;
		$data_array['form_html'] .="</tbody>";
		$data_array['form_html'] .="</table>";
				
		
		return $data_array;
	}

	/**
	 * Funciones para editar Querys o Datos a enviar desde cada plugin
	 */
	//Funciï¿½n para desplegar listado, desde aquï¿½ se puede modificar el query
	public function _plugin_display($filter){
		$offset 	= (isset($filter[3]))?$filter[3]:0;
		$listpaises	=(isset($filter[0]))?$filter[0]:'GUATEMALA';
		$listmeses	=(isset($filter[1]))?$filter[1]:date('m');
		$listanios	=(isset($filter[2]))?$filter[2]:date('Y');
		
		
		
		$this->pagination_total_rows = $this->plugins_model->total_rows("QUOTE_PAIS = '".$listpaises."' AND MONTH(QUOTE_DATE)=".$listmeses." AND YEAR(QUOTE_DATE)=".$listanios); //Número total de items a desplegar
		$result_array['body'] = $this->plugins_model->list_rows('', "QUOTE_PAIS = '".$listpaises."' AND MONTH(QUOTE_DATE)=".$listmeses." AND YEAR(QUOTE_DATE)=".$listanios, $this->pagination_per_page, $offset);
			
		$result_array['filter']['paises'] = $listpaises;
		$result_array['filter']['meses'] = $listmeses;
		$result_array['filter']['anios'] = $listanios;
		
		return $this ->_html_plugin_display($result_array);
		
		
	}
	
	//Funciones de los posts a enviar
	public function post_new_val() {
		$submit_posts = $this -> input -> post();

		$submit_posts = array_map("entities_to_ascii", $submit_posts);
		return $this -> _set_new_val($submit_posts);
	}

	public function post_update_val($data_id) {
		$submit_posts = $this -> input -> post();

		$submit_posts = array_map("entities_to_ascii", $submit_posts);

		return $this -> _set_update_val($submit_posts);
	}
	 
	/**
	 * Funciones especï¿½ficas del plugin
	 */
	//Funcion que despliega las filas de una tabla (FROM)
	

}
