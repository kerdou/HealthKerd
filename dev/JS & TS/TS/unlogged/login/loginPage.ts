import _ from 'lodash';

export default class LoginPage
{
    private docu = document as Document;
    private loginForm = document.getElementById('loginForm') as HTMLFormElement;
    private errorMessage = document.getElementById('error_message') as HTMLHeadingElement;
    private loginField = document.getElementById('login') as HTMLInputElement;
    private passwordField = document.getElementById('password') as HTMLInputElement;
    private loginButton = document.getElementById('login_button') as HTMLInputElement;

    private loginFieldStatus = false;
    private passwordFieldStatus = false;

    constructor() {
        this.docu.addEventListener('keydown', this.enterPress.bind(this));
        this.loginField.addEventListener('input', _.debounce(this.loginFieldBehaviour.bind(this), 150));
        this.passwordField.addEventListener('input', _.debounce(this.passwordFieldBehaviour.bind(this), 150));
        this.loginButton.addEventListener('click', this.loginButtonBehaviour.bind(this));

        const dataFromGet = window.location.search;
        (dataFromGet.length != 0) ? this.getCheck(dataFromGet): '';
    }

    /** Gestion des erreurs à partir des infos du GET
     */
    private getCheck(dataFromGet: string): void {
        dataFromGet = dataFromGet.replace('?', '');
        const getArray = dataFromGet.split('&');

        const givenUser = getArray[0].replace('givenUser=', '');
        const errorType = getArray[1].replace('=true', '');

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
    }

    /** Contrôle de la longueur du champ de Login
     * S'il fait moins de 4 caractéres c'est pas bon et on l'entoure en rouge
     * Réagit aprés un debounce de 150ms ou le délenchement de getCheck()
     */
    private loginFieldBehaviour(): void {
        if (this.loginField.value.length < 4) {
            this.loginFieldStatus = false;
            this.loginField.classList.add('inputNotOk');
        } else {
            this.loginFieldStatus = true;
            this.loginField.classList.remove('inputNotOk');
        };
    }

    /** Contrôle de la longueur du champ de Password
     * S'il fait moins de 8 caractéres c'est pas bon et on l'entoure en rouge
     * Réagit aprés un debounce de 150ms
     */
    private passwordFieldBehaviour(): void {
        if (this.passwordField.value.length < 8) {
            this.passwordFieldStatus = false;
            this.passwordField.classList.add('inputNotOk');
        } else {
            this.passwordFieldStatus = true;
            this.passwordField.classList.remove('inputNotOk');
        };
    }

    /** Déclenchement d'une tentative de submit avec l'appui de la touche ENTER
     * @param event
     */
    private enterPress(event: KeyboardEvent): void {
        if (event.key == 'Enter') {
            this.loginButtonBehaviour();
        }
    }

    /** Submit uniquement si les champs de Login et Password sont corrects
     */
    private loginButtonBehaviour(): void {
        if ((this.loginFieldStatus) && (this.passwordFieldStatus)) {
            this.loginForm.submit();
        }
    }
}