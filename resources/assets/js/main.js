import { ApiHandler } from './api';

function init() {
	initLogoutActions();
	initMoment();
	initTrimInputs();
	initDatatables();
	initSelect();
	initDualListBox();
	initDatePickers();
	initDatePickersNoMin();
	initDateTimePickers();
	initDateTimeRangePickers();
	initSearchDateTimeRangePickers();
	initSummernote();
	initMaxlength();
	initTagify();
	initClipboard();
	addFormHandlers();
	initGatherFormInputs();
	initCheckboxToggle();
	initNestedCheckboxToggle();
	initSafeFileName();
	initGenerateSlug();
	initMediaGalleries();
	initTabNavigation();
	initDisplayErrors();
	displayErrors();
	displayRequestSuccessful();
}

function initLogoutActions(selector = '.logout-action') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onclick = () => logout();
	}
}

function logout() {
	document.getElementById('logout-form').submit();
}

function initMoment(lang = null) {
	return moment.locale(lang ?? document.documentElement.lang ?? 'en');
}

function initTrimInputs(selector = 'textarea, input[type="text"], input[type="email"], input[type="tel"], input[type="url"]') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onblur = () => {
			elem.value = elem.value.trim();
		};
	}
}

function confirmAlert(data = {}) {
	return swal.fire({
		title: data.title ?? 'Are you sure?',
		text: data.text ?? null,
		icon: 'warning',
		showCancelButton: true,
		showCloseButton: true,
		buttonsStyling: false,
		confirmButtonText: '<i class="la la-check"></i> Ok',
		cancelButtonText: '<i class="la la-close"></i> Cancel',
		customClass: {
			confirmButton: 'btn btn-primary',
			cancelButton: 'btn btn-secondary'
		}
	});
}

function successAlert(data = {}) {
	return swal.fire({
		title: data.title ?? 'Success!',
		text: data.text ?? null,
		icon: 'success',
		timer: 3000,
		showCloseButton: true,
		buttonsStyling: false,
		confirmButtonText: '<i class="la la-check"></i> Ok',
		customClass: {
			confirmButton: 'btn btn-primary'
		}
	});
}

function errorAlert(data = {}) {
	return swal.fire({
		title: data.title ?? 'Error!',
		text: data.text ?? null,
		icon: 'error',
		showCloseButton: true,
		buttonsStyling: false,
		confirmButtonText: '<i class="la la-check"></i> Ok',
		customClass: {
			confirmButton: 'btn btn-primary'
		}
	});
}

function debounced(callback, delay = 300) {
	let timerId;

	return (...args) => {
		if (timerId) {
			clearTimeout(timerId);
		}

		timerId = setTimeout(() => callback.apply(this, args), delay);
	};
}

function initMaxlength(selector = 'input[maxlength], textarea[maxlength]') {
	return !$.fn.maxlength ? null : $(selector).maxlength({
		alwaysShow: true,
		placement: 'bottom',
		warningClass: 'label label-primary label-rounded label-inline',
		limitReachedClass: 'label label-danger label-rounded label-inline'
	});
}

function initSelect(selector = 'select.kt-bootstrap-select') {
	return !$.fn.selectpicker ? null : $(selector).selectpicker({
		showTick: true,
		actionsBox: true,
		showSubtext: true,
		liveSearchNormalize: true
	});
}

function initDualListBox(selector = 'select.dual-listbox[multiple]') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		new DualListbox(elem, {
			availableTitle: elem.dataset.availableTitle ?? '',
			selectedTitle: elem.dataset.selectedTitle ?? '',
			searchPlaceholder: elem.dataset.searchPlaceholder ?? '',
			addButtonText: elem.dataset.addButton ?? '<i class="flaticon2-next"></i>',
			removeButtonText: elem.dataset.removeButton ?? '<i class="flaticon2-back"></i>',
			addAllButtonText: elem.dataset.addAllButton ?? '<i class="flaticon2-fast-next"></i>',
			removeAllButtonText: elem.dataset.removeAllButton ?? '<i class="flaticon2-fast-back"></i>'
		});
	}
}

