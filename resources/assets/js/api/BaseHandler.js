import { errorAlert } from '../main';

class BaseHandler {
	#token;

	#abortController = null;

	constructor(token = null) {
		this.#token = token;
	}

	getToken() {
		return this.#token;
	}

	setToken(token = null) {
		this.#token = token;

		return this;
	}

	withAbort(enable = true) {
		if (!enable) {
			this.#abortController = null;
		} else if (!this.#abortController) {
			this.#abortController = new AbortController;
		}

		return this;
	}

	async fetch(url, options = {}) {
		try {
			const data = await fetch(url, options);

			return data.json();
		} catch (error) {
			return errorAlert({ text: error.message });
		}
	}
}

export { BaseHandler };
