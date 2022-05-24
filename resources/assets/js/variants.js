function init() {
	addVariant();
}

function addVariant() {

	if (!document.getElementById('btabs-variants')) {
		return;
	}

	let mainSection = document.getElementById('variants-section');
	let variants = JSON.parse(mainSection.dataset.variants);
	let variantsPrice = JSON.parse(mainSection.dataset.variantsprice);
	let variantsMeasure = JSON.parse(mainSection.dataset.variantsmeasure);
	let variantsWeight = JSON.parse(mainSection.dataset.variantsweight);
	let variantsEN= JSON.parse(mainSection.dataset.variantsen);

	const existingVariants = JSON.parse(mainSection.dataset.existingvariants);

	const noVariantsId = document.getElementById('no-variants');

	const variantLabel = mainSection.dataset.variantslabel
	const priceLabel = mainSection.dataset.pricelabel
	const measureLabel = mainSection.dataset.measurelabel
	const weightLabel = mainSection.dataset.weightlabel
	const variantLabelEN = mainSection.dataset.variantslabelen


	const button = document.getElementById('add-variant');
	const deleteButton = document.getElementById('delete-variant');

	let counter = 0;

	if(!variants && existingVariants && existingVariants.length > 0){
		console.log('unutra')
		for(let i = 0; i < existingVariants.length; i++){
			mainSection.insertAdjacentHTML('beforeend', generateHtmlTemplate(
				variantLabel,
				priceLabel,
				measureLabel,
				variantLabelEN,
				weightLabel,
				i,
				existingVariants[i].name,
				existingVariants[i].price,
				existingVariants[i].measure,
				existingVariants[i].weight,
				existingVariants[i].translations[0].value,
				existingVariants[i].id
				))
		}
		counter = existingVariants.length - 1

		if(mainSection.childElementCount > 0){
			noVariantsId.classList.add('d-none')
			deleteButton.classList.remove('d-none')
		}

	}else{

		if(variants && variants.length > 0){

			for(let i = 0; i < variants.length; i++){
				mainSection.insertAdjacentHTML('beforeend', generateHtmlTemplate(variantLabel, priceLabel, measureLabel, variantLabelEN, weightLabel, i, variants[i], variantsPrice[i], variantsMeasure[i], variantsQuantity[i], variantsEN[i]))
			}
			counter = variants.length - 1

			if(mainSection.childElementCount > 0){
				noVariantsId.classList.add('d-none')
				deleteButton.classList.remove('d-none')
			}
		}
		else{
			noVariantsId.classList.remove('d-none')
			deleteButton.classList.add('d-none')
			//mainSection.insertAdjacentHTML('beforeend', generateHtmlTemplate(variantLabel, priceLabel, measureLabel, variantLabelEN))
		}
}

	addOnChangeListenerToMediaInputs();

	button.addEventListener('click', function (event) {
		event.preventDefault();

		counter++;

		mainSection.insertAdjacentHTML('beforeend', generateHtmlTemplate(variantLabel, priceLabel, measureLabel, variantLabelEN, weightLabel, counter))

		if(mainSection.childElementCount > 0){
			noVariantsId.classList.add('d-none')
			deleteButton.classList.remove('d-none')
		}

		addOnChangeListenerToMediaInputs();

	});

	deleteButton.addEventListener('click', function (event) {
		event.preventDefault();

		document.getElementById('variants-section').removeChild(document.getElementById('variants-section').lastElementChild)

		if(mainSection.childElementCount == 0){
			noVariantsId.classList.remove('d-none')
			deleteButton.classList.add('d-none')
		}

		counter--;

	});

}

function createImgElement(address, id){

	const img = document.createElement("img");
	img.classList.add('preview-image');

	img.setAttribute("src", URL.createObjectURL(address));
	img.dataset.id = id

	return img;

}


function generateHtmlTemplate(variantLabel = 'Variant name', priceLabel = 'Price', measureLabel = 'Measure', variantLabelEN = 'Variant name - english', weightLabel = 'Variant weight(kg)', index = 0, variant = '', price = null, measure = null, weight = null, variantEn = '', variantId = null){

	variant = (variant == null) || (variant == 'null') ? '' : variant
	price = (price == null) || (price == 'null') ? null : price
	measure = (measure == null) || (measure == 'null') ? null : measure
	weight = (weight == null) || (weight == 'null') ? null : weight
	variantEn = (variantEn == null) || (variantEn == 'null') ? '' : variantEn


	return  `<div><div class="form-group">
							<label for="">${variantLabel}</label>
							<input name="variants[]" type="text" value="${variant}" maxlength="100" required class="form-control">
							<span class="form-text text-muted">This will be appended to the product main name</span>
						</div>
						<input type="hidden" value="${variantId}" name="variant_ids[]" />
						<div class="form-group">
							<label for="">${priceLabel}</label>
							<input name="variants_price[]" value="${price}" type="number" min="0" required class="form-control">
						</div>
						<div class="form-group">
							<label for="">${measureLabel}</label>
							<input name="variants_measure[]" value="${measure}" step="any" type="number" min="0" required class="form-control">
						</div>
						<div class="form-group">
						<label for="">${weightLabel}</label>
						<input name="variants_weight[]" value="${weight}" step="any" type="number" min="0" required class="form-control">
					</div>
						<div class="form-group variants-border">
							<label for="">${variantLabelEN}</label>
							<input name="variants_en[]" type="text" value="${variantEn}" maxlength="100" required class="form-control">
							<span class="form-text text-muted">This will be appended to the product main name</span>
						</div></div>`

}

function addOnChangeListenerToMediaInputs(){

	let mediaInputs = document.getElementsByClassName('input-file');

	for(let i = 0; i < mediaInputs.length; i++){

		mediaInputs[i].addEventListener('change', function (event) {

			const targetElement = event.target

			clearChosenMedia(targetElement.name)

			const files = targetElement.files

			if (files.length > 0) {
				files.forEach(item => {
					targetElement.parentElement.append(createImgElement(item, targetElement.name))
				})
			}

		});
	}
}

function clearChosenMedia(id){

	const matches = document.querySelectorAll(`img[data-id='${id}']`);

	matches.forEach(item => {
		item.remove()
	})
}


export { init };
