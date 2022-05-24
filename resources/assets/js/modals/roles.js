import { assignInputsToForm } from '../main';

function init() {
	$('#roles-modal').on('shown.bs.modal', e => {
		assignInputsToForm(e.target.querySelector('form').id);
	});

	$('#roles-modal').on('hidden.bs.modal', () => {
		assignInputsToForm();
	});
}

export { init };
