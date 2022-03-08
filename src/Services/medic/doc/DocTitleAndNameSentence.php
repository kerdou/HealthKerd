<?php

namespace HealthKerd\Services\medic\doc;

/** Classe destinée à convertir les noms et titre des docteurs
 */
class DocTitleAndNameSentence
{
    private string $docID;
    private string $title;
    private string $firstName;
    private string $lastName;
    private string $fullNameSentence = '';

    public function __destruct()
    {
    }

    /** Reçoit et lance la transformation des données pour renvoyer un texte regroupant titre, prénom et nom suivant les cas de figure
     * @param string $docID         ID du docteur
     * @param string $title         Titre ou civilité
     * @param string $firstName     Prénom
     * @param string $lastName      Nom de famille
     * @return string               Phrase combinant toutes ces données suivant les cas de figure
     */
    public function dataReceiver(
        string $docID,
        string $title,
        string $firstName,
        string $lastName
    ): string {
        $this->docID = $docID;
        $this->title = $title;
        $this->firstName = $firstName;
        $this->lastName = $lastName;

        $this->docTitleConversion();
        $this->docFullNameSentenceCreator();

        return $this->fullNameSentence;
    }

    /** Remplacement du titre du pro de santé pour être plus présentable
     * * Etape indispensable avant de lancer docFullNameSentenceCreator()
     */
    private function docTitleConversion()
    {
        switch ($this->title) {
            case 'dr':
                $this->title = 'Dr';
                break;
            case 'mr':
                $this->title = 'Mr';
                break;
            case 'mrs':
                $this->title = 'Mme';
                break;
            case 'ms':
                $this->title = 'Mlle';
                break;
            case 'none':
                $this->title = '';
                break;
        }
    }

    /** Création de la phrase contenant le titre, le nom et le prénom du pro de santé
     * * Dépend de la présence ou l'absence du titre de Dr
     * * Dépend de la présence ou l'absence du prénom
     */
    private function docFullNameSentenceCreator()
    {
        // on vérifie le titre, seuls les cas des 'dr' et des 'none' sont vraiment ciblés
        if ($this->title == 'Dr') {
            $titleType = 'dr';
        } elseif ($this->title == '') {
            $titleType = 'none';
        } else {
            $titleType = 'other';
        }

        // les tests se déclenchent uniquement si ce n'est pas un docID == 0
        if ($this->docID != '0') {
            switch ($titleType) {
                case 'dr':
                    if (strlen($this->firstName) > 0) {
                        $this->fullNameSentence = $this->title . ' ' . ucwords($this->firstName) . ' ' . ucwords($this->lastName);
                    } else {
                        $this->fullNameSentence = $this->title . ' ' . ucwords($this->lastName);
                    }
                    break;

                case 'other':
                    if (strlen($this->firstName) > 0) {
                        $this->fullNameSentence = ucwords($this->firstName) . ' ' . ucwords($this->lastName);
                    } else {
                        $this->fullNameSentence = $this->title . ' ' . ucwords($this->lastName);
                    }
                    break;

                case 'none':
                    if (strlen($this->firstName) > 0) {
                        $this->fullNameSentence = ucfirst($this->firstName) . ' ' . ucfirst($this->lastName);
                    } else {
                        $this->fullNameSentence = ucfirst($this->lastName);
                    }
                    break;
            }
        }
    }
}
