window.addEventListener('load', operationsAtLoad);

/** Copie du contenu du sidebar dans le off canvas sidebar au chargement de la page
*/
function operationsAtLoad(): void {
    if (document.body.contains(document.getElementById('desktop_sidebar'))) {
        textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea

    }
}

/** Pour faire disparaitre "Informations complémentaires" au scroll des textarea
 */
function textAreaRidonliListenersAddition(): void {
    let ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));

    ridonList.forEach((element) => {
        element.addEventListener('scroll', textAreaScrollDown);
    });
}

/**
 *
 * @param this
 */
function textAreaScrollDown(this: HTMLTextAreaElement): void {
    const label = this.nextElementSibling as HTMLLabelElement;
    label.style.opacity = '0';
}
