<?php 
    include_once("config/configuration.php");
    // Get colors from models table in database
	$modelId = $_GET['modelId'];
	// $modelId = 1;
	$models_statement =  mysqli_query($connection,"select * from models where id = ".$modelId." order by id DESC");
	$models = array();
	if(mysqli_num_rows($models_statement) > 0 )
	{
		while($rowww = mysqli_fetch_assoc($models_statement)):
			$models[] = $rowww;							
		endwhile;													
	}				
	else
	{
		die("No data found.");
    }
	$array_colors = json_decode($models[0]['colors']);
	$array_imgs = json_decode($models[0]['img_color']);
	// echo 'test     :'.json_encode($array_imgs);

    // Get colors from colors table in database
	$where = '';
	$array_where = array();
	if($array_colors && $array_imgs) {
		foreach($array_colors as $item) {
			array_push($array_where, 'id = '.$item);
		}
		$where_query = join(' OR ', $array_where);
		$colors_statement =  mysqli_query($connection,"select * from colors where ".$where_query." order by id ASC");
		$colors = array();
		if(mysqli_num_rows($colors_statement) > 0 )
		{
			while($rowww = mysqli_fetch_assoc($colors_statement)):
				$colors[] = $rowww;							
			endwhile;													
		}				
		else
		{
			// die("No data found.");
			// return false;
		}
	}

		// echo json_encode($colors);

	// Get accessories from accessories table in database
	$modelId = $_GET['modelId'];
	$on = 0;	
	$accessories_statement =  mysqli_query($connection,"select * from accessories where model_id = ".$modelId." and repair_view = ".$on." order by id ASC");
	$accessories = array();
	if(mysqli_num_rows($accessories_statement) > 0 )
	{
		while($rowww = mysqli_fetch_assoc($accessories_statement)):
			$accessories[] = $rowww;							
		endwhile;													
	}				
	else
	{
		// die("No data found.");
		// return false;
	}
	// echo json_encode($accessories);

?>

<li data-selection="colors" id="colors" class="builder-step first-load">
	<section class="cd-step-content">
		<header>
			<h1>Select Color</h1>
			<span class="steps-indicator">Step <b>3</b> of 5</span>
		</header>

		<?php if(!$array_imgs) { ?>
			<h2 style="text-align: center; margin-top: 180px;">NO COLOR FOR THIS MODEL</h2>
		<?php } ?>

		<ul class="cd-product-previews">
			<?php if($array_imgs) { ?>
				<?php $i = 0; foreach($array_imgs as $color) { ?>
					<li <?php if($i==0) { echo "class='selected'"; } ?>><img src=<?php echo "img/".$color ?> alt="Product Preview" class="product-preview"></li>
				<?php $i++; } ?>
			<?php } ?>
		</ul>

		<ul class="cd-product-customizer">
			<?php if($array_imgs) { ?>
				<?php $i = 0; foreach ($colors as $color) { ?>
					<li data-content=<?php echo $color["name"] ?> data-price="0" data-dealer="0" <?php if ($i==0) { echo "class='selected'"; } ?>>
					<a href="#0" <?php echo 'style="background-color: '.$color['color'].';"'; ?> ><?php echo $color["name"] ?></a>
					</li>
				<?php $i++; } ?>
			<?php } ?>
		</ul>
	</section>
</li>

<li data-selection="accessories" id="accessories" class="builder-step first-load">
	<section class="cd-step-content">
		<header>
			<h1>Accessories</h1>
			<span class="steps-indicator">Step <b>4</b> of 5</span>
		</header>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">Cerca</span>
				<input type="text" onkeyup="searchModels(event, 'accessories')" name="search" id="search_text" placeholder="Cerca per Riparazione" class="form-control" />
			</div>
		</div>

		<?php if(!$accessories) { ?>
			<h2 style="text-align: center; margin-top: 180px;">NO REPAIR FOR THIS MODEL</h2>
		<?php } ?>

        <ul class="accessories-list options-list">
			<?php foreach($accessories as $accessory) { ?>
				<li class="js-option accessories-list-search" data-price=<?php echo $accessory['total_price'] ?> data-dealer=<?php echo $accessory['dealer_price']; ?>>
					<p class="searchText1"><?php echo $accessory['name'] ?></p>
					<span class="price">€<?php echo $accessory['total_price'] ?></span>
					<div class="check"></div>
				</li>
			<?php } ?>
		</ul>
	</section>
</li>

<li data-selection="summary" id="symmary" class="builder-step first-load">
	<section class="cd-step-content">
		<header>
			<h1>Summary</h1>
			<span class="steps-indicator">Step <b>5</b> of 5</span>
		</header>
		
		<ul class="summary-list">
			<li>
				<div class="brandAndModel"><h2>Modello</h2></div>

				<img src="img/product01_col01.jpg" alt="Alfa Romeo Giulietta" class="product-preview">
				
				<h3>Attenzione!</h3>
				
				<p>
					Si ricorda la gentile clientela che DGH-Lab non si assume nessuna responsabilità circa i dati contenuti nel Device,</br> pertanto consigliamo di fare un Backup preventivo dei dati presenti.	
				</p>
			</li>

			<li data-summary="colors">
				<h2>Colore</h2>
				
				<span class="summary-color">
					<em class="color-swatch" data-color="red"></em>
					<em class="color-label">Red Passion</em>
				</span>
			</li>

			<li data-summary="accessories">
				<h2>Riparazione Selezionata</h2>

				<ul class="summary-accessories">
					<li>
						<p>Uconnect 6.5" colour touchscreen radio navigation sytem with Bluetooth &amp; DAB ($1050)</p>
					</li>

					<li>
						<p>Audio &amp; telephone controls on steering wheels ($750)</p>
					</li>
				</ul>
			</li>
		</ul>
	</section>
</li>