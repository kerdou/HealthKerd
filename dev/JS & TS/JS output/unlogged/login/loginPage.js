import _ from 'lodash';
export default function loginPage() {
    var docu = document;
    var loginForm = document.getElementById('loginForm');
    var errorMessage = document.getElementById('error_message');
    var loginField = document.getElementById('login');
    var passwordField = document.getElementById('password');
    var loginButton = document.getElementById('login_button');
    var loginFieldStatus = false;
    var passwordFieldStatus = false;
    docu.addEventListener('keydown', enterPress);
    loginField.addEventListener('input', _.debounce(loginFieldBehaviour, 150));
    passwordField.addEventListener('input', _.debounce(passwordFieldBehaviour, 150));
    loginButton.addEventListener('click', loginButtonBehaviour);
    var dataFromGet = window.location.search;
    (dataFromGet.length != 0) ? getCheck(dataFromGet) : '';
    /** Gestion des erreurs à partir des infos du GET
     */
    function getCheck(dataFromGet) {
        dataFromGet = dataFromGet.replace('?', '');
        var getArray = dataFromGet.split('&');
        var givenUser = getArray[0].replace('givenUser=', '');
        var errorType = getArray[1].replace('=true', '');
        loginField.value = givenUser;
        loginFieldBehaviour();
        switch (errorType) {
            case 'unknownUser':
                errorMessage.innerText = 'Utilisateur inconnu';
                errorMessage.style.display = 'block';
                loginField.classList.add('inputNotOk');
                break;
            case 'wrongPassword':
                errorMessage.innerText = 'Mot de passe incorrect';
                errorMessage.style.display = 'block';
                passwordField.classList.add('inputNotOk');
                break;
        }
    }
    /** Contrôle de la longueur du champ de Login
     * S'il fait moins de 4 caractéres c'est pas bon et on l'entoure en rouge
     * Réagit aprés un debounce de 150ms ou le délenchement de getCheck()
     */
    function loginFieldBehaviour() {
        if (loginField.value.length < 4) {
            loginFieldStatus = false;
            loginField.classList.add('inputNotOk');
        }
        else {
            loginFieldStatus = true;
            loginField.classList.remove('inputNotOk');
        }
        ;
    }
    /** Contrôle de la longueur du champ de Password
     * S'il fait moins de 8 caractéres c'est pas bon et on l'entoure en rouge
     * Réagit aprés un debounce de 150ms
     */
    function passwordFieldBehaviour() {
        if (passwordField.value.length < 8) {
            passwordFieldStatus = false;
            passwordField.classList.add('inputNotOk');
        }
        else {
            passwordFieldStatus = true;
            passwordField.classList.remove('inputNotOk');
        }
        ;
    }
    /** Déclenchement d'une tentative de submit avec l'appui de la touche ENTER
     * @param event
     */
    function enterPress(event) {
        if (event.key == 'Enter') {
            loginButtonBehaviour();
        }
    }
    /** Submit uniquement si les champs de Login et Password sont corrects
     */
    function loginButtonBehaviour() {
        if ((loginFieldStatus) && (passwordFieldStatus)) {
            loginForm.submit();
        }
    }
}
