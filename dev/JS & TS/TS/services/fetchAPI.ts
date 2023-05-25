/** Envoi des données du form au backend pour vérification des champs avant envoie du mail et l'insert dans la DB
* @param   {string}            pathFinalDestination La fin du chemin menant au bon controleur
* @param   {object}            dataToSend           Data du form à envoyer
* @returns {Promise<any>}                        Statut des champs après test, statut de l'envoi du mail et de l'envoie à la DB
*/
export default async function fetchDataTransfer(pathFinalDestination: string, dataToSend: object = {}): Promise<any> {
    return fetch(
        window.location.pathname + pathFinalDestination,
        {
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            },
            method: 'POST',
            body: JSON.stringify(dataToSend)
        })
        .then((response) => {
            return response.json(); // reception des données et return
        })
        .catch((error) => {
            //console.log('Error : ' + error.message);
            return error.message; // renvoi du message d'erreur en cas de souci
        });
}