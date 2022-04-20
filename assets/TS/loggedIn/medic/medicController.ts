import DocModifModal from './doc/oneDocPage/docModifModal';
import DocFormBehaviour from './doc/generalDocForm/docFormBehaviour';
import SpeMedicDocOfficeForm from './doc/speMedicDocOfficeForm/speMedicDocOfficeForm';

export default class MedicController
{
    constructor() {
        if (document.body.contains(document.getElementById('one_doc_page'))) {
            const docModifModalLaunch = new DocModifModal();
        }

        if (document.body.contains(document.getElementById('general_doc_form_page'))) {
            const docFormBehaviourLaunch = new DocFormBehaviour();
        }

        if (document.body.contains(document.getElementById('spemedic_docoffice_form_page'))) {
            const SpeMedicDocOfficeFormLaunch = new SpeMedicDocOfficeForm();
        }
    }
}