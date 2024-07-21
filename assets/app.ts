/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import * as bootstrap from 'bootstrap';

document.addEventListener("DOMContentLoaded", () => {
    document
        .querySelector('input#create_company_form_name')
        .addEventListener('input', function (e: Event) {
            const target: HTMLInputElement = e.target as HTMLInputElement;
            console.log(target.value);
            const searchParams = new URLSearchParams({
                companyName: target.value
            });
            fetch(`{{ path('ajax.prefix.get') }}?${searchParams}`)
                .then(response => response.json())
                .then(({ prefix }) => {

                    const prefixInput: HTMLInputElement = document.querySelector('input#create_company_form_prefix');
                    prefixInput.value = prefix;
                })
            ;
    });
})
