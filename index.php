<?php 
	include_once("config/configuration.php");

	// $statement =  mysqli_query($connection,"select * from brands where roomtypecode = '".$roomtypecode."'order by id ASC");
	$brands_statement =  mysqli_query($connection,"select * from brands order by id ASC");
			
	$brands = array();

	if(mysqli_num_rows($brands_statement) > 0 )
	{
		while($rowww = mysqli_fetch_assoc($brands_statement)):
			$brands[] = $rowww;							
		endwhile;													
		
	}				
	else
	{
		die("No data found.");
	}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	
	<title>Valutatore Riparazioni</title>
</head>
<body>
<div class="cd-product-builder">
	<header class="main-header">
		<h1>Seleziona la tua riparazione</h1>
		<?php 
		?>

		<!-- <div class="mt-5" style="background-color: red">Testig is Good.</div> -->

		<nav class="cd-builder-main-nav disabled">
			<ul>
				<li class="active"><a href="#brands">Marca</a></li>
				<li><a href="#models">Modello</a></li>
				<li><a href="#colors">Colore</a></li>
				<li><a href="#accessories">Riparazione</a></li>
				<li><a href="#summary">Sommario</a></li>
			</ul>
		</nav>

		
	</header>

	<div class="cd-builder-steps">
		<ul>
			<li data-selection="brands" id="brands" class="active builder-step">
				<section class="cd-step-content">
					<header>
						<h1>Select Brand</h1>
						<span class="steps-indicator">Step <b>1</b> of 5</span>
					</header>
					<ul class="models-list options-list cd-col-2">
						<?php $i=1; foreach($brands as $brand) { ?>
							<li class="js-brand js-radio" dataBrand=<?php echo $brand['name'] ?> data-brandid=<?php echo $brand['id'] ?>>
								<span class="name"><?php echo $brand['name'] ?></span>
								<img src=<?php echo "img/".$brand['image']; ?>>
								<span class="price"></span>
								<div class="radio"></div>
							</li>
						<?php $i++; } ?>
					</ul>
				</section>
			</li>

		</ul>
	</div>

	<footer class="cd-builder-footer disabled step-1">
		<div class="selected-product">
			<img src="img/product01_col01.jpg" alt="Product preview">

			<div class="tot-price">
				<span>Public</span>
				<span class="total">€<b>0</b></span>
			</div>
			<div class="shprice" hidden>
				<span>Dealer</span>
				<span class="sh-price">€<b>0</b></span>
			</div>
		</div>

		<ul class="" style="position:fixed; right:300px; margin-top:42px">
			<div class="onoffswitch">
				<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" onchange="showHidden(event)" checked>
				<label class="onoffswitch-label" for="myonoffswitch">
					<span class="onoffswitch-inner"></span>
					<span class="onoffswitch-switch"></span>
				</label>
			</div>
		</ul>
		<nav class="row cd-builder-secondary-nav">
			<ul>
				<li class="next nav-item">
					<ul>
						<li class="visible"><a href="#0">Modello</a></li>
						<li><a href="#0">Colore</a></li>
						<li><a href="#0">Riparazione</a></li>
						<li><a href="#0">Sommario</a></li>
						<li class="buy"><a href="#0">Prenota</a></li>
					</ul>
				</li>
				<li class="prev nav-item">
					<ul>
						<li class="visible"><a href="#0">Marca</a></li>
						<li><a href="#0">Marca</a></li>
						<li><a href="#0">Modello</a></li>
						<li><a href="#0">Colore</a></li>
						<li><a href="#0">Riparazione</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<span class="alert">Perfavore seleziona una Marca!</span>
		<!-- <span class="alertNot">No exist with the brand!</span> -->
	</footer>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script>
	if( !window.jQuery ) document.write('<script src="js/jquery-3.0.0.min.js"><\/script>');
</script>
<script src="js/main.js"></script> <!-- Resource jQuery -->
</body>
</html>