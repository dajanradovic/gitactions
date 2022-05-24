import { BaseHandler } from './BaseHandler';

class ApiHandler extends BaseHandler {
	get(url) {
		return this.#request('GET', url);
	}

	post(url, data = null) {
		return this.#request('POST', url, data);
	}

	put(url, data = null) {
		return this.#request('PUT', url, data);
	}

	patch(url, data = null) {
		return this.#request('PATCH', url, data);
	}

	delete(url) {
		return this.#request('DELETE', url);
	}

	#request(method, url, data = null) {
		const headers = new Headers;

		headers.set('Accept', 'application/json');

		if (data) {
			headers.set('Content-Type', 'application/json; charset=utf-8');
		}

		const token = this.getToken();

		if (token) {
			headers.set('Authorization', 'Bearer ' + token);
		}

		return this.fetch(url, {
			method,
			headers,
			signal: this.abortController ? this.abortController.signal : null,
			body: data ? JSON.stringify(data) : null
		});
	}
}

export { ApiHandler };
