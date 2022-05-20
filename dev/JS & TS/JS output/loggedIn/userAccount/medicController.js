import DocFormBehaviour from './docForm/docFormBehaviour.js';
var MedicController = /** @class */ (function () {
    function MedicController() {
        if (document.body.contains(document.getElementById('doc_form_page'))) {
            var docFormBehaviourLaunch = new DocFormBehaviour();
        }
    }
    return MedicController;
}());
export default MedicController;
