
function init() {
    initiateVatRates();
}

function initiateVatRates(){

    const elements = document.getElementsByClassName('countries-item');

	const labels = [];

    for(let i = 0; i < elements.length; i++){

        const elementFirstLetter = elements[i].parentElement.previousElementSibling.textContent.trim().charAt(0);

        if(!labels.includes(elementFirstLetter)){
            labels.push(elementFirstLetter);
            elements[i].parentElement.parentElement.insertAdjacentHTML('afterbegin',`<div class="first-letter">${elementFirstLetter}</div>`);
        }

        elements[i].addEventListener('keyup', function (event) {

			const targetElement = event.target
            if (targetElement && targetElement.value > 0) {
                targetElement.classList.remove('border-warning');
                targetElement.classList.add('border-primary');
            }else{
                targetElement.classList.remove('border-primary');
				targetElement.classList.add('border-warning');
            }

        });
    }
}

export { init };
