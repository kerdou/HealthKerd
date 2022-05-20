import UserFormBehaviour from './userForm/userFormBehaviour.js';
import PwdFormBehaviour from './userForm/pwdFormBehaviour.js';

export default class UserAccountController
{
    constructor() {
        if (document.body.contains(document.getElementById('user_account_form'))) {
            const userFormBehaviourLaunch = new UserFormBehaviour();
        }

        if (document.body.contains(document.getElementById('user_account_pwd_form'))) {
            const pwdFormBehaviourLaunch = new PwdFormBehaviour();
        }
    }
}