function initClipboard(selector = '[data-clipboard=true]') {
	return new ClipboardJS(selector)
		.on('success', () => fireNotification({
			message: 'Copied to clipboard',
			icon: 'la la-copy',
			type: 'success',
			delay: 3
		}))
		.on('error', () => fireNotification({
			message: 'Error copying to clipboard',
			icon: 'la la-warning',
			type: 'danger',
			delay: 3
		}));
}

function initTabNavigation(selector = 'ul.nav[data-active-tab]:not([data-active-tab=""])') {
	const elems = document.querySelectorAll(selector);

	if (!elems.length) {
		return;
	}

	const params = new URLSearchParams(location.search);

	for (const elem of elems) {
		const activeTab = elem.querySelector('li a.nav-link[href="#' + (params.get(elem.dataset.activeTab) ?? '') + '"]:not(.active)');

		if (activeTab) {
			activeTab.click();
		}
	}
}

function initTagify(selector = '.tagify') {
	const api = new ApiHandler;
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		const delimiter = elem.dataset.delimiter ?? ' ';
		const state = elem.dataset.state ?? 'primary';

		const tagify = new Tagify(elem, {
			delimiters: delimiter,
			pattern: /^.{1,50}$/u,
			originalInputValueFormat: items => items.map(item => item.value).join(delimiter),
			transformTag: e => {
				e.class = 'tagify__tag tagify__tag--' + state;
			}
		});

		tagify
			.on('add edit:updated remove', () => {
				elem.value = tagify.value.map(item => item.value).join(delimiter);
			})
			.on('input', debounced(async e => {
				tagify.settings.whitelist = [];
				const value = e.detail.value.trim();

				if (!elem.dataset.api || value.length < 3) {
					return;
				}

				const data = await api
					.withAbort()
					.setToken(elem.dataset.token)
					.get(elem.dataset.api + '?search=' + value);

				tagify.settings.whitelist = data.data.map(item => item.name);
				tagify.dropdown.show.call(tagify, value);
			}));
	}
}

function initSummernote(selector = '.summernote') {
	if (!$.fn.summernote) {
		return;
	}

	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		$(elem).summernote({
			dialogsFade: true,
			dialogsInBody: true,
			height: elem.dataset.height ?? 300,
			fontNames: [],
			maximumImageFileSize: 1024 * 1024,
			tableClassName: 'table table-hover table-bordered',
			toolbar: [
				['actions', ['undo', 'redo']],
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph', 'hr']],
				['table', ['table']],
				['insert', ['link', 'picture', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			],
			callbacks: {
				onChange(contents) {
					if (elem.dataset.textarea) {
						document.getElementById(elem.dataset.textarea).value = $(elem).summernote('isEmpty') ? '' : contents.trim();
					}
				}
			}
		});
	}
}

function initDatatables(selector = 'table.js-datatable') {
	if (!$.fn.DataTable) {
		return;
	}

	const exportOptions = {
		columns: 'th:visible:not([data-datatable-exclude-export])'
	};

	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		const columnDefs = [];
		const thElems = elem.querySelectorAll('thead tr th');

		for (let i = 0; i < thElems.length; i++) {
			columnDefs[columnDefs.length] = {
				targets: i,
				type: thElems[i].dataset.datatableType ?? 'html',
				searchable: (thElems[i].dataset.datatableSearchable ?? 1) === 1,
				orderable: (thElems[i].dataset.datatableOrderable ?? 1) === 1
			};
		}

		const exportTitle = elem.dataset.exportTitle ?? '*';
		const exportFilename = elem.dataset.exportFilename ?? exportTitle;
		const exportMessageTop = elem.dataset.exportMessageTop ?? '*';
		const exportMessageBottom = elem.dataset.exportMessageBottom ?? '*';

		$(elem).DataTable({
			order: [],
			responsive: !elem.classList.contains('table-responsive'),
			processing: true,
			colReorder: true,
			pagingType: 'full_numbers',
			pageLength: 50,
			lengthMenu: [
				[10, 50, 100, -1],
				[10, 50, 100, 'All']
			],
			columnDefs,
			dom:
				'<\'row\'<\'col-sm-6 text-left\'f><\'col-sm-6 text-right\'B>>\n\t\t\t<\'row\'<\'col-sm-12\'tr>>\n\t\t\t<\'row\'<\'col-sm-12 col-md-5\'i><\'col-sm-12 col-md-7 dataTables_pager\'lp>>',
			buttons: [
				{
					extend: 'collection',
					text: 'Export',
					autoClose: true,
					className: 'btn-light',
					buttons: [
						{
							extend: 'copyHtml5',
							exportOptions
						},
						{
							extend: 'print',
							exportOptions
						},
						{
							extend: 'csvHtml5',
							filename: exportFilename,
							exportOptions
						},
						{
							extend: 'excelHtml5',
							filename: exportFilename,
							title: exportTitle,
							messageTop: exportMessageTop,
							messageBottom: exportMessageBottom,
							exportOptions
						},
						{
							extend: 'pdfHtml5',
							orientation: 'landscape',
							filename: exportFilename,
							title: exportTitle,
							messageTop: exportMessageTop,
							messageBottom: exportMessageBottom,
							exportOptions
						},
						{
							text: 'JSON',
							action: (e, dt) => {
								$.fn.dataTable.fileSave(new Blob([JSON.stringify(dt.buttons.exportData(exportOptions))], {
									type: 'application/json'
								}), (exportFilename !== '*' ? exportFilename : document.title) + '.json');
							}
						}
					]
				},
				{
					extend: 'collection',
					text: 'Visibility',
					className: 'btn-light',
					buttons: ['colvisRestore', 'colvis']
				}
			]
		});
	}
}

