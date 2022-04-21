import _ from 'lodash';
var LoginPage = /** @class */ (function () {
    function LoginPage() {
        this.loginForm = document.getElementById('loginForm');
        this.errorMessage = document.getElementById('error_message');
        this.loginField = document.getElementById('login');
        this.passwordField = document.getElementById('password');
        this.loginButton = document.getElementById('login_button');
        this.loginFieldStatus = false;
        this.passwordFieldStatus = false;
        this.loginField.addEventListener('input', _.debounce(this.loginFieldBehaviour.bind(this), 150));
        this.passwordField.addEventListener('input', _.debounce(this.passwordFieldBehaviour.bind(this), 150));
        this.loginButton.addEventListener('click', this.loginButtonBehaviour.bind(this));
        var dataFromGet = window.location.search;
        (dataFromGet.length != 0) ? this.getCheck(dataFromGet) : '';
    }
    /** Gestion des erreurs à partir des infos du GET
     */
    LoginPage.prototype.getCheck = function (dataFromGet) {
        dataFromGet = dataFromGet.replace('?', '');
        var getArray = dataFromGet.split('&');
        var givenUser = getArray[0].replace('givenUser=', '');
        var errorType = getArray[1].replace('=true', '');
        this.loginField.value = givenUser;
        this.loginFieldBehaviour();
        switch (errorType) {
            case 'unknownUser':
                this.errorMessage.innerText = 'Utilisateur inconnu';
                this.errorMessage.style.display = 'block';
                this.loginField.classList.add('inputNotOk');
                break;
            case 'wrongPassword':
                this.errorMessage.innerText = 'Mot de passe incorrect';
                this.errorMessage.style.display = 'block';
                this.passwordField.classList.add('inputNotOk');
                break;
        }
    };
    /** Contrôle de la longueur du champ de Login
     * S'il fait moins de 4 caractéres c'est pas bon et on l'entoure en rouge
     * Réagit aprés un debounce de 150ms ou le délenchement de getCheck()
     */
    LoginPage.prototype.loginFieldBehaviour = function () {
        if (this.loginField.value.length < 4) {
            this.loginFieldStatus = false;
            this.loginField.classList.add('inputNotOk');
        }
        else {
            this.loginFieldStatus = true;
            this.loginField.classList.remove('inputNotOk');
        }
        ;
    };
    /** Contrôle de la longueur du champ de Password
     * S'il fait moins de 8 caractéres c'est pas bon et on l'entoure en rouge
     * Réagit aprés un debounce de 150ms
     */
    LoginPage.prototype.passwordFieldBehaviour = function () {
        if (this.passwordField.value.length < 8) {
            this.passwordFieldStatus = false;
            this.passwordField.classList.add('inputNotOk');
        }
        else {
            this.passwordFieldStatus = true;
            this.passwordField.classList.remove('inputNotOk');
        }
        ;
    };
    /** Submit uniquement si les champs de Login et Password sont corrects
     */
    LoginPage.prototype.loginButtonBehaviour = function () {
        if ((this.loginFieldStatus) && (this.passwordFieldStatus)) {
            this.loginForm.submit();
        }
    };
    return LoginPage;
}());
export default LoginPage;
