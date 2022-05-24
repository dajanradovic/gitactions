import { ApiHandler } from './api';
import { blockSection, unblockSection } from './main';

function init() {
	initChangeContentType();
	fetchCountries();
}

function initChangeContentType(selector = '.notifications-content-type') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onchange = () => changeContentType(elem.value);

		if (elem.checked) {
			elem.onchange();
		}
	}
}

function changeContentType(input) {
	const inputs = ['content-url', 'content-file'];

	if (input !== '') {
		inputs.splice(inputs.indexOf(input), 1);
	}

	for (let elem of inputs) {
		elem = document.getElementById(elem);

		if (!elem) {
			continue;
		}

		elem.disabled = true;

		elem = elem.closest('div.form-group');
		elem.hidden = true;
	}

	if (input === '') {
		return;
	}

	input = document.getElementById(input);
	input.disabled = false;

	const parentElem = input.closest('div.form-group');
	parentElem.hidden = false;
}

async function fetchCountries() {
	const elem = document.getElementById('notifications-countries');

	if (!elem) {
		return;
	}

	const parentElem = elem.closest('div.form-group');

	blockSection(parentElem);

	elem.innerHTML = '';
	const selectedData = elem.dataset.selected ? elem.dataset.selected.split(',') : [];

	const api = new ApiHandler;
	const data = await api.setToken(elem.dataset.token).get(elem.dataset.api + '?fields[]=cca2&fields[]=name&fields[]=subregion');

	for (const country of data.data) {
		const option = document.createElement('option');

		option.value = country.cca2;
		option.innerHTML = country.name.official;
		option.selected = selectedData.includes(country.cca2);
		option.dataset.subtext = country.subregion;

		elem.appendChild(option);
	}

	$(elem).selectpicker('refresh');
	unblockSection(parentElem);
}

export { init };
