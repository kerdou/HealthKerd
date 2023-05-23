export default function textAreaInfoComp()
{
    textAreaRidonliListenersAddition();


    /** Ajout d'events listeners sur tous les textareas qui ont la classe 'textarea-ridonli'
     * pour faire disparaitre "Informations complémentaires" au scroll des textareas
     */
    function textAreaRidonliListenersAddition(): void {
        const ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));
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

}