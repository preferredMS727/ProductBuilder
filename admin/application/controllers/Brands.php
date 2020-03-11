<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class brands extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();
		$this->load->model('MBrands');
		$this->load->helper('url'); 
	}

	public function index()
	{
		$data['brands'] = $this->MBrands->get_table('brands');
		$data['title'] = 'Brands management';
		$this->load->view('brands', $data);
	}

	public function save() {
		// redirect('/');
		$brandName = $this->input->post('brandNameInput');
		$brandImage = $this->input->post('brandImageInput');
		$brandId = $this->input->post('brandImageId');
		$brandEditType = $this->input->post('brandEditType');

        if ($brandEditType == 'edit') {
            $edit_array = [
				'name' => $brandName,
				'image' => $brandImage
        	];
            $this->MBrands->update('brands', 'id', $brandId, $edit_array);
		} else if($brandEditType == 'add') {
			$add_array = [
				'name' => $brandName,
				'image' => $brandImage
			];
			$this->MBrands->insert('brands', $add_array);
		}
		
		header("Location: http://".$_SERVER['SERVER_NAME']."/admin");
	}

	public function delete() {
		$delete_id = $_POST['del_id'];
		$this->MBrands->delete('brands', 'id', $delete_id);
		echo 'Have deleted the row.';
	}
}
