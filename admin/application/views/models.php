<?php include("header.php"); ?>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">

		<?php include("navbar.php"); ?>

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
							<h3 class="card-title">Model</h3>

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
									<button class="btn btn-primary" onclick="editModel()">
										Edit Model
									</button>
								</div>
								<div class="btn-group mx-2">
									<button class="btn btn-primary" onclick="addModel()">
										Add Model
									</button>
								</div>
								<div class="btn-group mx-2">
									<button class="btn btn-primary" onclick="deleteModel()">
										Delete Model
									</button>
								</div>
							</div>

						</div>

						<div class="card-body model-form" hidden>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group mx-2">
										<label for="modelNameInput">Model Name</label>
										<input type="text" class="form-control" id="modelNameInput" value="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group mx-2">
										<label for="modelImageInput">Model Image</label>
										<input type="text" class="form-control" id="modelImageInput" value="">
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- BEGIN EXAMPLE TABLE PORTLET-->
								<div class="portlet light portlet-fit bordered">
									<div class="portlet-body">
										<div id="sample_editable_1_wrapper" class="dataTables_wrapper no-footer">
											<div class="table-scrollable">
												<div id="mytable"></div>
												<table class="table table-striped table-hover table-bordered dataTable no-footer" id="DyanmicTable" role="grid" aria-describedby="sample_editable_1_info">
													<thead>
														<tr role="row">
															<th class="sorting_asc" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-sort="ascending" aria-label=" No : activate to sort column descending" style="width: 30px;"> No </th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" style="width: 196px;"> Image </th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" style="width: 194px;"> Color </th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" style="width: 80px;"> Edit </th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" style="width: 80px;"> Delete </th>
														</tr>
													</thead>
													<tbody id="mytbody"></tbody>
												</table>
											</div>

										</div>
									</div>
								</div>
								<!-- END EXAMPLE TABLE PORTLET-->
							</div>

							<div class="row">
								<div class="btn-group mx-2">
									<button class="btn btn-primary" onclick="Save()">
										Save Model
									</button>
								</div>
								<div class="btn-group mx-2">
									<button class="btn btn-primary" onclick="Add()">
										Add Item
									</button>
								</div>
							</div>
						</div>

					</div>
				</div>
			</section>
		</div>

		<?php include("footer.php"); ?>

		<script>
			let Allcolors = Array();
			let models = Array();
			let flagSaveAdd = '';
			let selectBrand = document.getElementsByClassName('brand-select')[0];
			let selectModel = document.getElementsByClassName('model-select')[0];
			brandId = selectBrand.options[selectBrand.selectedIndex].id;
			modelId = selectModel.options[selectModel.selectedIndex].id;

			initial();

			function initial() {
				$.ajax({
					url: 'Models/getColors/',
					type: 'post',
					success: function(data) {
						// console.log('data', data);
						Allcolors = JSON.parse(data);
					},
					error: function(xhr, err) {
						console.error(err);
					}
				});
				$.ajax({
					url: 'Models/changeBrand/',
					type: 'post',
					data: {
						id: brandId
					},
					success: function(data) {
						// console.log(data);
						models = JSON.parse(data);
					},
					error: function(xhr, err) {
						console.error(err);
						modelId = -1;
					}
				});
			}

			changeBrand = function(e) {
				brandId = e.target.options[e.target.selectedIndex].id;
				$.ajax({
					url: 'Models/changeBrand/',
					type: 'post',
					data: {
						id: brandId
					},
					success: function(data) {
						console.log(data);
						models = JSON.parse(data);

						let selectModel = document.getElementsByClassName('model-select')[0];
						console.log(selectModel);
						while (selectModel.hasChildNodes()) {
							selectModel.removeChild(selectModel.firstChild);
						}

						models.forEach(function(item) {
							console.log(item['name']);
							let option = document.createElement("option");
							option.id = item['id'];
							option.text = item['name'];
							selectModel.appendChild(option);
						});
						if(selectModel.selectedIndex >= 0) {
							modelId = selectModel.options[selectModel.selectedIndex].id;
						} else {
							modelId = -1;
							document.getElementsByClassName('model-form')[0].hidden = true;
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
			}

			function buildTable(data) {
				let tbody = document.getElementById('mytbody');
				console.log(tbody);
				while (tbody.hasChildNodes()) {
					tbody.removeChild(tbody.firstChild);
				}

				let i = 1;
				data.forEach(function(el) {
					var tr = document.createElement("tr");
					tr.id = el[0]; 

					var td = document.createElement("td");
					td.appendChild(document.createTextNode(i))
					tr.appendChild(td);

					// for (o in el) {  
					for (let i=1;i<=2;i++) {  
						var td = document.createElement("td");
						td.appendChild(document.createTextNode(el[i]))
						tr.appendChild(td);
					}

						var td = document.createElement("td");
							var a = document.createElement("a");
							a.appendChild(document.createTextNode('Edit'))
							a.className = 'edit';
							a.href = 'javascript: ;';
							a.onclick = function (event) { onEdit(event); };
						td.appendChild(a);

					tr.appendChild(td);
						
						var td = document.createElement("td");
							var a = document.createElement("a");
							a.appendChild(document.createTextNode('Delete'))
							a.className = 'delete';
							a.href = 'javascript: ;';
							a.onclick = function (event) { onDelete(event); };
						td.appendChild(a);

					tr.appendChild(td);

					tbody.appendChild(tr); 
					i++; 
				});
			}

			editModel = function() {
				// console.log(document.getElementsByClassName('model-select')[0].selectedIndex);
				console.log('open');
				if(document.getElementsByClassName('model-select')[0].selectedIndex>=0) {
					flagSaveAdd = 'edit';
					document.getElementById('modelNameInput').value = document.getElementsByClassName('model-select')[0].value;
					document.getElementById('modelImageInput').value = models.find( ({ id }) => id === modelId )['image'];
					document.getElementsByClassName('model-form')[0].hidden = false;
					$.ajax({
						url: 'Models/Open/',
						type: 'post',
						data: {
							brandId: brandId,
							modelId: modelId
						},
						success: function(data) {
							console.log(data);
							let colors = JSON.parse(data);
							buildTable(colors);

						},
						error: function(xhr, err) {
							console.error(err);
						}
					});
				} else {
					document.getElementsByClassName('model-form')[0].hidden = true;
				}
			}

			addModel = function() {
				flagSaveAdd = 'add';
				document.getElementsByClassName('model-form')[0].hidden = false;
				document.getElementById('modelNameInput').value = '';
				let tbodyElement = document.getElementById('mytbody');
				// console.log();
				while (tbodyElement.hasChildNodes()) {
					tbodyElement.removeChild(tbodyElement.firstChild);
				}
			}

			onEdit = function(e) {
				let trElement = event.target.parentNode.parentNode;
				if (trElement.hasChildNodes()) {
					var children = trElement.childNodes;
					console.log(children.length);
					let preValue = Array();

					for (var i = 1; i < children.length -3 ; i++) {
						let value = children[i].innerHTML;
						preValue.push(value);
						let input = document.createElement('input');
						input.value = value;
						children[i].innerHTML = '<input class="edit" style="width: 100%;" value="' + value +'">';
						console.log(value);
					}

					let value = children[2].innerHTML;
					let id = children[2].id;
					preValue.push(value);
					children[2].innerHTML = '<select class="my-1 w-100"></select>';
					let options = document.createElement('options');
					Allcolors.forEach(function(item) {
						let option = document.createElement("option");
						option.id = item[0];
						option.text = item[2];
						children[2].childNodes[0].appendChild(option);
					});
					children[2].childNodes[0].value = value;
					let a = children[children.length -2].childNodes[0];
						a.innerHTML = 'Ok';
						a.onclick = function(e) {
							onOk(e);
						}

					a = children[children.length -1].childNodes[0];
						a.innerHTML = 'Cancel';
						a.onclick = function(e) {
							onCancel(e, preValue);
						}
				}
			}

			onDelete = function(e) {
				let trElement = e.target.parentNode.parentNode;
				trElement.parentNode.removeChild(trElement);
			}

			onOk = function(e) {
				let trElement = e.target.parentNode.parentNode;

				let data_color =trElement.childNodes[2].childNodes[0].value;
				let color_id = 1;
				for(let i=0;i<Allcolors.length;i++) {
					if(Allcolors[i][2] == data_color) {
						color_id = Allcolors[i][0];
					}
				}

				let Data = Array();
				if (trElement.hasChildNodes()) {
					var children = trElement.childNodes;

					for (var i = 1; i < children.length -2 ; i++) {
						let inputValue = children[i].childNodes[0].value;
						children[i].innerHTML = inputValue;
						Data.push(inputValue);
						console.log(inputValue);
					}

					let a = children[children.length -2].childNodes[0];
						a.innerHTML = 'Edit';
						a.onclick = function(e) {
							onEdit(e);
						}

					a = children[children.length -1].childNodes[0];
						a.innerHTML = 'Delete';
						a.onclick = function(e) {
							onDelete(e);
						}
					trElement.id = color_id;
				}

			}

			onCancel = function(e, preValue) {
				let trElement = e.target.parentNode.parentNode;
				if (trElement.hasChildNodes()) {
					var children = trElement.childNodes;
					console.log(children.length);
					for (var i = 1; i < children.length -2 ; i++) {
						children[i].innerHTML = preValue[i-1];
					}

					let a = children[children.length -2].childNodes[0];
						a.innerHTML = 'Edit';
						a.onclick = function(e) {
							onEdit(e);
						}

					a = children[children.length -1].childNodes[0];
						a.innerHTML = 'Delete';
						a.onclick = function(e) {
							onDelete(e);
						}
				}
			}

			Add = function() {
				let tbodyElement = document.getElementById('mytbody');

				var tr = document.createElement("tr");
					let preValue = Array();
					
					var td = document.createElement("td");
					if(tbodyElement.lastChild) {
						td.appendChild(document.createTextNode(Number(tbodyElement.lastChild.childNodes[0].innerHTML) + 1));
					} else {
						td.appendChild(document.createTextNode(1));
					}
					tr.appendChild(td);

					let value = '';
					preValue.push(value);
					var td = document.createElement("td");
					td.innerHTML = '<input class="edit" style="width: 100%;" value="' + value +'">';
					tr.appendChild(td);

					preValue.push(value);
					var td = document.createElement("td");
					td.innerHTML = '<select class="my-1 w-100"></select>';
					let options = document.createElement('options');
					Allcolors.forEach(function(item) {
						let option = document.createElement("option");
						option.id = item[0];
						option.text = item[2];
						td.childNodes[0].appendChild(option);
					});
					tr.appendChild(td);

					var td = document.createElement("td");
							var a = document.createElement("a");
							a.appendChild(document.createTextNode('Ok'))
							a.className = 'edit';
							a.href = 'javascript: ;';
							a.onclick = function (event) { onOk(event); };
						td.appendChild(a);

					tr.appendChild(td);
						
						var td = document.createElement("td");
							var a = document.createElement("a");
							a.appendChild(document.createTextNode('Cancel'))
							a.className = 'delete';
							a.href = 'javascript: ;';
							a.onclick = function (event) { onCancel(event, preValue); };
						td.appendChild(a);

					tr.appendChild(td);

					tbodyElement.appendChild(tr); 
			}

			deleteModel = function() {
				console.log(modelId);

				if(modelId >= 0) {
					$.ajax({
						url: 'Models/deleteModel/',
						type: 'post',
						data: {
							del_id: modelId
						},
						success: function(data) {
							console.log('data', data);
							let del_id = JSON.parse(data);
							let selectModel = document.getElementsByClassName('model-select')[0];

							for( let i=0;i<selectModel.children.length;i++) {
								if(selectModel.children[i].id == del_id) {
									selectModel.children[i].remove();
								}
							}
							modelId = selectModel.options[selectModel.selectedIndex].id;

							console.log('brandId', brandId)
							console.log('modelId', modelId)
						},
						error: function(xhr, err) {
							console.error(err);
						}
					});
				}
			}

			Save = function() {
				console.log('model id : ', modelId);
				let modelName = document.getElementById('modelNameInput').value;
				let modelImage = document.getElementById('modelImageInput').value;
				let tbody = document.getElementById('mytbody');
				let colorArray = Array();
				let imageArray = Array();
				if(modelName && modelImage && tbody.hasChildNodes()) {
					let children = tbody.childNodes;
					for(let i=0;i<children.length;i++) {
						let status =children[i].childNodes[3].childNodes[0].innerHTML;
						let img = children[i].childNodes[1].innerHTML;
						let color = children[i].childNodes[1].innerHTML;
						let color_id = children[i].id;
						if((status == 'Edit') && img && color && color_id ) {
							colorArray.push(color_id);
							imageArray.push('"' + img + '"');
						}
					}
				}
				if(tbody.hasChildNodes()) {
					console.log(tbody);
				}
				$.ajax({
					url: 'Models/SaveModel/',
					type: 'post',
					data: {
						id: modelId,
						type: flagSaveAdd,
						brandId: brandId,
						modelName: modelName,
						modelImage: modelImage,
						imageArray: imageArray,
						colorArray: colorArray
					},
					success: function(data) {
						console.log('data', data);
						if(flagSaveAdd == 'add') {
							let savedData = JSON.parse(data);
							let selectModel = document.getElementsByClassName('model-select')[0];
							let option = document.createElement("option");
							option.id = savedData['id'];
							option.text = savedData['name'];
							selectModel.appendChild(option);
						}
					},
					error: function(xhr, err) {
						console.error(err);
					}
				});
			}
		</script>

