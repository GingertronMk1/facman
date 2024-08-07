/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

/**
 * Generating a company's prefix in the company creation screen
 */
document.addEventListener("DOMContentLoaded", () => {
    const companyFormName: HTMLInputElement = document.querySelector('input#create_company_form_name');
    if (!companyFormName) {
        return;
    }
    companyFormName
        .addEventListener('input', function (e: Event) {
            const target: HTMLInputElement = e.target as HTMLInputElement;
            console.log(target.value);
            const searchParams: URLSearchParams = new URLSearchParams({
                companyName: target.value
            });
            fetch(`/ajax/prefix/?${searchParams}`)
                .then((response: Response): Promise<object> => {
                    return response.json();
                })
                .then(({ prefix }: { prefix: string }): void => {
                    const prefixInput: HTMLInputElement = document.querySelector('input#create_company_form_prefix') as HTMLInputElement;
                    prefixInput.value = prefix;
                })
            ;
    });
})
