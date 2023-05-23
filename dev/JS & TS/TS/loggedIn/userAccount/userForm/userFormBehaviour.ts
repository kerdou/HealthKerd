import * as userFormChecks from './userFormChecks.js';
import _ from 'lodash';

export default function userFormBehaviour()
{
    const userForm = document.getElementById('user_account_form') as HTMLFormElement;

    const  lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    const  firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    const  birthDateInput = document.getElementById('birthDate') as HTMLInputElement;
    const  loginInput = document.getElementById('login') as HTMLInputElement;
    const  mailInput = document.getElementById('mail') as HTMLInputElement;
    const  formResetButton = document.getElementById('formResetButton') as HTMLButtonElement;
    const  formSubmitButton = document.getElementById('formSubmitButton') as HTMLButtonElement;


    fieldInputsEventListeners();
    formButtonsEventListeners();

    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    function fieldInputsEventListeners(): void {
        lastnameInput.addEventListener('input', _.debounce(formChecks, 150));
        firstnameInput.addEventListener('input', _.debounce(formChecks, 150));
        birthDateInput.addEventListener('input', _.debounce(formChecks, 150));
        loginInput.addEventListener('input', _.debounce(formChecks, 150));
        mailInput.addEventListener('input', _.debounce(formChecks, 150));
    }

    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    function formButtonsEventListeners(): void {
        formResetButton.addEventListener('click', resetForm);
        formSubmitButton.addEventListener('click', submitForm);
    }

    /** Reset du form et des classes des champs inputs
     */
    function resetForm(): void {
        lastnameInput.classList.remove('is-invalid');
        firstnameInput.classList.remove('is-invalid');
        birthDateInput.classList.remove('is-invalid');
        loginInput.classList.remove('is-invalid');
        mailInput.classList.remove('is-invalid');
        userForm.reset();
    }

    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitForm(): void {
        let formValidityResult = {};
        formValidityResult = formChecks();
        const formHasIssues = _.includes(formValidityResult, false);

        // Si aucun test ne renvoie false, on peut submit le form
        if (!formHasIssues) {
            userForm.submit();
        }
    }

    /** Série de vérifications des champs du formulaire
     * @returns {object} Renvoie du statut de vérification du formulaire
     */
    function formChecks(): object {
        const formValidity = {
            lastNameCheck: false,
            firstNameCheck: false,
            birthDateCheck: false,
            loginCheck: false,
            mailCheck: false
        };

        formValidity.lastNameCheck = userFormChecks.lastNameCheck();
        formValidity.firstNameCheck = userFormChecks.firstNameCheck();
        formValidity.birthDateCheck = userFormChecks.birthDateCheck();
        formValidity.loginCheck = userFormChecks.loginCheck();
        formValidity.mailCheck = userFormChecks.mailCheck();

        return formValidity;
    }
}
