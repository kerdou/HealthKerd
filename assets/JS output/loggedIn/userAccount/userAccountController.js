import UserFormBehaviour from './userForm/userFormBehaviour.js';
import PwdFormBehaviour from './userForm/pwdFormBehaviour.js';
var UserAccountController = /** @class */ (function () {
    function UserAccountController() {
        if (document.body.contains(document.getElementById('user_account_form'))) {
            var userFormBehaviourLaunch = new UserFormBehaviour();
        }
        if (document.body.contains(document.getElementById('user_account_pwd_form'))) {
            var pwdFormBehaviourLaunch = new PwdFormBehaviour();
        }
    }
    return UserAccountController;
}());
export default UserAccountController;
