function init() {
	initConvertFilesToBase64();
}

function initConvertFilesToBase64(selector = 'input[type=file][data-file-convert64][data-file-convert64-target-id]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.onchange = () => convertCert(input);
	}
}

function convertCert(elem) {
	for (const file of elem.files) {
		fileToBase64(file, data => {
			document.getElementById(elem.dataset.fileConvert64TargetId).value = data;
		});
	}
}

function fileToBase64(file, callback) {
	const fileReader = new FileReader;
	fileReader.onload = e => callback(window.btoa(e.target.result));

	return fileReader.readAsBinaryString(file);
}

export { init };
