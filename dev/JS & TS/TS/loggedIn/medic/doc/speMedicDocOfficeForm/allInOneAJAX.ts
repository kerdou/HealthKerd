export default class AllInOneAJAX
{
    // réception des données à l'ouverture de la page
    public async receive(): Promise<any> {
        let xhr = new XMLHttpRequest();

        return new Promise(resolve => {
            const phpScriptPath = window.location.pathname + "?controller=medic&subCtrlr=doc&action=getAJAXDataForSpeMedDocOfficeForm"; // les params sont placés à la fin de l'URL pour le GET
            xhr.open("GET", phpScriptPath, true); // true pour avoir une requête asynchrone

            xhr.onload = () => resolve({
                status: xhr.status,
                response: JSON.parse(xhr.response)
            });
            // pour le débug, remplacer par "response: xhr.response" et afficher le response dans la DIV "debug"

            xhr.send();
        });
    }

    // envoi des données du form au clic sur "Envoyer"
    public async send(params: string): Promise<any> {
        let xhr = new XMLHttpRequest();

        return new Promise(resolve => {
            const phpScriptPath = window.location.pathname + "?controller=medic&subCtrlr=docPost&action=editSpeMedDocOfficeForDoc";
            xhr.open("POST", phpScriptPath, true); // true pour avoir une requête asynchrone
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // permet d'envoyer des données via le POST

            xhr.onload = () => resolve({
                status: xhr.status,
                response: xhr.response
            });
            // pour le debug, afficher le response dans la DIV "debug"

            xhr.send(params); // les params sont placés dans le send() pour le POST
        });
    }
}