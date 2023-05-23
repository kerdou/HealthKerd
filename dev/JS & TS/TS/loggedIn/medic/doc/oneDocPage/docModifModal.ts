//import bootstrap from 'bootstrap';
import { Modal } from 'bootstrap';

export default function docModifModal()
{
    const modalElement = document.getElementById('doc_modif_modal') as HTMLDivElement;
    const modalObj = Modal.getOrCreateInstance(modalElement) as Modal;

    const modalButton = document.getElementById('doc_modif_modal_button') as HTMLAnchorElement;
    modalButton.addEventListener('click', modalShowUp);

    /** Affichage de la boîte modale pour accéder aux forms de modif suivants:
     * * Modif générale du doc
     * * Modif des spé médic et des doc office
     * -------
     * Bootstrap n'a l'air de bien marcher qu'en gardant le code au sein de la même fonction
     */
    function modalShowUp() {
        modalObj.show();
    }
}