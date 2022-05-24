import { ApiHandler } from './api';
import { blockSection, unblockSection } from './main';

function init() {
	initChartDatePicker();
	initChartDateFormat();
	initCharts();
	initStorageCharts();
	initResizeCharts();
}

function initCharts(selector = '.update-charts') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onclick = () => updateCharts();
	}
}

function initStorageCharts(selector = '.update-storage-charts') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onclick = () => {
			initStorageChart('storage-chart-files');
			initStorageChart('storage-chart-size');
		};
	}
}

function initResizeCharts(selector = 'a[data-chart-resize]') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onclick = () => resizeChart(elem.dataset.chartResize);
	}
}

function resizeChart(chartId) {
	chartId = document.getElementById(chartId);
	chartId.classList.toggle('col-sm-6');
	chartId.classList.toggle('col-sm-12');
}

function updateCharts(selector = '.chart-area') {
	const elems = document.querySelectorAll(selector);

	if (!elems.length) {
		return;
	}

	blockSection(selector);

	const formData = {
		min_date: document.getElementById('chart-date-range-first').value,
		max_date: document.getElementById('chart-date-range-second').value,
		date_format: document.getElementById('chart-date-format').value
	};

	const api = new ApiHandler;

	for (const elem of elems) {
		api.post(elem.dataset.api, formData).then(data => plotLineChart(data, {
			area_id: elem.id,
			title: elem.dataset.title
		}));
	}
}

function initChartDatePicker(selector = '.chart-daterangepicker') {
	return !$.fn.daterangepicker ? null : $(selector).daterangepicker({
		buttonClasses: 'btn',
		applyClass: 'btn-primary',
		cancelClass: 'btn-secondary',
		timePicker: true,
		timePicker24Hour: true,
		showWeekNumbers: true,
		autoUpdateInput: true,
		maxDate: new Date,
		locale: { format: 'DD/MM/YYYY HH:mm' }
	}, function(start, end) {
		const id = $(this).attr('element').attr('id');

		document.getElementById(id + '-first').value = start.utc().format('YYYY-MM-DD HH:mm');
		document.getElementById(id + '-second').value = end.utc().format('YYYY-MM-DD HH:mm');

		updateCharts();
	});
}

function initChartDateFormat(selector = '.chart-date-format') {
	const elems = document.querySelectorAll(selector);

	for (const elem of elems) {
		elem.onchange = () => updateCharts();
	}
}

function plotLineChart(data, graphData) {
	unblockSection('#' + graphData.area_id);
	const chartContainer = document.getElementById(graphData.area_id);

	return !AmCharts ? null : AmCharts.makeChart(graphData.area_id, {
		type: 'serial',
		theme: 'light',
		creditsPosition: 'top-right',
		fontFamily: window.KTAppSettings['font-family'] ?? 'Poppins',
		mouseWheelZoomEnabled: true,
		dataDateFormat: 'YYYY-MM-DD JJ',
		valueAxes: [
			{
				id: 'v1',
				axisAlpha: 0,
				position: 'left',
				ignoreAxisWidth: true,
				precision: 0,
				minimum: 0
			}
		],
		balloon: { borderThickness: 1, shadowAlpha: 0 },
		legend: {
			enabled: true,
			position: 'top',
			switchType: 'v',
			useMarkerColorForLabels: true,
			useMarkerColorForValues: true
		},
		graphs: [
			{
				id: 'g1',
				lineColor: window.KTAppSettings.colors.theme.base.primary ?? '#3699FF',
				fillAlphas: 0.3,
				fillColorsField: 'lineColor',
				balloon: { drop: true, adjustBorderColor: false, color: '#ffffff' },
				bullet: 'round',
				bulletBorderAlpha: 1,
				bulletColor: '#FFFFFF',
				title: graphData.title,
				bulletSize: 5,
				hideBulletsCount: 50,
				lineThickness: 2,
				useLineColorForBulletBorder: true,
				valueField: chartContainer.dataset.valueProvider,
				balloonText: '<span class="font-size-lg">[[value]]</span>'
			}
		],
		chartScrollbar: {
			graph: 'g1',
			oppositeAxis: false,
			offset: 30,
			scrollbarHeight: 80,
			backgroundAlpha: 0,
			selectedBackgroundAlpha: 0.1,
			selectedBackgroundColor: '#888888',
			graphFillAlpha: 0,
			graphLineAlpha: 0.5,
			selectedGraphFillAlpha: 0,
			selectedGraphLineAlpha: 1,
			autoGridCount: true,
			color: '#AAAAAA'
		},
		chartCursor: {
			pan: true,
			valueLineEnabled: true,
			valueLineBalloonEnabled: true,
			cursorAlpha: 1,
			cursorColor: '#258cbb',
			limitToGraph: 'g1',
			valueLineAlpha: 0.2,
			valueZoomable: true,
			categoryBalloonDateFormat: 'DD/MM/YYYY JJ'
		},
		valueScrollbar: { oppositeAxis: false, offset: 50, scrollbarHeight: 10 },
		categoryField: chartContainer.dataset.dateProvider,
		categoryAxis: { parseDates: true, dashLength: 1, minorGridEnabled: true, minPeriod: 'mm' },
		export: {
			enabled: true,
			fileName: document.title + ' - ' + graphData.title,
			pageOrientation: 'landscape',
			compress: true
		},
		dataProvider: data.data
	});
}

function initStorageChart(elem) {
	const chartContainer = document.getElementById(elem);

	return !AmCharts ? null : AmCharts.makeChart(elem, {
		type: 'pie',
		theme: 'light',
		creditsPosition: 'top-right',
		fontFamily: window.KTAppSettings['font-family'] ?? 'Poppins',
		dataProvider: JSON.parse(chartContainer.dataset.chartData),
		titleField: 'model',
		valueField: 'value',
		balloon: { fixedPosition: true },
		legend: {
			enabled: true,
			position: 'top',
			switchType: 'v',
			useMarkerColorForLabels: true,
			useMarkerColorForValues: true
		},
		export: {
			enabled: true,
			fileName: document.title,
			pageOrientation: 'landscape',
			compress: true
		}
	});
}

export { init };
