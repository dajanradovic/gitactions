import { ApiHandler } from '../api';
import { blockModal, fromNowTimestamp, localTimestamp, unblockModal } from '../main';
import { adjustProgress, clearModalListContainer, initModalSearch, setUserAvatar } from './utils';

function init() {
	$('#activity-modal').on('shown.bs.modal', e => {
		const data = JSON.parse(e.relatedTarget.dataset.modalData);
		e.target.querySelector('.modal-title').innerHTML = data.title;

		initModalSearch(e.target, data.api, data.token, getModelActivity);

		getModelActivity(e.target, data.api, data.token);
	});

	$('#activity-modal').on('hidden.bs.modal', e => {
		clearModalListContainer(e.target);
	});
}

async function getModelActivity(modalElem, url, token) {
	if (!url) {
		return;
	}

	blockModal(modalElem.id);

	const api = new ApiHandler;
	const data = await api.setToken(token).get(url);

	adjustProgress(modalElem, data.meta);

	modalElem.querySelector('.modal-load-more').onclick = () => getModelActivity(modalElem, data.links.next, token);

	const container = modalElem.querySelector('.modal-list-container');
	const template = modalElem.querySelector('.modal-list-template').content;

	for (const row of data.data) {
		let clone = template.cloneNode(true);

		const link = clone.querySelector('.modal-user-link');
		const time = clone.querySelector('.modal-time');
		const type = clone.querySelector('.item-type');

		time.innerHTML = fromNowTimestamp(row.created_at);
		time.title = localTimestamp(row.created_at);

		link.href = 'mailto:' + row.user.email;
		link.innerHTML = row.user.name;

		type.href = row.item.url ?? '#';
		type.innerHTML = row.item.title ?? row.item.type;
		type.title = row.item.type;

		clone.querySelector('.ip-address').innerHTML = row.ip_address ?? '-';

		if (row.type) {
			clone.querySelector('.modal-activity-badge-' + row.type).hidden = false;
		}

		clone = setUpdatedFieldsContent(clone, row.updated_fields);

		container.appendChild(setUserAvatar(clone, row.user));
	}

	unblockModal(modalElem.id);
}

function setUpdatedFieldsContent(clone, updatedFields) {
	if (!updatedFields || !updatedFields.length) {
		return clone;
	}

	for (let i = 0; i < updatedFields.length; i++) {
		updatedFields[i] = updatedFields[i].replace(/_+/gu, ' ').trim();
	}

	const modalText = clone.querySelector('.modal-text');
	modalText.innerHTML = '<ul><li>' + updatedFields.join('</li><li>') + '</li></ul>';
	modalText.hidden = false;

	return clone;
}

export { init };