function initSafeFileName(selector = 'input[type=text][data-safe-filename]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.onblur = () => safeFileName(input, input.dataset.safeFilename, input.dataset.safeFilenameGlue ?? '-');
	}
}

function safeFileName(elem, glue = '-') {
	const regex = new RegExp('[\\s' + glue + ']+', 'gu');

	elem.value = elem.value.trim().replace(regex, glue);
}

function initGenerateSlug(selector = 'input[type=text][data-slug-target-id]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input[input.dataset.slugTargetEvent ?? 'oninput'] = () => generateSlug(input.value, input.dataset.slugTargetId, input.dataset.slugTargetGlue ?? '-');
	}
}

function generateSlug(value, elemId, glue = '-') {
	const regex = new RegExp('[\\s' + glue + ']+', 'gu');

	document.getElementById(elemId).value = value.trim().toLowerCase().replace(regex, glue);
}

function initMediaGalleries(selector = 'input[type=file][data-media-gallery-id]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.onchange = () => showMedia(input, input.dataset.mediaGalleryId);
	}
}

function showMedia(elem, galleryDiv) {
	const template = document.getElementById('gallery-template-' + galleryDiv).content;
	galleryDiv = document.getElementById(galleryDiv);
	galleryDiv.innerHTML = '';

	for (const file of elem.files) {
		const clone = template.cloneNode(true);

		let galleryItem;
		const path = window.URL.createObjectURL(file);
		const type = file.type;

		if (type.includes('video')) {
			galleryItem = clone.querySelector('.gallery-type-video');
			galleryItem.src = path;
		} else if (type.includes('audio')) {
			galleryItem = clone.querySelector('.gallery-type-audio');
			galleryItem.src = path;
		} else {
			galleryItem = clone.querySelector('.gallery-type-file');
			galleryItem.href = path;

			if (type.includes('image')) {
				galleryItem.querySelector('img').src = path;
			}
		}

		galleryItem.hidden = false;

		const fileName = file.name.trim();
		let size = file.size / (1024 * 1024);
		size = parseFloat(size.toFixed(2));

		clone.querySelector('.gallery-footer').innerHTML = fileName + ' (' + size + ' MB)';

		galleryDiv.appendChild(clone);
	}
}

