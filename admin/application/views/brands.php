<?php include("header.php") ; ?>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <?php include("navbar.php") ; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo $title ?></h1>
					</div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $title ?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Brand</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <div class="card-body">

						<div class="row">
							<div class="btn-group mx-2">
								<button class="btn btn-primary" onclick="AddBrand()">
									Add New <i class="fa fa-plus"></i>
								</button>
							</div>
							<div class="btn-group mx-2">
								<button class="btn btn-primary" onclick="EditBrand()">
									Edit 
									<!-- <i class="fa fa-pencil fa-fw"></i> -->
								</button>
							</div>
							<div class="btn-group mx-2">
								<button class="btn btn-primary" onclick="Delete()">
									Delete
								</button>
							</div>
						</div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Brand Name</label>
									<select class="form-control brands-name-select" onchange="changeBrand(event)" style="width: 100%;">
										<?php foreach($brands as $brand) { ?>
											<option id="<?php echo $brand->id; ?>"><?php echo $brand->name; ?></option>
										<?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
								<div class="form-group">
                  <label>Brand Image</label>
                  <select class="form-control brands-image-select" disabled="disabled" style="width: 100%;">
										<?php foreach($brands as $brand) { ?>
											<option><?php echo $brand->image; ?></option>
										<?php } ?>
                  </select>
                </div>
								<!-- <div class="form-group">
                  <label for="brandNameOutput">Brand Image</label>
                  <input type="text" class="form-control" id="brandNameOutput" value="<?php echo $brands[0]->image ?>" disabled>
                </div> -->
              </div>
						</div>

						<form role="form" action="Brands/save" method="POST" class="border p-2 saveform" hidden>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="brandNameInput">Brand Name</label>
										<input type="text" class="form-control" id="brandNameInput" name="brandNameInput" value="" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="brandImageInput">Brand Image</label>
										<input type="text" class="form-control" id="brandImageInput" name="brandImageInput" value="" required>
									</div>
								</div>
							</div>

							<input type="hidden" class="form-control" id="brandImageId" name="brandImageId" value="<?php echo $brands[0]->id; ?>">
							<input type="hidden" class="form-control" id="brandEditType" name="brandEditType" value="add">

							<div class="row">
								<div class="btn-group pull-right mx-2">
									<button class="btn btn-primary" type="submit">
										Save
									</button>
								</div>
							</div>

						</form>

          </div>
        </div>
      </div>
    </section>
	</div>
	<script>
		let flagAddEdit = 0;

		FlagHandler = function() {
			console.log(flagAddEdit);
			
			if(flagAddEdit == 0) {
				console.log('hidden!');
				document.getElementsByClassName('saveform')[0].hidden = true;
			} else if(flagAddEdit == 1) {
				console.log('Edit!');
				document.getElementsByClassName('saveform')[0].hidden = false;

				let selectElement = document.getElementsByClassName('brands-name-select')[0];
				document.getElementById('brandNameInput').value = selectElement.options[selectElement.selectedIndex].text;

				let imageElement = document.getElementsByClassName('brands-image-select')[0];
				document.getElementById('brandImageInput').value = imageElement.options[imageElement.selectedIndex].text;

				document.getElementById('brandEditType').value = 'edit';

			} else if(flagAddEdit == 2) {
				console.log('Add!');
				document.getElementsByClassName('saveform')[0].hidden = false;
				document.getElementById('brandNameInput').value = '';
				document.getElementById('brandImageInput').value = '';

				document.getElementById('brandEditType').value = 'add';
			}
		}

		EditBrand = function() {
			if(flagAddEdit == 1) { flagAddEdit = 0; } else { flagAddEdit = 1; }
			FlagHandler();
		}

		AddBrand = function() {
			if(flagAddEdit == 2) { flagAddEdit = 0; } else { flagAddEdit = 2; }
			FlagHandler();
		}

		changeBrand = function(e) {
			// console.log('change!');
			let selectName = document.getElementsByClassName('brands-name-select')[0];
			let selectImage = document.getElementsByClassName('brands-image-select')[0];

			let nameElement = selectName.options[selectName.selectedIndex];
			let imageElement = selectImage.options[e.target.selectedIndex];

			// console.log(imageElement.text);
			selectImage.selectedIndex = e.target.selectedIndex;

			document.getElementById('brandImageId').value = nameElement.id;
			if(flagAddEdit == 1) {
				document.getElementById('brandNameInput').value = nameElement.text;
				document.getElementById('brandImageInput').value = imageElement.text;
			} 
		}

		Delete = function() {
			let selectElement = document.getElementsByClassName('brands-name-select')[0];
			let delId = selectElement.options[selectElement.selectedIndex].id;

			$.ajax({url: 'Brands/delete/',
				type: 'post',
				data: {
					del_id: delId
				},
				success: function(data){
					console.log(data);
					location.reload();
				},
				error: function(xhr, err){
					console.error(err);
				}
			});
		}
	</script>
	<?php include("footer.php") ; ?>


