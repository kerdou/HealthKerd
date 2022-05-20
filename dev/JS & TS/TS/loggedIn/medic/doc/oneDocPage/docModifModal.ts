//import bootstrap from 'bootstrap';
import { Modal } from 'bootstrap';

export default class DocModifModal
{
    private modalButton = document.getElementById('doc_modif_modal_button') as HTMLAnchorElement;

    private modalElement = document.getElementById('doc_modif_modal') as HTMLDivElement;
    private modalObj = Modal.getOrCreateInstance(this.modalElement) as Modal;

    constructor() {
        this.modalButton.addEventListener('click', this.modalShowUp.bind(this));
    }

    /** Affichage de la boîte modale pour accéder aux forms de modif suivants:
     * * Modif générale du doc
     * * Modif des spé médic et des doc office
     * -------
     * Bootstrap n'a l'air de bien marcher qu'en gardant le code au sein de la même fonction
     */
    private modalShowUp() {
        this.modalObj.show();
    }
}