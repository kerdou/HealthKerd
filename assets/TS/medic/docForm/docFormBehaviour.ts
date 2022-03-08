if (document.body.contains(document.getElementById('doc_form_page'))) {
    const formSubmitButton = document.getElementById('formSubmitButton') as HTMLButtonElement;
    const formResetButton = document.getElementById('formResetButton') as HTMLButtonElement;
    const telInput = document.getElementById('tel') as HTMLInputElement;
    const lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    const firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    const mailInput = document.getElementById('mail') as HTMLInputElement;
    const webpageInput = document.getElementById('webpage') as HTMLInputElement;
    const doctolibpageInput = document.getElementById('doctolibpage') as HTMLInputElement;

    // boutons de validation et de reset du form
    formSubmitButton.addEventListener('click', submitForm);
    if (document.body.contains(document.getElementById('formResetButton'))) {
        formResetButton.addEventListener('click', resetForm);
    }

    // bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
    telInput.addEventListener('keydown', telKeyCheck);

    // relance la vérification des champs à chaque fois que l'un d'eux perd le focus
    lastnameInput.addEventListener('focusout', focusOutRecheck);
    firstnameInput.addEventListener('focusout', focusOutRecheck);
    telInput.addEventListener('focusout', focusOutRecheck);
    mailInput.addEventListener('focusout', focusOutRecheck);
    webpageInput.addEventListener('focusout', focusOutRecheck);
    doctolibpageInput.addEventListener('focusout', focusOutRecheck);
}

/** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide
*/
function focusOutRecheck(this: HTMLInputElement): void {
    let event = this;
    let target = event.id;
    formChecks(target);
}

/** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone quantité
 * @param {KeyboardEvent} event
 */
function telKeyCheck(event: KeyboardEvent): void {
    if (
        !(event.key >= '0' && event.key <= '9') &&
        event.key != '+' &&
        event.code != 'NumpadAdd' &&
        event.key != '.' &&
        event.code != 'NumpadDecimal' &&
        event.code != 'Backspace' &&
        event.code != 'Delete' &&
        event.code != 'ArrowLeft' &&
        event.code != 'ArrowRight' &&
        event.code != 'Tab'
    ) {
        event.preventDefault();
    } else if (event.code == 'Space') {
        event.preventDefault();
    }
}

/** Reset du form et des classes des champs inputs
 */
function resetForm(): void {
    const lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    const firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    const telInput = document.getElementById('tel') as HTMLInputElement;
    const mailInput = document.getElementById('mail') as HTMLInputElement;
    const webpageInput = document.getElementById('webpage') as HTMLInputElement;
    const doctolibpageInput = document.getElementById('doctolibpage') as HTMLInputElement;
    const docForm = document.getElementById('docForm') as HTMLFormElement;

    lastnameInput.classList.remove('is-invalid');
    firstnameInput.classList.remove('is-invalid');
    telInput.classList.remove('is-invalid');
    mailInput.classList.remove('is-invalid');
    webpageInput.classList.remove('is-invalid');
    doctolibpageInput.classList.remove('is-invalid');
    docForm.reset();
}

/** Comportement lors de l'appui sur le bouton de Submit
 */
function submitForm(): void {
    let formValidity: boolean[] = [];
    formValidity = formChecks();

    let validityStatus = formValidity.findIndex(formValidityArrayChecker);

    if (validityStatus == -1) {
        const docForm = document.getElementById('doc_form_page') as HTMLFormElement;
        docForm.submit();
    }
}
