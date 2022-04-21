import MobileSidebar from './loggedIn/common/mobileSidebar.js';
import ScrollUpButton from './loggedIn/common/scrollUpButton.js';
import TextAreaInfoComp from './loggedIn/common/textAreaInfoComp.js';
import LoggedInMainController from './loggedIn/loggedInMainController.js';
import LoginPage from './unlogged/login/loginPage.js';
if (document.getElementsByClassName('unlogged_pages').length != 0) {
    unloggedDispatcher();
}
else {
    var mobileSidebarObj = new MobileSidebar();
    var scrollUpButtonObj = new ScrollUpButton();
    var textAreaInfoCompObj = new TextAreaInfoComp();
    loggedInDispatcher();
}
function unloggedDispatcher() {
    var loginPageLaunch = new LoginPage();
}
function loggedInDispatcher() {
    var mainControllerLaunch = new LoggedInMainController();
}
