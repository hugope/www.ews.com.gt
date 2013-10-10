<?php
class Cms_plugin_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
		$this->load->library('upload');
    }
    public function initialise($current_table)
    {
        $this->_table = $current_table;
    }
    
    public function display_result(){
        $query = $this->db->get($this->_table);
        
        return $query->result();
    }
	 
	 /**
	  * Plugin Email
	  */
	  public function update_email_data($update_array){
	  	//Actualizar cada dato en el framework resource
	  	foreach($update_array as $label => $value):
			$data['RESOURCE_DETAIL'] = $value;
			$this->db->where('RESOURCE_LABEL', $label);
			$result[] = $this->db->update('FRAMEWORK_RESOURCE', $data);
		endforeach;
		$message_key = (in_array(FALSE, $result))?4011:4010;
		return $message_key;
	  }
	  /**
	   * Plugin catalogo
	   */
	  //Despliega los datos de la tabla PLUGIN_CATEGORY (FROM)
	  public function desplegar_cat (){
	  	
		$query = $this->db->get('PLUGIN_CATEGORY');
		$categorias= ($query->result());
	  	$catReturn=array();
		
		foreach($categorias as $cat){
			$catReturn[$cat->ID] = $cat->CATEGORY_NAME;
		}
		return $catReturn;
	  }
	  //Muestra el nombre del id de la tabla usuarios
	  public function id_usuario (){
	  	
		$query = $this->db->get('PLUGIN_USERS');
		$usuarios= ($query->result());
	  	$retornaUsuario=array();
		
		foreach($usuarios as $user){
			$retornaUsuario[$user->ID] = $user->USER_NAME;
		}
		return $retornaUsuario;
		
	  
		/*		
		$query = $this->db->join("PLUGIN_QUOTE", "PLUGIN_QUOTE.QUOTE_USER = PLUGIN_USERS.ID");
		$query = $this->db->get("PLUGIN_USERS");
		return $query->result();*/
		
	  }
	  public function tabla_carrito_id($idcot)
	  {
	  	$query = $this->db->select('PP.*')
		->from('PLUGIN_PRODUCTS PP')
		->join('PLUGIN_CART PC', 'PP.ID = PC.CART_PRODUCT')
		->where('PC.CART_QUOTE', $idcot)->get();
		
		return $query->result();
	  }
	  public function carrito_cantidad($idc){
	  	$this->db->select('CART_QUANTITY');

		$query = $this->db->get('PLUGIN_CART');
		return $query;
	  }
	
	  	  
}