function addFormHandlers(className = 'form-notify') {
	const elems = document.querySelectorAll('form.' + className);

	for (const elem of elems) {
		elem.onreset = () => {
			$(elem.querySelectorAll('.summernote')).summernote('reset');

			const galleries = elem.querySelectorAll('.gallery-container');

			for (const gallery of galleries) {
				gallery.innerHTML = '';
			}
		};

		elem.onsubmit = () => {
			const buttons = document.querySelectorAll('button[type="submit"][form="' + elem.id + '"]');
			blockSection(buttons, 'light');

			fireNotification({
				message: 'Saving data in progress...',
				icon: 'la la-spinner fa-spin',
				type: 'success',
				delay: 0
			});
		};

		elem.addEventListener('invalid', e => {
			const inputElem = e.target;

			if (!inputElem?.name) {
				return;
			}

			inputElem.classList.add('is-invalid');

			fireNotification({
				message: 'Field "' + inputElem.name.replace(/_+/gu, ' ').trim() + '" is invalid.',
				icon: 'la la-warning',
				type: 'danger',
				delay: 5
			});

			let tabDiv = inputElem;

			while (tabDiv = tabDiv.closest('div.tab-pane')) {
				elem?.querySelector('ul.nav li.nav-item a.nav-link[href="#' + tabDiv.id + '"] .nav-text')
					?.classList.add('font-weight-bold', 'text-danger');

				tabDiv = tabDiv.closest('div.tab-content');
			}
		}, true);
	}
}

function blockPage(state = 'primary') {
	return KTApp.blockPage({
		size: 'lg',
		state
	});
}

function unblockPage() {
	return KTApp.unblockPage();
}

function blockSection(selector, state = 'primary') {
	return KTApp.block(selector, {
		size: 'lg',
		state
	});
}

function unblockSection(selector) {
	return KTApp.unblock(selector);
}

function blockModal(modal) {
	return blockSection('#' + modal + ' .modal-content');
}

function unblockModal(modal) {
	return unblockSection('#' + modal + ' .modal-content');
}

function initDatePickers(selector = '.js-datepicker') {
	return !$.fn.datepicker ? null : $(selector).datepicker({
		showWeekDays: true,
		calendarWeeks: true,
		todayBtn: true,
		clearBtn: true,
		startDate: new Date,
		format: 'dd-mm-yyyy',
		weekStart: 1
	});
}

function initDatePickersNoMin(selector = '.js-datepicker-no-min') {
	return !$.fn.datepicker ? null : $(selector).datepicker({
		showWeekDays: true,
		calendarWeeks: true,
		todayBtn: true,
		clearBtn: true,
		format: 'dd-mm-yyyy',
		weekStart: 1
	});
}

function initDateTimePickers(selector = '.js-datetimepicker') {
	return !$.fn.datetimepicker ? null : $(selector).datetimepicker({
		calendarWeeks: true,
		useCurrent: false,
		ignoreReadonly: true,
		minDate: new Date,
		format: 'YYYY-MM-DD HH:mm',
		buttons: {
			showToday: true,
			showClear: true,
			showClose: true
		}
	});
}

function initDateTimeRangePickers(selector = '.js-datetimerangepicker') {
	return !$.fn.daterangepicker ? null : $(selector).daterangepicker({
		buttonClasses: 'btn',
		applyClass: 'btn-primary',
		cancelClass: 'btn-secondary',
		timePicker: true,
		timePicker24Hour: true,
		showWeekNumbers: true,
		autoUpdateInput: true,
		minDate: new Date,
		locale: { format: 'DD/MM/YYYY HH:mm' }
	}, function(start, end) {
		const id = $(this).attr('element').attr('id');

		document.getElementById(id + '-first').value = start.utc().format('YYYY-MM-DD HH:mm');
		document.getElementById(id + '-second').value = end.utc().format('YYYY-MM-DD HH:mm');
	});
}

function initSearchDateTimeRangePickers(selector = '.js-search-datetimerangepicker') {
	return !$.fn.daterangepicker ? null : $(selector).daterangepicker({
		buttonClasses: 'btn',
		applyClass: 'btn-primary',
		cancelClass: 'btn-secondary',
		timePicker: true,
		timePicker24Hour: true,
		showWeekNumbers: true,
		autoUpdateInput: true,
		locale: { format: 'DD/MM/YYYY HH:mm' }
	}, function(start, end) {
		const id = $(this).attr('element').attr('id');

		document.getElementById(id + '-first').value = start.utc().format('YYYY-MM-DD HH:mm');
		document.getElementById(id + '-second').value = end.utc().format('YYYY-MM-DD HH:mm');

		document.getElementById(id).form.submit();
	});
}

