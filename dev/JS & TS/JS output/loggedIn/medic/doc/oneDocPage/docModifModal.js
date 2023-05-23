//import bootstrap from 'bootstrap';
import { Modal } from 'bootstrap';
export default function docModifModal() {
    var modalElement = document.getElementById('doc_modif_modal');
    var modalObj = Modal.getOrCreateInstance(modalElement);
    var modalButton = document.getElementById('doc_modif_modal_button');
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
