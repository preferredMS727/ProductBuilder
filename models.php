<?PHP 
	include_once("config/configuration.php");

	$brandId = $_GET['brandId'];
	// echo $brandId;
	$models_statement =  mysqli_query($connection,"select * from models where brand_id = ".$brandId." order by id DESC");
			
	$models = array();

	if(mysqli_num_rows($models_statement) > 0 )
	{
		while($rowww = mysqli_fetch_assoc($models_statement)):
			$models[] = $rowww;							
		endwhile;													
		
	}				
	else
	{
		// die("No data found.");
		return false;
	}
?>
<li data-selection="models" id="brands" class="active builder-step">
	<section class="cd-step-content">
		<header >
			<h1>Select Model</h1>
			<span class="steps-indicator">Step <b>2</b> of 5</span>
		</header>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">Cerca</span>
				<input type="text" onkeyup="searchModels(event, 'models')" name="search" id="search_text" placeholder="Cerca per Modello" class="form-control" />
			</div>
		</div>

		<ul class="models-list options-list cd-col-2">
			<?php $i=1; foreach($models as $model) { ?>
				<li class="js-option models-list-search js-radio" data-price="" data-dealer="" data-model=<?php echo $model['alt'] ?> data-modelid=<?php echo $model['id'] ?>>
					<span class="searchText"><?php echo $model['name'] ?></span>
					<img src=<?php echo "img/".$model['image'] ?> alt=<?php echo $model['alt'] ?>>
					<span class="price"></span>
					<div class="radio"></div>
				</li>
			<?php $i++; } ?>
		</ul>
	</section>
</li>