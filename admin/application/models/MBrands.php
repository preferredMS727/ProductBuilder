<?php 
class MBrands extends CI_Model
{
	function __construct()
    {
		parent::__construct();
		$this->load->database();
    }
  	public function get_table($tablename='') { 
		$this->db->select('*');
		$this->db->from($tablename);
	  	$this->db->order_by('id','asc');
		$query=$this->db->get();
		return $query->result();
	}

	public function update($table, $column, $value, $data_array) {
		$this->db->where($column, $value);
		$this->db->update($table, $data_array);
		return true;
	}

	public function insert($tablename,$data) {
		$insert = $this->db->insert($tablename,$data);
		if($insert)
		{
			return $this->db->insert_id();
		}else{
			return false;    
		}
	 }  
	 
	public function delete($tablename,$where,$services_id) 
	{ 
		if ($this->db->delete($tablename, $where."= ".$services_id)) 
		{ 
		   return true; 
		} 
	}
}

