import { BaseHandler } from './BaseHandler';

class FormHandler extends BaseHandler {
	submit(form) {
		const headers = new Headers;

		headers.set('Accept', 'application/json');

		const token = this.getToken();

		if (token) {
			headers.set('Authorization', 'Bearer ' + token);
		}

		return this.fetch(form.action, {
			method: form.method,
			headers,
			body: new FormData(form)
		});
	}
}

export { FormHandler };
