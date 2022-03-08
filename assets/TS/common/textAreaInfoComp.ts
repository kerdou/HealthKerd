window.addEventListener('load', operationsAtLoad);

function operationsAtLoad(): void {
    if (document.body.contains(document.getElementById('desktop_sidebar'))) {
        textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea
    }
}

/** Ajout d'events liseners sur tous les textareas qui ont la classe 'textarea-ridonli'
 * pour faire disparaitre "Informations complémentaires" au scroll des textareas
 */
function textAreaRidonliListenersAddition(): void {
    let ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));

    ridonList.forEach((element) => {
        element.addEventListener('scroll', textAreaScrollDown);
    });
}

/** Disparisation de la phrase 'Informations complémentaires sur l'évènement' quand on scroll down dans les textareas
 * @param {HTMLTextAreaElement} this
 */
function textAreaScrollDown(this: HTMLTextAreaElement): void {
    const label = this.nextElementSibling as HTMLLabelElement;
    label.style.opacity = '0';
}
