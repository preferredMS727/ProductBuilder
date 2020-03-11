<?php 
class MModels extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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
	
	public function modelwhere($tablename='', $wh = '') {
		$query = $this->db->query('SELECT * FROM models WHERE '.$wh);
		// $query = $this->db->query('SELECT * FROM models WHERE id = 1 AND brand_id = 1');
		return $query->result();
	}

	public function data_color($tablename='', $wh, $value)
    {
        $this->db->select('dataColor');
		$this->db->from($tablename);
		$this->db->where($wh, $value);
        $this->db->order_by('id', 'asc');
        $query=$this->db->get();
		return $query->result();
	}

	public function deleteModel($table, $where, $del_id) {
		if ($this->db->delete($table, $where." = ".$del_id)) 
		{ 
		   return true; 
		} 
	}

	public function updateModel($table, $wh, $value, $data_array) {
		$this->db->where($wh, $value);
		$update_id = $this->db->update($table, $data_array);
		return $update_id;
	}

	public function addModel($table, $data_array) {
		$insert = $this->db->insert($table, $data_array);
        if($insert){
            return $this->db->insert_id();
        } else {
            return false;    
        }
	}
}
