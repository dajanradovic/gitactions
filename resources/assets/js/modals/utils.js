import { debounced } from '../main';

function adjustProgress(modalElem, meta = {}) {
	meta.to = meta.to || 0;
	meta.total = meta.total || 0;

	const progressElem = modalElem.querySelector('.progress-bar');
	const progress = meta.total ? Math.floor(100 * meta.to / meta.total) : 0;

	progressElem.innerHTML = progress + '% (' + meta.to + ' / ' + meta.total + ')';
	progressElem.style.width = progress + '%';
}

function setUserAvatar(clone, user = null) {
	if (!user) {
		user = { name: '-' };
	}

	if (user.avatar) {
		const userAvatar = clone.querySelector('.modal-user-avatar');
		userAvatar.src = user.avatar;
		userAvatar.hidden = false;
	} else {
		const userLetterWrapper = clone.querySelector('.modal-user-letter-wrapper');
		userLetterWrapper.querySelector('.modal-user-letter').innerHTML = user.name[0].toUpperCase();
		userLetterWrapper.hidden = false;
	}

	return clone;
}

function initModalSearch(modalElem, url, token, callback) {
	const searchInput = modalElem.querySelector('.modal-search-input');

	if (!searchInput) {
		return;
	}

	searchInput.oninput = debounced(() => {
		clearModalListContainer(modalElem, false);

		return callback(modalElem, setSearchString(url, searchInput.value), token);
	});
}

function clearModalListContainer(modalElem, clearSearchInput = true) {
	const container = modalElem.querySelector('.modal-list-container');
	container.innerHTML = '';
	container.scrollTop = 0;

	adjustProgress(modalElem);

	const searchInput = modalElem.querySelector('.modal-search-input');

	if (searchInput && clearSearchInput) {
		searchInput.value = '';
	}
}

function setSearchString(url, search) {
	search = search.trim();

	return url.includes('?') ? url + '&search=' + search : url + '?search=' + search;
}

function focusInputElemAfterLoad(modalElem, selector) {
	$(modalElem).on('shown.bs.modal', e => {
		e.target.querySelector(selector).focus();
	});
}

export { adjustProgress, setUserAvatar, initModalSearch, clearModalListContainer, focusInputElemAfterLoad };
