import MedicController from './medic/medicController.js';

export default class LoggedInMainController
{
    constructor() {
        if (document.getElementsByClassName('medic_section').length >= 0) {
            this.medicSectionController();
        }
    }

    private medicSectionController() {
        const medicControllerLaunch = new MedicController();
    }
}

