import DocModifModal from './doc/oneDocPage/docModifModal';
import DocFormBehaviour from './doc/generalDocForm/docFormBehaviour';
import SpeMedicDocOfficeForm from './doc/speMedicDocOfficeForm/speMedicDocOfficeForm';
var MedicController = /** @class */ (function () {
    function MedicController() {
        if (document.body.contains(document.getElementById('one_doc_page'))) {
            var docModifModalLaunch = new DocModifModal();
        }
        if (document.body.contains(document.getElementById('general_doc_form_page'))) {
            var docFormBehaviourLaunch = new DocFormBehaviour();
        }
        if (document.body.contains(document.getElementById('spemedic_docoffice_form_page'))) {
            var SpeMedicDocOfficeFormLaunch = new SpeMedicDocOfficeForm();
        }
    }
    return MedicController;
}());
export default MedicController;
