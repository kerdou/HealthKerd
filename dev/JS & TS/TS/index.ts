import mobileSidebar from './loggedIn/common/mobileSidebar.js';
import scrollUpButton from './loggedIn/common/scrollUpButton.js';
import textAreaInfoComp from './loggedIn/common/textAreaInfoComp.js';

import loggedInMainController from './loggedIn/loggedInMainController.js';
import loginPage from './unlogged/login/loginPage.js';

if (document.getElementsByClassName('unlogged_pages').length != 0) {
    loginPage();
} else {
    mobileSidebar();
    scrollUpButton();
    textAreaInfoComp();

    loggedInMainController();
}
