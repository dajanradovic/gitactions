import { ApiHandler } from './api';
import { blockSection, unblockSection } from './main';

function init() {
	generateFilterFields();
}

function generateFilterFields(selector = 'select[data-generate-filters]') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onchange = () => generateTemplateFields(elem);
		elem.onchange();
	}
}

async function generateTemplateFields(elem) {

	const container = document.getElementById('filters-content');
	container.innerHTML = '';

	if (!elem.value || elem.value == '') return;

	const parent = elem.closest('div.form-group');

	blockSection(parent);

	const template = document.getElementById('filter-list-template').content;

	const api = new ApiHandler();
	const data = await api.setToken(elem.dataset.token).get(elem.dataset.filtersApi + '?category_id=' + elem.value);

	for (const row of data.data.filters) {
		const clone = template.cloneNode(true);

		const label = clone.querySelector('label');
		const message = clone.querySelector('span.form-text');
		let input = clone.querySelector('input');

		label.innerHTML = row.name;
		label.htmlFor = row.id;
		message.innerHTML = row.message ?? '';

		switch (row.type) {
			case 'textarea': {
				const textarea = document.createElement(row.type);
				textarea.className = input.className;
				textarea.rows = 5;
				input.parentNode.replaceChild(textarea, input);
				input = textarea;

				break;
			}
			case 'select': {
				const select = document.createElement(row.type);
				select.className = input.className;

				const values = row.value ?? [];

				for (const [index, value] of values) {

					const option = document.createElement('option');
					option.value = option.innerHTML = value + '/' + row.value[index];
					select.options.add(option);
				}

				input.parentNode.replaceChild(select, input);
				input = select;

				break;
			}
			default: {
				input.type = row.type;
			}
		}

		input.id = row.id;
		input.name = 'filters[' + row.id + ']';
		input.required = row.required;

		if (row.min != null) input.min = input.minLength = row.min;
		if (row.max != null) input.max = input.maxLength = row.max;
		if (row.step != null) input.step = row.step;
		if (row.value != null) input.value = row.value;

		container.appendChild(clone);
	}

	getFilterValues(elem);

	unblockSection(parent);
}

async function getFilterValues(elem) {
	const productId = elem.dataset.productId;
	const categoryId = elem.dataset.categoryId;

	if (!productId || productId == '' || !categoryId || categoryId == '' || categoryId != elem.value) return;

	const api = new ApiHandler();
	const data = await api.setToken(elem.dataset.token).get(elem.dataset.filterValuesApi + '?product_id=' + productId);

	for (const row of data.data) {
		const item = document.getElementById(row.filter_category.id);
		if (!item) continue;

		if (item.tagName.toLowerCase() != 'select') {
			item.value = row.value ?? '';
			continue;
		}

		const option = item.querySelector('option[value="' + (row.value ?? '') + '"]');
		if (option) option.selected = true;
	}
}

export { init };
