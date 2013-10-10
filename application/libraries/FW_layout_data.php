<?php
/**
 * Desplegar datos para el header y footer
 */
class FW_layout_data {
	
	var $FW;
	function __construct(){
		$this->FW			=& get_instance();
		$this->FW->load->model('plugins/layout_model', 'layout_model');
		$this->FW->load->helper('utilities');
	}
	
	/**
	 * Función que despliega los datos para el header
	 */
	public function header_data(){
		$data['external_files'] = array(
									load_external_file('clear.css', 'css'),
									
									load_external_file('//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', 'js', false)
									);
		return $data;
	}
}
