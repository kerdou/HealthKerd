import MedicController from './medic/medicController.js';
var LoggedInMainController = /** @class */ (function () {
    function LoggedInMainController() {
        if (document.getElementsByClassName('medic_section').length >= 0) {
            this.medicSectionController();
        }
    }
    LoggedInMainController.prototype.medicSectionController = function () {
        var medicControllerLaunch = new MedicController();
    };
    return LoggedInMainController;
}());
export default LoggedInMainController;
