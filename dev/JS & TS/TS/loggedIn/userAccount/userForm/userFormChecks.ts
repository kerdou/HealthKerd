import NameRegex from '../../../services/regexStore/nameRegex.js';
import MailRegex from '../../../services/regexStore/mailRegex.js';
import DateRegex from '../../../services/regexStore/dateRegex.js';
import isExists from 'date-fns/isExists';

export default class UserFormChecks
{
    /** Vérification du nom de famille:
     * * Lancement du regex 'nameRegex'
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    protected lastNameCheck(): boolean {
        const lastnameInput = document.getElementById('lastname') as HTMLInputElement;
        let lastnameValue: string = lastnameInput.value;
        lastnameValue = lastnameValue.trim();

        let lastnameValidity: boolean = false;

        if (lastnameValue.length < 2) {
            lastnameValidity = false;
            lastnameInput.classList.add('is-invalid');
        } else {
            let lastnameTestObj = new NameRegex;
            let lastnameTest = lastnameTestObj.nameRegex(lastnameValue);

            if (lastnameTest) {
                lastnameValidity = true;
                lastnameInput.classList.remove('is-invalid');
            } else {
                lastnameValidity = false;
                lastnameInput.classList.add('is-invalid');
            }
        }

        return lastnameValidity;
    }

    /** Vérification du prénom:
     * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    protected firstNameCheck(): boolean {
        const firstnameInput = document.getElementById('firstname') as HTMLInputElement;
        let firstnameValue: string = firstnameInput.value;
        firstnameValue = firstnameValue.trim();

        let firstnameValidity = false;

        if (firstnameValue.length == 0) {
            firstnameValidity = true;
            firstnameInput.classList.remove('is-invalid');
        } else {
            let firstnameTestObj = new NameRegex;
            let firstnameTest = firstnameTestObj.nameRegex(firstnameValue);

            if (firstnameTest) {
                firstnameValidity = true;
                firstnameInput.classList.remove('is-invalid');
            } else {
                firstnameValidity = false;
                firstnameInput.classList.add('is-invalid');
            }
        }

        return firstnameValidity;
    }


    /** Vérification du login:
     * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    protected birthDateCheck(): boolean {
        const birthDateInput = document.getElementById('birthDate') as HTMLInputElement;
        let birthDateValue: string = birthDateInput.value;
        birthDateValue = birthDateValue.trim();

        let birthDateValidity = false;

        if (birthDateValue.length == 0) {
            birthDateValidity = true;
            birthDateInput.classList.remove('is-invalid');
        } else {
            let birthDateTestObj = new DateRegex;
            let birthDateTest = birthDateTestObj.frenchDateRegex(birthDateValue);

            if (birthDateTest) {
                const splittedDate: string[] = birthDateValue.split('/');
                const nbrSplittedDate = {
                    year: 0,
                    month: 0,
                    day: 0
                };

                splittedDate.map(
                    (value, index) => {
                        switch (index) {
                            case 0:
                                nbrSplittedDate.day = +value;
                                break;

                            case 1:
                                nbrSplittedDate.month = +value - 1;
                                break;

                            case 2:
                                nbrSplittedDate.year = +value;
                                break;
                        }
                    }
                );

                const dateValidity = isExists(
                    nbrSplittedDate.year,
                    nbrSplittedDate.month,
                    nbrSplittedDate.day
                    );

                if (dateValidity) {
                    birthDateValidity = true;
                    birthDateInput.classList.remove('is-invalid');
                } else {
                    birthDateValidity = false;
                    birthDateInput.classList.add('is-invalid');
                }
            } else {
                birthDateValidity = false;
                birthDateInput.classList.add('is-invalid');
            }
        }

        return birthDateValidity;
    }

    /** Vérification du login:
     * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    protected loginCheck(): boolean {
        const loginInput = document.getElementById('login') as HTMLInputElement;
        let loginValue: string = loginInput.value;
        loginValue = loginValue.trim();

        let loginValidity = false;

        if (loginValue.length == 0) {
            loginValidity = true;
            loginInput.classList.remove('is-invalid');
        } else {
            let loginTestObj = new NameRegex;
            let loginTest = loginTestObj.nameRegex(loginValue);

            if (loginTest) {
                loginValidity = true;
                loginInput.classList.remove('is-invalid');
            } else {
                loginValidity = false;
                loginInput.classList.add('is-invalid');
            }
        }

        return loginValidity;
    }


    /** Vérification du mail:
     * * Vérification de la syntaxe de l'adresse
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    protected mailCheck(): boolean {
        const mailInput = document.getElementById('mail') as HTMLInputElement;
        let mailValue: string = mailInput.value; // variable d'adresse e-mail
        mailValue = mailValue.trim();

        let mailValidity: boolean = false;

        if (mailValue.length == 0) {
            mailValidity = true;
            mailInput.classList.remove('is-invalid');
        } else {
            let mailTestObj = new MailRegex;
            let mailTest = mailTestObj.mailRegex(mailValue);

            if (mailTest) {
                mailValidity = true;
                mailInput.classList.remove('is-invalid');
            } else {
                mailValidity = false;
                mailInput.classList.add('is-invalid');
            }
        }

        return mailValidity;
    }
}
