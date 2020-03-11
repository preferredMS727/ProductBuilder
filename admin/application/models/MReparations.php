<?php 
class MReparations extends CI_Model
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

	public function check($table, $wh) {
		$query = $this->db->query('SELECT * FROM '.$table.' WHERE '.$wh);
		return $query->result();
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
	 
    public function whereDetail($tablename='', $wh, $value)
    {
        $this->db->select('*');
		$this->db->from($tablename);
		$this->db->where($wh, $value);
        $this->db->order_by('id', 'asc');
        $query=$this->db->get();
		return $query->result();
	}

	public function updateData($table, $wh, $value, $data_array) {
		$this->db->where($wh, $value);
		$update_id = $this->db->update($table, $data_array);
		return $update_id;
	}

	public function addData($table, $data_array) {
		$insert = $this->db->insert($table, $data_array);
        if($insert){
            return $this->db->insert_id();
        } else {
            return false;    
        }
	}

	public function deleteData($table, $where, $del_id) {
		if ($this->db->delete($table, $where." = ".$del_id)) 
		{ 
		   return true; 
		} 
	}
}

