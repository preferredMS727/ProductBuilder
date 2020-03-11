jQuery(document).ready(function($){
	console.log('document.ready');
	function ProductBuilder( element ) {
		console.log('functin ProductBuilder( element )');
		this.element = element;
		this.stepsWrapper = this.element.children('.cd-builder-steps');
		this.steps = this.element.find('.builder-step');
		//store some specific bulider steps
		this.brands = this.element.find('[data-selection="brands"]'); 
		this.models = this.element.find('[data-selection="models"]'); 
		this.brands_models = this.element.find('[data-selection="brands"]', '[data-selection="models"]'); 
		this.summary;
		this.optionsLists = this.element.find('.options-list');
		//bottom summary 
		this.fixedSummary = this.element.find('.cd-builder-footer');
		this.modelPreview = this.element.find('.selected-product').find('img');
		this.totPriceWrapper = this.element.find('.tot-price').find('b');
		this.dealerPriceWrapper = this.element.find('.shprice').find('b');
		//builder navigations
		this.mainNavigation = this.element.find('.cd-builder-main-nav');
		this.secondaryNavigation = this.element.find('.cd-builder-secondary-nav');
		//used to check if the builder content has been loaded properly
		this.loaded = true;
		
		// bind builder events
		this.bindEvents();
	}

	ProductBuilder.prototype.bindEvents = function() {
		console.log('ProductBuilder.prototype.bindEvents = function()');
		var self = this;

		//detect click on the left navigation
		this.mainNavigation.on('click', 'li:not(.active)', function(event){
			// console.log('test!');
			// console.log('test!', $(this).index());
			event.preventDefault();
			var stepNumber = ( $(this).parents('.next').length > 0 ) ? $(this).index() + 1 : $(this).index() - 1;
			console.log('stepNumber : ', stepNumber);
			console.log('repair length : ', $('.accessories-list.options-list li').length);
			if( (stepNumber >= 2) && ($('.cd-product-customizer li').length < 1)) {
				console.log('color empty!');
				console.log('this index : ', $(this).index());
				if($(this).index() < 3) {
					self.loaded && self.newContentSelected($(this).index());
				}
			} else if( (stepNumber >= 3) && ($('.accessories-list.options-list li').length < 1)) {
				console.log('repair empty!');
			} else {
				self.loaded && self.newContentSelected($(this).index());
			}
		});

		//detect click on bottom fixed navigation
		this.secondaryNavigation.on('click', '.nav-item li:not(.buy)', function(event){ 
			console.log('secondaryNavigation click');
			event.preventDefault();
			var stepNumber = ( $(this).parents('.next').length > 0 ) ? $(this).index() + 1 : $(this).index() - 1;
			let modelSelectedFlag = self.element.find('.js-option.models-list-search.js-radio.selected.loaded').length;

			if(stepNumber == 2) {
				if(modelSelectedFlag) {
					self.loaded && self.newContentSelected(stepNumber);
				}
			} else if( (stepNumber == 3) && ($('.cd-product-customizer li').length < 1)) {
				console.log('color empty!');
			} else if( (stepNumber == 4) && ($('.accessories-list.options-list li').length < 1)) {
				console.log('repair length : ', $('.accessories-list.options-list li').length);
				console.log('repair empty!');
			} else if(stepNumber != -1) {
				self.loaded && self.newContentSelected(stepNumber);
			}

			console.log('stepNumber : ', stepNumber);
			console.log('colors length : ', $('.cd-product-customizer li').length);
		});

		this.optionsLists.on('click', '.js-brand', function(event){
			self.updateListBrand($(this));
		});

		//detect click on one element in an options list (e.g, models, accessories)
		this.optionsLists.on('click', '.js-option', function(event){
			self.updateListOptions($(this));
		});

		//detect clicks on customizer controls (e.g., colors ...)
		this.stepsWrapper.on('click', '.cd-product-customizer a', function(event){
			event.preventDefault();
			self.customizeModel($(this));
		});
	};

	ProductBuilder.prototype.newContentSelected = function(nextStep) {
		console.log('ProductBuilder.prototype.newContentSelected = function(nextStep)');
		//first - check if a model has been selected - user can navigate through the builder
		if( this.fixedSummary.hasClass('disabled') ) {
			//no model has been selected - show alert
			this.fixedSummary.addClass('show-alert');
		} else {
			//model has been selected so show new content 
			//first check if the color step has been completed - in this case update the product bottom preview
			if( this.steps.filter('.active').is('[data-selection="colors"]') ) {
				//in this case, color has been changed - update the preview image
				var imageSelected = this.steps.filter('.active').find('.cd-product-previews').children('.selected').children('img').attr('src');
				this.modelPreview.attr('src', imageSelected);
			}
			//if Summary is the selected step (new step to be revealed) -> update summary content
			if( nextStep + 1 >= this.steps.length ) {
				this.createSummary();
			}
			// console.log('builder steps : ', this.steps);
			// console.log('steps lengths : ', this.steps.length);
			this.showNewContent(nextStep);
			this.updatePrimaryNav(nextStep);
			this.updateSecondaryNav(nextStep);
		}
	}

	ProductBuilder.prototype.showNewContent = function(nextStep) {
		console.log('ProductBuilder.prototype.showNewContent = function(nextStep)');
		var actualStep = this.steps.filter('.active').index() + 1;
		console.log('actualStep : ', actualStep);
		console.log('nextStep : ', nextStep);
		if( actualStep < nextStep + 1 ) {
			//go to next section
			this.steps.eq(actualStep-1).removeClass('active back').addClass('move-left');
			this.steps.eq(nextStep).addClass('active').removeClass('move-left back');
		} else {
			//go to previous section
			this.steps.eq(actualStep-1).removeClass('active back move-left');
			this.steps.eq(nextStep).addClass('active back').removeClass('move-left');
		}
	}

	ProductBuilder.prototype.updatePrimaryNav = function(nextStep) {
		console.log('ProductBuilder.prototype.updatePrimaryNav = function(nextStep)');
		this.mainNavigation.find('li').eq(nextStep).addClass('active').siblings('.active').removeClass('active');
	}

	ProductBuilder.prototype.updateSecondaryNav = function(nextStep) {
		console.log('ProductBuilder.prototype.updateSecondaryNav = function(nextStep)');
		( nextStep == 0 ) ? this.fixedSummary.addClass('step-1') : this.fixedSummary.removeClass('step-1');
		// if( (nextStep == 2) && ($('.cd-product-customizer li').length < 1)) {
		// 	console.log('color empty!');
		// }
		// console.log('next step : ', nextStep);
		this.secondaryNavigation.find('.nav-item.next').find('li').eq(nextStep).addClass('visible').removeClass('visited').prevAll().removeClass('visited').addClass('visited').end().nextAll().removeClass('visible visited');
		this.secondaryNavigation.find('.nav-item.prev').find('li').eq(nextStep).addClass('visible').removeClass('visited').prevAll().removeClass('visited').addClass('visited').end().nextAll().removeClass('visible visited');
	}

	ProductBuilder.prototype.createSummary = function() {
		console.log('ProductBuilder.prototype.createSummary = function()');
		var self = this;

		this.steps.each(function(){
			//this function may need to be updated according to your builder steps and summary
			var step = $(this);
			if( $(this).data('selection') == 'colors' ) {
				//create the Color summary
				var colorSelected = $(this).find('.cd-product-customizer').find('.selected'),
					color = colorSelected.children('a').data('color'),
					colorName = colorSelected.data('content'),
					imageSelected = $(this).find('.cd-product-previews').find('.selected img').attr('src');
				
				self.summary.find('.summary-color').find('.color-label').text(colorName).siblings('.color-swatch').attr('data-color', color);
				console.log('imageSelected : ', imageSelected);
				self.summary.find('.product-preview').attr('src', imageSelected);
			} else if( $(this).data('selection') == 'accessories' ) {


				let title = self.summary.find('.brandAndModel');
				let brandName = self.brands.find('.js-brand.selected').find('.name')[0].innerHTML;
				let modelName = self.models.find('.js-option.selected').find('.searchText')[0].innerHTML;
				title.children('h2').remove().end().append($('<h2><li>' + brandName + '</li></br><li>' + modelName + '</li></h2>'));


				var selectedOptions = $(this).find('.js-option.selected'),
					optionsContent = '';

				if( selectedOptions.length == 0 ) {
					optionsContent = '<li><p style="color:#FF0000">Nessuna Riparazione Selezionata</p></li>';
				} else {
					selectedOptions.each(function(){
						optionsContent +='<li><p>'+$(this).find('p').text()+'</p></li>';
					});
				}

				self.summary.find('.summary-accessories').children('li').remove().end().append($(optionsContent));
			}
		});
	}
	
	ProductBuilder.prototype.updateListBrand = function(brand) {
		console.log('ProductBuilder.prototype.updateListBrand = function(brand)');
		var self = this;
		console.log('brand : ', brand);
		var brandType = brand.data('brand'),
			brandId = brand.data('brandid'),
			modelImage = brand.find('img').attr('src');
		this.modelPreview.attr('src', modelImage);

		if( brand.parents('[data-selection="brands"]').length > 0 ) {

			var alreadySelectedOption = brand.siblings('.selected');
			alreadySelectedOption.removeClass('selected');
			brand.toggleClass('selected');
			self.loaded = true;
			brand.addClass('loaded');
			self.fixedSummary.add(self.mainNavigation).removeClass('disabled show-alert');
			// self.updateModelContent(brand);
		}
		this.element.find('[data-selection="models"]').remove();
		this.element.find('[data-selection="colors"]').remove();
		this.element.find('[data-selection="accessories"]').remove();
		this.element.find('[data-selection="summary"]').remove();
		$.ajax({
			type       : "GET",
			dataType   : "html",
			url        : "models.php?brandId=" + brandId,
			beforeSend : function(){
				self.loaded = false;
				brand.siblings().removeClass('loaded');
			},
			success    : function(data){
				if(data) {
					// console.log('this brand models exist!');
					// console.log('data : ', data);
					self.brands.after(data);
					self.loaded = true;
					brand.addClass('loaded');
					//activate top and bottom navigations
					self.fixedSummary.add(self.mainNavigation).removeClass('disabled show-alert');

					self.summary = self.element.find('[data-selection="summary"]');
					//detect click on one element in an options list
					self.optionsLists.off('click', '.js-option');
					self.optionsLists = self.element.find('.options-list');
					self.optionsLists.on('click', '.js-option', function(event){
						self.updateListOptions($(this));
					});

					//this is used not to load the animation the first time new content is loaded
					self.element.find('.first-load').removeClass('first-load');
					self.models = self.element.find('[data-selection="models"]');
				} else {
					console.log('this brand models not exist!');
				}

			},
			error     : function(jqXHR, textStatus, errorThrown) {
				//you may want to show an error message here
			}
		});
	}

	ProductBuilder.prototype.updateListOptions = function(listItem) {
		console.log('ProductBuilder.prototype.updateListOptions = function(listItem)');
		var self = this;
		console.log('listItem : ', listItem);
		
		if( listItem.hasClass('js-radio') ) {
			//this means only one option can be selected (e.g., models) - so check if there's another option selected and deselect it
			var alreadySelectedOption = listItem.siblings('.selected'),
				price = (alreadySelectedOption.length > 0 ) ? -Number(alreadySelectedOption.data('price')) : 0;
			var	dealer = (alreadySelectedOption.length > 0 ) ? -Number(alreadySelectedOption.data('dealer')) : 0;

			//if the option was already selected and you are deselecting it - price is the price of the option just clicked
			( listItem.hasClass('selected') ) 
				? price = -Number(listItem.data('price'))
				: price = Number(listItem.data('price')) + price;

			( listItem.hasClass('selected') ) 
				? dealer = -Number(listItem.data('dealer'))
				: dealer = Number(listItem.data('dealer')) + dealer;
			
			//now deselect all the other options
			alreadySelectedOption.removeClass('selected');
			//toggle the option just selected
			listItem.toggleClass('selected');
			//update totalPrice - only if the step is not the Models step
			// (listItem.parents('[data-selection="models"]').length == 0) && self.updatePrice(price, dealer);
		} else {
			//more than one options can be selected - just need to add/remove the one just clicked
			var price = ( listItem.hasClass('selected') ) ? -Number(listItem.data('price')) : Number(listItem.data('price'));
			var dealer = ( listItem.hasClass('selected') ) ? -Number(listItem.data('dealer')) : Number(listItem.data('dealer'));
			//toggle the option just selected
			listItem.toggleClass('selected');
			//update totalPrice
			self.updatePrice(price, dealer);
		}

		if( listItem.parents('[data-selection="models"]').length > 0 ) {
			//since a model has been selected/deselected, you need to update the builder content
			self.updateModelContent(listItem);
		}
	};

	ProductBuilder.prototype.updateModelContent = function(model) {
		console.log('ProductBuilder.prototype.updateModelContent = function(model)');
		var self = this;
		if( model.hasClass('selected') ) {
			var modelType = model.data('model'),
				modelId = model.data('modelid'),
				modelImage = model.find('img').attr('src');
			console.log('model : ', model);
			//need to update the product image in the bottom fixed navigation
			this.modelPreview.attr('src', modelImage);

			//need to update the content of the builder according to the selected product
			//first - remove the contet which refers to a different model
			// this.models.siblings('li').remove();
			this.element.find('[data-selection="colors"]').remove();
			this.element.find('[data-selection="accessories"]').remove();
			this.element.find('[data-selection="summary"]').remove();

			$.ajax({
		        type       : "GET",
		        dataType   : "html",
				url        : "product.php?modelId=" + modelId,
		        // url        : modelType+".html",
		        beforeSend : function(){
		        	self.loaded = false;
		        	model.siblings().removeClass('loaded');
		        },
		        success    : function(data){
					// console.log('data : ', data);
		        	// self.element.find('[data-selection="models"]').after(data);
		        	self.models.after(data);
		        	self.loaded = true;
		        	model.addClass('loaded');
		        	//activate top and bottom navigations
		        	self.fixedSummary.add(self.mainNavigation).removeClass('disabled show-alert');
		        	//update properties of the object
					self.steps = self.element.find('.builder-step');
					console.log('builder steps : ', self.steps);
					console.log('steps lengths : ', self.steps.length);
					self.summary = self.element.find('[data-selection="summary"]');
					//detect click on one element in an options list
					self.optionsLists.off('click', '.js-option');
					self.optionsLists = self.element.find('.options-list');
					self.optionsLists.on('click', '.js-option', function(event){
						self.updateListOptions($(this));
					});

					//this is used not to load the animation the first time new content is loaded
					self.element.find('.first-load').removeClass('first-load');
		        },
		        error     : function(jqXHR, textStatus, errorThrown) {
		            //you may want to show an error message here
		        }
			});

			//update price (no adding/removing)
			this.totPriceWrapper.text(model.data('price'));
			this.dealerPriceWrapper.text(model.data('dealer'));
		} else {
			//no model has been selected
			this.fixedSummary.add(this.mainNavigation).addClass('disabled');
			//update price
			this.totPriceWrapper.text('0');
			this.dealerPriceWrapper.text('0');

			this.models.find('.loaded').removeClass('loaded');
		}
	};

	ProductBuilder.prototype.customizeModel = function(target) {
		console.log('ProductBuilder.prototype.customizeModel = function(target)');
		var parent = target.parent('li')
			index = parent.index();
		
		//update final price
		var price = ( parent.hasClass('selected') )
			? 0
			: Number(parent.data('price')) - parent.siblings('.selected').data('price');

		var dealer = ( parent.hasClass('selected') )
			? 0
			: Number(parent.data('dealer')) - parent.siblings('.selected').data('dealer');

		this.updatePrice(price, dealer);
		target.parent('li').addClass('selected').siblings().removeClass('selected').parents('.cd-product-customizer').siblings('.cd-product-previews').children('.selected').removeClass('selected').end().children('li').eq(index).addClass('selected');
	};

	ProductBuilder.prototype.updatePrice = function(price, dealer) {
		console.log('ProductBuilder.prototype.updatePrice = function(price)');

		var actualPrice = Number(this.totPriceWrapper.text()) + price;
		var dealerPrice = Number(this.dealerPriceWrapper.text()) + dealer;
		// var dealerPrice = Math.round((actualPrice > 20 ? (actualPrice -20)/1.22  : actualPrice) * 100) / 100;
		this.totPriceWrapper.text(actualPrice);
		this.dealerPriceWrapper.text(Math.round(dealerPrice * 100) / 100 );
	};

	if( $('.cd-product-builder').length > 0 ) {
		console.log("if( $('.cd-product-builder').length > 0 )");
		$('.cd-product-builder').each(function(){
			console.log("$('.cd-product-builder').each(function()");
			//create a productBuilder object for each .cd-product-builder
			new ProductBuilder($(this));
		});
	}

	showHidden = function(event) {
		console.log(event.target.checked);
		var sh = document.getElementsByClassName('shprice');
		sh[0].hidden = event.target.checked;
		console.log(sh);
		console.log(sh[0].hidden);
	}

	searchModels = function(evnet, sort) {
		var searchKey = evnet.target.value.toLowerCase();

		if(sort == "models") {
			let x = document.getElementsByClassName('models-list-search'); 
			for (i = 0; i < x.length; i++) {  
				if (!x[i].getElementsByClassName('searchText')[0].innerHTML.toLowerCase().includes(searchKey)) { 
					x[i].style.display="none"; 
				} else { 
					x[i].style.display="list-item";                  
				} 
			} 
		} else if(sort == "accessories") {
			// console.log('accessories');
			let x = document.getElementsByClassName('accessories-list-search'); 
			for (i = 0; i < x.length; i++) {  
				let key1 = x[i].getElementsByClassName('searchText1')[0].innerHTML.toLowerCase();
				
				if (!key1.includes(searchKey) ) { 
					x[i].style.display="none"; 
				} else { 
					x[i].style.display="list-item";                  
				} 
			} 
		}
	}
});