import { blockPage, errorAlert, init as mainInit, unblockPage } from './main';
import { init as chartsInit } from './charts';
import { init as fileInit } from './file';
import { init as notificationsInit } from './notifications';
import { init as modalsInit } from './modals';
import { initMaps, init as mapsInit } from './map';
import { init as productFilters } from './productFilters';
import { init as vatRates } from './vat-rates';
import { init as variantsInit } from './variants';
import { init as payPalInit } from './paypal';

window.onload = () => {
	blockPage();

	try {
		mainInit();
		chartsInit();
		fileInit();
		notificationsInit();
		modalsInit();
		mapsInit();
		productFilters();
		vatRates();
		variantsInit();
		payPalInit();
	} catch (e) {
		errorAlert({
			title: e.name,
			text: e.message
		});
	}

	unblockPage();
};

window.onbeforeunload = () => blockPage();

window.initMaps = () => initMaps();

window.KTAppSettings = {
	breakpoints: {
		sm: 576,
		md: 768,
		lg: 992,
		xl: 1200,
		xxl: 1400
	},
	colors: {
		theme: {
			base: {
				white: '#ffffff',
				primary: '#3699FF',
				secondary: '#E5EAEE',
				success: '#1BC5BD',
				info: '#8950FC',
				warning: '#FFA800',
				danger: '#F64E60',
				light: '#E4E6EF',
				dark: '#181C32'
			},
			light: {
				white: '#ffffff',
				primary: '#E1F0FF',
				secondary: '#EBEDF3',
				success: '#C9F7F5',
				info: '#EEE5FF',
				warning: '#FFF4DE',
				danger: '#FFE2E5',
				light: '#F3F6F9',
				dark: '#D6D6E0'
			},
			inverse: {
				white: '#ffffff',
				primary: '#ffffff',
				secondary: '#3F4254',
				success: '#ffffff',
				info: '#ffffff',
				warning: '#ffffff',
				danger: '#ffffff',
				light: '#464E5F',
				dark: '#ffffff'
			}
		},
		gray: {
			'gray-100': '#F3F6F9',
			'gray-200': '#EBEDF3',
			'gray-300': '#E4E6EF',
			'gray-400': '#D1D3E0',
			'gray-500': '#B5B5C3',
			'gray-600': '#7E8299',
			'gray-700': '#5E6278',
			'gray-800': '#3F4254',
			'gray-900': '#181C32'
		}
	},
	'font-family': 'Poppins'
};
