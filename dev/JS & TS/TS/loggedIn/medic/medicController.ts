import docModifModal from './doc/oneDocPage/docModifModal';
import docFormBehaviour from './doc/generalDocForm/docFormBehaviour';
import speMedicDocOfficeForm from './doc/speMedicDocOfficeForm/speMedicDocOfficeForm';

export default function medicController()
{
    if (document.body.contains(document.getElementById('one_doc_page'))) {
        docModifModal();
    }

    if (document.body.contains(document.getElementById('general_doc_form_page'))) {
        docFormBehaviour();
    }

    if (document.body.contains(document.getElementById('spemedic_docoffice_form_page'))) {
        speMedicDocOfficeForm();
    }
}