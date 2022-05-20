import MedicController from './medic/medicController.js';
import UserAccountController from './userAccount/userAccountController.js';
var LoggedInMainController = /** @class */ (function () {
    function LoggedInMainController() {
        if (document.getElementsByClassName('medic_section').length >= 0) {
            this.medicSectionController();
        }
        if (document.getElementsByClassName('user_account_section').length >= 0) {
            this.userAccountSectionController();
        }
    }
    LoggedInMainController.prototype.medicSectionController = function () {
        var medicControllerLaunch = new MedicController();
    };
    LoggedInMainController.prototype.userAccountSectionController = function () {
        var userAccountSectionControllerLaunch = new UserAccountController();
    };
    return LoggedInMainController;
}());
export default LoggedInMainController;
