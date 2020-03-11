<?php include("header.php") ; ?>

<style>
	.pt-3-half {
		padding-top: 1.4rem;
	}
</style>

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
            <h3 class="card-title">Selection</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <div class="card-body">

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Brand Name</label>
									<select class="form-control brand-select" onchange="changeBrand(event)" style="width: 100%;">
										<?php foreach ($brands as $brand) { ?>
											<option id="<?php echo $brand->id; ?>"><?php echo $brand->name; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Model Name</label>
									<select class="form-control model-select" onchange="changeModel(event)" style="width: 100%;">
										<?php foreach ($models as $model) { ?>
											<option id="<?php echo $model->id; ?>"><?php echo $model->name; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="btn-group mx-2">
								<button class="btn btn-primary" onclick="editReparation()">
									Edit Reparation
								</button>
							</div>
						</div>

					</div>
				</div>
				
				<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Reparation</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <div class="card-body">
						<!-- Editable table -->
						<div class="card">
							<!-- <h3 class="card-header text-center font-weight-bold text-uppercase py-4">Editable table</h3> -->
							<div class="card-body">
								<div id="table" class="table-editable">
									<span class="float-right mb-3 mr-2">
										<a href="javascript: saveAll();" class="text-success">
											<img src="https://img.icons8.com/cute-clipart/64/000000/save-all.png" width="32px">
										</a>
									</span>
									<span class="table-add float-right mb-3 mr-2">
										<a href="#" class="text-success">
											<i class="fas fa-plus fa-2x" aria-hidden="true"></i>
										</a>
									</span>
									
									<table class="table table-bordered table-responsive-md table-striped text-center">
										<thead>
											<tr>
												<th class="text-center" style="width: 30px;">No</th>
												<th class="text-center" style="width: 250px;">Name</th>
												<th class="text-center" style="width: 320px;">Description</th>

												<th class="text-center" style="width: 80px;">Part</th>
												<th class="text-center" style="width: 80px;">Work</th>
												<th class="text-center" style="width: 80px;">Commission</th>
												<th class="text-center" style="width: 80px;">Extra</th>

												<th class="text-center" style="width: 80px;">Remove</th>
											</tr>
										</thead>
										<tbody>
											<!-- <tr>
												<td class="pt-3-half" contenteditable="true">1</td>
												<td class="pt-3-half" contenteditable="true">Aurelia Vega</td>
												<td class="pt-3-half" contenteditable="true">30</td>
												<td class="pt-3-half" contenteditable="true">Deepends</td>
												<td class="pt-3-half" contenteditable="true">Spain</td>
												<td class="pt-3-half" contenteditable="true">Madrid</td>
												<td class="pt-3-half" contenteditable="true">Madrid</td>
												<td>
													<span class="table-remove">
														<button type="button" class="btn btn-danger btn-rounded btn-sm my-0">Remove</button>
													</span>
												</td>
											</tr> -->
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!-- Editable table -->
						<div class="export"></div>
					</div>
				</div>

      </div>
    </section>
  </div>

  <?php include("footer.php") ; ?>

	<script>
			let models = Array();
			let reparations = Array();
			let flagSaveAdd = '';
			let selectBrand = document.getElementsByClassName('brand-select')[0];
			let selectModel = document.getElementsByClassName('model-select')[0];
			brandId = selectBrand.options[selectBrand.selectedIndex].id;
			modelId = selectModel.options[selectModel.selectedIndex].id;

			changeBrand = function(e) {
				brandId = e.target.options[e.target.selectedIndex].id;
				$.ajax({
					url: 'Models/changeBrand/',
					type: 'post',
					data: {
						id: brandId
					},
					success: function(data) {
						// console.log(data);
						models = JSON.parse(data);

						let selectModel = document.getElementsByClassName('model-select')[0];
						console.log(selectModel);
						while (selectModel.hasChildNodes()) {
							selectModel.removeChild(selectModel.firstChild);
						}

						models.forEach(function(item) {
							// console.log(item['name']);
							let option = document.createElement("option");
							option.id = item['id'];
							option.text = item['name'];
							selectModel.appendChild(option);
						});
						if(selectModel.selectedIndex >= 0) {
							modelId = selectModel.options[selectModel.selectedIndex].id;
						} else {
							modelId = -1;
							// document.getElementsByClassName('model-form')[0].hidden = true;
						}

					},
					error: function(xhr, err) {
						console.error(err);
						modelId = -1;
					}
				});
			}

			changeModel = function(e) {
				console.log('change model!');
				modelId = e.target.options[e.target.selectedIndex].id;
				console.log(brandId);
				console.log(modelId);
			}

			editReparation = function(e) {
				$.ajax({
					url: 'Reparations/editReparation/',
					type: 'post',
					data: {
						modelId: modelId
					},
					success: function(data) {
						// console.log(data);
						reparations = JSON.parse(data);

						let tbodyElement = $('tbody')[0];
						while (tbodyElement.hasChildNodes()) {
							tbodyElement.removeChild(tbodyElement.firstChild);
						}

						let i = 1;
						reparations.forEach(function(reparation) {
							const newTr = `
								<tr class="hide" id="` + reparation['id'] + `">
									<td class="pt-3-half" contenteditable="true">` + i + `</td>
									<td class="pt-3-half" contenteditable="true">` + reparation['name'] + `</td>
									<td class="pt-3-half" contenteditable="true">` + reparation['description'] + `</td>
									<td class="pt-3-half" contenteditable="true">` + reparation['part_price'] + `</td>
									<td class="pt-3-half" contenteditable="true">` + reparation['work_price'] + `</td>
									<td class="pt-3-half" contenteditable="true">` + reparation['commission_price'] + `</td>
									<td class="pt-3-half" contenteditable="true">` + reparation['extra_price'] + `</td>
									<td>
										<span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Remove</button></span>
									</td>
								</tr>`;
							$('tbody').append(newTr);
							i++;
						});
					},
					error: function(xhr, err) {
						console.error(err);
					}
				});
			}

			const $tableID = $('#table');

			$('.table-add').on('click', 'i', () => {

				// const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');
				let No = 1;
				if($tableID.find('tbody tr').length) {
					No = Number($tableID.find('tbody tr').last()[0].children[0].innerHTML) + 1;
				}
				const newTr = `
					<tr class="hide" id="-1">
						<td class="pt-3-half" contenteditable="true">` + No + `</td>
						<td class="pt-3-half" contenteditable="true"></td>
						<td class="pt-3-half" contenteditable="true"></td>
						<td class="pt-3-half" contenteditable="true">0</td>
						<td class="pt-3-half" contenteditable="true">0</td>
						<td class="pt-3-half" contenteditable="true">0</td>
						<td class="pt-3-half" contenteditable="true">0</td>
						<td>
							<span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Remove</button></span>
						</td>
					</tr>`;
				$('tbody').append(newTr);
			});

			$tableID.on('click', '.table-remove', function () {
				$(this).parents('tr').detach();
				let del_id = $(this).parents('tr')[0].id;
				// console.log(del_id)
				$.ajax({
					url: 'Reparations/deleteData/',
					type: 'post',
					data: {
						del_id: del_id,
					},
					success: function(data) {
						console.log(data);
					},
					error: function(xhr, err) {
						console.error(err);
					}
				});
			});

			saveAll = function() {
				console.log('save all!');
				if(modelId > 0) {
					const $rows = $tableID.find('tr:not(:hidden)');
					const headers = [];
					const data = [];

					// Get the headers (add special header logic here)
					$($rows.shift()).find('th:not(:empty)').each(function () {

						headers.push($(this).text().toLowerCase());
					});

					// Turn all existing rows into a loopable array
					$rows.each(function () {
						const $td = $(this).find('td');
						const h = {};

						// Use the headers from earlier to name our hash keys
						headers.forEach((header, i) => {
							h[header] = $td.eq(i).text();
						});
						h['id'] = $(this)[0].id;
						data.push(h);
					});
					data.forEach(function(item) {
						delete item.remove;
					});
					// $('.export').text(JSON.stringify(data));
					$.ajax({
						url: 'Reparations/saveData/',
						type: 'post',
						data: {
							modelId: modelId,
							data: data
						},
						success: function(data) {
							console.log(data);
							reparations = JSON.parse(data);
							console.log(reparations)
							reparations.forEach(function(reparation) {
								$rows.each(function () {
									console.log($(this)[0].children[0].innerHTML)
									if($(this)[0].children[0].innerHTML == reparation['no']) {
										$(this)[0].id = reparation['id'];
									}
								});
							});
						},
						error: function(xhr, err) {
							console.error(err);
						}
					});
				} else {
					alert('You didn\'t select specitify model.');
				}
			}

			// A few jQuery helpers for exporting only
			jQuery.fn.pop = [].pop;
			jQuery.fn.shift = [].shift;
	</script>
