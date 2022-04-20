//import bootstrap from 'bootstrap';
import { Modal } from 'bootstrap';
var DocModifModal = /** @class */ (function () {
    function DocModifModal() {
        this.modalButton = document.getElementById('doc_modif_modal_button');
        this.modalButton.addEventListener('click', this.modalShowUp);
    }
    /** Affichage de la boîte modale pour accéder aux forms de modif suivants:
     * * Modif générale du doc
     * * Modif des spé médic et des doc office
     * -------
     * Bootstrap n'a l'air de bien marcher qu'en gardant le code au sein de la même fonction
     */
    DocModifModal.prototype.modalShowUp = function () {
        var modalElement = document.getElementById('doc_modif_modal');
        var modalObj = Modal.getOrCreateInstance(modalElement);
        modalObj.show();
    };
    return DocModifModal;
}());
export default DocModifModal;
