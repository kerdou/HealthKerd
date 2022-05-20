import MedicController from './medic/medicController.js';
import UserAccountController from './userAccount/userAccountController.js'

export default class LoggedInMainController
{
    constructor() {
        if (document.getElementsByClassName('medic_section').length >= 0) {
            this.medicSectionController();
        }

        if (document.getElementsByClassName('user_account_section').length >= 0) {
            this.userAccountSectionController();
        }
    }

    private medicSectionController() {
        const medicControllerLaunch = new MedicController();
    }

    private userAccountSectionController() {
        const userAccountSectionControllerLaunch = new UserAccountController();
    }
}

