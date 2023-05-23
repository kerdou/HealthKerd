import userFormBehaviour from './userForm/userFormBehaviour.js';
import pwdFormBehaviour from './userForm/pwdFormBehaviour.js';

export default function userAccountController()
{
    if (document.body.contains(document.getElementById('user_account_form'))) {
        userFormBehaviour();
    }

    if (document.body.contains(document.getElementById('user_account_pwd_form'))) {
        pwdFormBehaviour();
    }
}