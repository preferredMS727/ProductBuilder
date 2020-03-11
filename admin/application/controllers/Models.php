<?php

use function PHPSTORM_META\type;

defined('BASEPATH') OR exit('No direct script access allowed');

class Models extends CI_Controller {

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
		$this->load->model('MModels');
	}

	public function index() {
		$data['brands'] = $this->MBrands->get_table('brands');
		$brand_id = 1;
		$data['models'] = $this->MModels->whereDetail('models', 'brand_id', $brand_id);
		$data['title'] = 'Models management';
		$this->load->view('models', $data);
	}

	public function getColors() {
		$colorsTable = $this->MBrands->get_table('colors');
		$color_array = array();
		for($i=0;$i<count($colorsTable);$i++) {
			$model_array[$i] = [$colorsTable[$i]->id, $colorsTable[$i]->name, $colorsTable[$i]->dataColor];
		}
		print_r(json_encode($model_array));
	}
	
	public function changeBrand() {
		$brandId = $_POST['id'];
		$models = $this->MModels->whereDetail('models', 'brand_id', $brandId);
		print_r(json_encode($models));
	}

	public function Open() {
		$brandId = $_POST['brandId'];
		$modelId = $_POST['modelId'];

		$wh = 'id = '.$modelId.' AND brand_id = '.$brandId;
		$model = $this->MModels->modelwhere('models', $wh);
		$image = json_decode($model[0]->img_color);
		$colors = json_decode($model[0]->colors);

		$model_array = array();
		for($i=0;$i<count($image);$i++) {
			$color = $this->MModels->whereDetail('colors', 'id', $colors[$i]);
			$model_array[$i] = [$color[0]->id, $image[$i], $color[0]->dataColor];
			// $model_array[$i] = ['image' => $image[$i], 'data-color' => $color[0]->dataColor, 'color' => $color[0]->color];
		}

		print_r(json_encode($model_array));
	}

	public function deleteModel() {
		$del_id = $_POST['del_id'];
		$delete = $this->MModels->deleteModel('models', 'id', $del_id);
		print_r($del_id);
	}

	public function SaveModel() {
		$id = $_POST['id'];
		$type = $_POST['type'];
		$brandId = $_POST['brandId'];
		$modelName = $_POST['modelName'];
		$modelImage = $_POST['modelImage'];
		$colorArray = $_POST['colorArray'];
		$imageArray = $_POST['imageArray'];

		$colors = '['.join(',', $colorArray).']';
		$images = '['.join(',', $imageArray).']';
		if($type === 'edit') {
			$data_array = ['name' => $modelName, 'image' => $modelImage, 'colors' => $colors, 'img_color' => $images];
			$update = $this->MModels->updateModel('models', 'id', $id, $data_array);
			print_r($update);
		} else if($type === 'add') {
			$data_array = ['name' => $modelName, 'image' => $modelImage, 'alt' => '', 'brand_id' => $brandId, 'colors' => $colors, 'img_color' => $images];
			$add = $this->MModels->addModel('models', $data_array);
			$return = [ 'id' => $add, 'name' => $modelName ];
			print_r(json_encode($return));
		}
	}
}
