import { defineConfig } from 'vite';
import laravel from 'vite-plugin-laravel';

export default defineConfig({
	build: {
		target: 'esnext'
	},
	plugins: [
		laravel()
	]
});