function fireNotification(data) {
	return $.notify({
		// options
		message: data.message,
		icon: 'icon ' + data.icon
	}, {
		// settings
		type: data.type,
		delay: data.delay * 1000,
		animate: {
			enter: 'animate__animated animate__fadeInDown',
			exit: 'animate__animated animate__fadeOutUp'
		}
	});
}

function utcToLocal(timestamp = null) {
	return moment.utc(timestamp).local();
}

function localTimestamp(timestamp = null, format = 'DD/MM/YYYY HH:mm') {
	return utcToLocal(timestamp).format(format);
}

function fromNowTimestamp(timestamp = null) {
	return utcToLocal(timestamp).fromNow();
}

function initCheckboxToggle(selector = 'input[type=checkbox][data-toggle-class]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.onclick = () => toggleCheckboxes(input.checked, input.dataset.toggleClass);
	}
}

function toggleCheckboxes(state, className) {
	const inputs = document.querySelectorAll('input.' + className);

	for (const input of inputs) {
		input.checked = state;
	}
}

function initNestedCheckboxToggle(selector = 'input[type=checkbox][data-toggle-nested-id]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.onclick = () => toggleNestedCheckboxes(input.checked, input.dataset.toggleNestedId);
	}
}

function toggleNestedCheckboxes(state, containerId) {
	const inputs = document.getElementById(containerId).querySelectorAll('input[type=checkbox]');

	for (const input of inputs) {
		input.checked = state;
	}
}

function initGatherFormInputs(selector = 'button[data-action-buttons-form]') {
	const buttons = document.querySelectorAll(selector);

	for (const button of buttons) {
		button.onclick = () => gatherFormInputs(button.form);
	}
}

function gatherFormInputs(formElem) {
	return confirmAlert({ text: formElem.dataset.submitMessage ?? null }).then(e => {
		if (!e.value) {
			return;
		}

		fireNotification({
			message: 'Saving data in progress...',
			icon: 'la la-spinner fa-spin',
			type: 'success',
			delay: 0
		});

		assignInputsToForm(formElem.id);

		formElem.submit();
	});
}

function assignInputsToForm(formElemId = '', selector = 'input.options-form:checked') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.setAttribute('form', formElemId);
	}
}

function initDisplayErrors(selector = '.display-errors') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onclick = () => displayErrors();
	}
}

function displayErrors(elemId = 'error-messages') {
	if (!document.getElementById(elemId)) {
		return;
	}

	const errors = JSON.parse(document.getElementById(elemId).innerHTML.trim());

	for (const error of errors) {
		fireNotification({
			message: error,
			icon: 'la la-warning',
			type: 'danger',
			delay: 5
		});
	}
}

function displayRequestSuccessful(elemId = 'request-successful') {
	const elem = document.getElementById(elemId);

	if (!elem || elem.innerHTML === '') {
		return;
	}

	fireNotification({
		message: elem.innerHTML,
		icon: 'la la-check',
		type: 'success',
		delay: 3
	});
}

export {
	init,
	logout,
	initMoment,
	confirmAlert,
	successAlert,
	errorAlert,
	blockModal,
	unblockModal,
	blockSection,
	blockPage,
	unblockPage,
	unblockSection,
	utcToLocal,
	fromNowTimestamp,
	localTimestamp,
	debounced,
	initClipboard,
	initDatatables,
	initDatePickers,
	initDatePickersNoMin,
	initDateTimePickers,
	initDateTimeRangePickers,
	initSearchDateTimeRangePickers,
	initMaxlength,
	initSelect,
	initDualListBox,
	initSummernote,
	initTagify,
	fireNotification,
	initCheckboxToggle,
	initNestedCheckboxToggle,
	assignInputsToForm
};
