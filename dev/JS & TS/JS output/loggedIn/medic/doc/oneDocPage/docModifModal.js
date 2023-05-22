//import bootstrap from 'bootstrap';
import { Modal } from 'bootstrap';
var DocModifModal = /** @class */ (function () {
    function DocModifModal() {
        this.modalButton = document.getElementById('doc_modif_modal_button');
        this.modalElement = document.getElementById('doc_modif_modal');
        this.modalObj = Modal.getOrCreateInstance(this.modalElement);
        this.modalButton.addEventListener('click', this.modalShowUp.bind(this));
    }
    /** Affichage de la boîte modale pour accéder aux forms de modif suivants:
     * * Modif générale du doc
     * * Modif des spé médic et des doc office
     * -------
     * Bootstrap n'a l'air de bien marcher qu'en gardant le code au sein de la même fonction
     */
    DocModifModal.prototype.modalShowUp = function () {
        this.modalObj.show();
    };
    return DocModifModal;
}());
export default DocModifModal;
