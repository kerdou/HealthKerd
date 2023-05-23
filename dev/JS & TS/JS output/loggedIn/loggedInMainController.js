import medicController from './medic/medicController.js';
import userAccountController from './userAccount/userAccountController.js';
export default function loggedInMainController() {
    if (document.getElementsByClassName('medic_section').length >= 0) {
        medicController();
    }
    if (document.getElementsByClassName('user_account_section').length >= 0) {
        userAccountController();
    }
}
