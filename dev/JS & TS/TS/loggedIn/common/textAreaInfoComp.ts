export default class TextAreaInfoComp
{
    constructor() {
        this.textAreaRidonliListenersAddition();
    }

    /** Ajout d'events listeners sur tous les textareas qui ont la classe 'textarea-ridonli'
     * pour faire disparaitre "Informations complémentaires" au scroll des textareas
     */
    private textAreaRidonliListenersAddition(): void {
        const ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));
        ridonList.forEach((element) => {
            element.addEventListener('scroll', this.textAreaScrollDown);
        });
    }

    /** Disparisation de la phrase 'Informations complémentaires sur l'évènement' quand on scroll down dans les textareas
     * @param {HTMLTextAreaElement} this
     */
    private textAreaScrollDown(this: HTMLTextAreaElement): void {
        const label = this.nextElementSibling as HTMLLabelElement;
        label.style.opacity = '0';
    }

}




