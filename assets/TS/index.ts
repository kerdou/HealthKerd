import MobileSidebar from './loggedIn/common/mobileSidebar.js'
import ScrollUpButton from './loggedIn/common/scrollUpButton.js';
import TextAreaInfoComp from './loggedIn/common/textAreaInfoComp.js';
import LoggedInMainController from './loggedIn/loggedInMainController.js';

if (document.getElementsByClassName('unlogged_pages').length != 0) {
    unloggedDispatcher();
} else {
        const mobileSidebarObj = new MobileSidebar();
        const scrollUpButtonObj = new ScrollUpButton();
        const textAreaInfoCompObj = new TextAreaInfoComp();

    loggedInDispatcher();
}

function unloggedDispatcher() {
    // unlogged behaviour
}

function loggedInDispatcher() {
        const mainControllerLaunch = new LoggedInMainController();
}