import DocFormBehaviour from './docForm/docFormBehaviour.js';

export default class MedicController
{
    constructor() {
        if (document.body.contains(document.getElementById('doc_form_page'))) {
            const docFormBehaviourLaunch = new DocFormBehaviour();
        }
    }
}