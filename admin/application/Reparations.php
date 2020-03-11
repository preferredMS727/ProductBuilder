<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reparations extends CI_Controller {

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
	private $client = "0J098moirjv0909JSmvp";

	function __construct() {
		parent::__construct();
		$this->load->model('MReparations');
	}

	public function index()
	{
		$data['brands'] = $this->MReparations->get_table('brands');
		$brand_id = 1;
		$data['models'] = $this->MReparations->whereDetail('models', 'brand_id', $brand_id);
		$data['title'] = 'Reparations management';
		$this->get_repair();
		// $data['star'] = $this->getPrice();
		// print_r($data['star']);
		$this->load->view('reparations', $data);
	}

	public function editReparation() {
		// $brandId = $_POST['brandId'];
		$modelId = $_POST['modelId'];
		$reparation = $this->MReparations->whereDetail('accessories', 'model_id', $modelId);
		print_r(json_encode($reparation));
	}

	public function saveData() {
		$modelId = $_POST['modelId'];
		$data = $_POST['data'];

		$addId = array();
		// $updateId = array();
		foreach($data as $item) {
			$data_array =  ['name' => $item['name'], 'description' => $item['description'],
							'part_price' => $item['part'], 'work_price' => $item['work'],
							'commission_price' => $item['commission'], 'model_id' => $modelId];
			if($item['id'] > 0) {
				$update_id = $this->MReparations->updateData('accessories', 'id', $item['id'], $data_array);
				// print_r($update_id);
			} else {
				$add_id = $this->MReparations->addData('accessories', $data_array);
				array_push($addId, ['id' => $add_id, 'no' => $item['no']]);
				// print_r($add_id);
			}
		}
		print_r(json_encode($addId));
	}

	public function deleteData() {
		$del_id = $_POST['del_id'];
		if($this->MReparations->deleteData('accessories', 'id', $del_id)) {
			print_r('deleted!');
		}
	}

	public function get_repair() {
		$tok = "item_list";

		$url = "http://api.areaaccessories.it/api?"."tok=".$tok."&client=".$this->client;

		$request_headers = array();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 6000);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
        ini_set('max_execution_time', 900); 
		set_time_limit(900);
		
		if (curl_errno($ch)) {
			print "Error: " . curl_error($ch);
		} else {
			$object = json_decode($data)->items;
			$array = array();
			foreach($object as $value) {
				$test = str_replace('  ', ' ', $value->item);
				$pos = strpos($test, ' PER');
				$title = substr($test, 0, $pos);
                $analysis = substr(substr($test, $pos), 5);
                $model = strtolower(substr($analysis, 0));
                $model = rtrim($model, '-');
				$model = str_replace('-', '', $model);
				$model = str_replace(' ', '', $model);
				$model = str_replace('+', 'plus', $model);
				$models = $this->MReparations->get_table('models');
				$modelId = -1;
				foreach($models as $modelitem) {
					if (strpos($model,  str_replace(' ', '', strtolower($modelitem->name))) !== false) {
						$modelId = $modelitem->id;
						$wh = 'code = "'.$value->code.'"';
						// $wh = 'name = "'.$title.'" AND model_id = '.$modelId;
						if(empty($this->MReparations->check('accessories', $wh)))  {
							$price = $this->getPrice($value->code);
							$insert_array = ['name' => $title, 'model_id' => $modelId, 'part_price' => $price, 'code' => $value->code];
							$this->MReparations->insert('accessories', $insert_array);
							print_r($insert_array);
						}
					}
				}
            }
		}	  
	}
	
	public function getPrice($code) {
		$tok = "item_find";
		// $code = 'SPD2195W';
		$url = "http://api.areaaccessories.it/api?"."tok=".$tok."&client=".$this->client.'&code='.$code.'&required_qty=1';

		$request_headers = array();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);

		if (curl_errno($ch)) {
			print "Error: " . curl_error($ch);
		} else {
			$object = json_decode($data);
			return $object->price;
			// print_r($object->price);
		}	
	}
}
