import _ from 'lodash';

export default function loginPage()
{
    const docu = document as Document;
    const loginForm = document.getElementById('loginForm') as HTMLFormElement;
    const errorMessage = document.getElementById('error_message') as HTMLHeadingElement;
    const loginField = document.getElementById('login') as HTMLInputElement;
    const passwordField = document.getElementById('password') as HTMLInputElement;
    const loginButton = document.getElementById('login_button') as HTMLInputElement;

    let loginFieldStatus = false;
    let passwordFieldStatus = false;


    docu.addEventListener('keydown', enterPress);
    loginField.addEventListener('input', _.debounce(loginFieldBehaviour, 150));
    passwordField.addEventListener('input', _.debounce(passwordFieldBehaviour, 150));
    loginButton.addEventListener('click', loginButtonBehaviour);

    const dataFromGet = window.location.search;
    (dataFromGet.length != 0) ? getCheck(dataFromGet): '';


    /** Gestion des erreurs à partir des infos du GET
     */
    function getCheck(dataFromGet: string): void {
        dataFromGet = dataFromGet.replace('?', '');
        const getArray = dataFromGet.split('&');

        const givenUser = getArray[0].replace('givenUser=', '');
        const errorType = getArray[1].replace('=true', '');

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
    function loginFieldBehaviour(): void {
        if (loginField.value.length < 4) {
            loginFieldStatus = false;
            loginField.classList.add('inputNotOk');
        } else {
            loginFieldStatus = true;
            loginField.classList.remove('inputNotOk');
        };
    }

    /** Contrôle de la longueur du champ de Password
     * S'il fait moins de 8 caractéres c'est pas bon et on l'entoure en rouge
     * Réagit aprés un debounce de 150ms
     */
    function passwordFieldBehaviour(): void {
        if (passwordField.value.length < 8) {
            passwordFieldStatus = false;
            passwordField.classList.add('inputNotOk');
        } else {
            passwordFieldStatus = true;
            passwordField.classList.remove('inputNotOk');
        };
    }

    /** Déclenchement d'une tentative de submit avec l'appui de la touche ENTER
     * @param event
     */
    function enterPress(event: KeyboardEvent): void {
        if (event.key == 'Enter') {
            loginButtonBehaviour();
        }
    }

    /** Submit uniquement si les champs de Login et Password sont corrects
     */
    function loginButtonBehaviour(): void {
        if ((loginFieldStatus) && (passwordFieldStatus)) {
            loginForm.submit();
        }
    }
}