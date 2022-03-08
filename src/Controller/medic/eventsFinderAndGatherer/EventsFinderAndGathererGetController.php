<?php

namespace HealthKerd\Controller\medic\eventsFinderAndGatherer;

/** Controller GET des catégories d'events
*/
class EventsFinderAndGathererGetController
{
    private array $cleanedUpGet = array();
    private array $cleanedUpPost = array();

    private object $medicEventIdFinder; // Modéle spéclialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer;
    private object $medicEventArrayBuildOrder;


    public function __construct()
    {
        $this->medicEventIdFinder = new \HealthKerd\Model\modelInit\medic\eventsFinderAndGatherer\EventsSelectIdFinderModel();
        $this->medicEventDataGatherer = new \HealthKerd\Model\modelInit\medic\eventsFinderAndGatherer\EventsSelectDataGatherModel();
        $this->medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicEventArrayBuildOrder();
    }

    public function __destruct()
    {
    }

    /** Récupération des ID d'events voulus
     * * Puis récupération des données liés aux events voulus
     * * Puis recombinaison et remodelage de ces données afin de faciliter le travail de la View
     * * Puis renvoi de ces données au contrôleur ayant fait la demande
     * ------------
     * @param string $actionType    Type d'action à executer
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     * @param array $cleanedUpPost  Infos nettoyées provnants du POST
     * @return array
     */
    public function actionReceiver(
        string $actionType,
        array $cleanedUpGet = array(),
        array $cleanedUpPost = array()
    ): array {
        $this->cleanedUpGet = $cleanedUpGet;
        $this->cleanedUpPost = $cleanedUpPost;

        if ($actionType) {
            $medicEventsIdResult = array();

            switch ($actionType) {
                case 'comingEventsIds': // affichage des events à venir
                    $medicEventsIdResult = $this->medicEventIdFinder->comingEventsIds();
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;

                case 'eventsIdsByUserId':
                    $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsByUserId();
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;

                case 'eventsIdsbyCatId':
                    $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsbyCatId($cleanedUpGet['medicEventCatID']);
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;

                case 'eventsIdsFromMedicThemeId':
                    $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsFromMedicThemeId($cleanedUpGet['medicThemeID']);
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;

                case 'eventsIdsFromSpeMedicId':
                    $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsFromSpeMedicId($cleanedUpGet['speMedicID']);
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;

                case 'eventsIdsByDocOfficeId':
                    $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsByDocOfficeId($cleanedUpGet['docOfficeID']);
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;

                case 'eventsIdsFromOneDocId':
                    $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsFromOneDocId($cleanedUpGet['docID']);
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;

                case 'onlyOneEvent':
                    $medicEventsIdResult = $this->medicEventIdFinder->comingEventsIds();
                    return $this->dataGatherAndProcess($medicEventsIdResult);
                    break;
            }
        }
    }

    /** Récupération des données pour les events puis remodelage et assemblage des données pour être plus facilement gérés par le View
     * @param array $medicEventsIdResult    Liste des ID d'events simplifiée pour être utilisée pour la génération des WHERE
     * @return array                        Données des events agencées pour faciliter la construction de la View
    */
    public function dataGatherAndProcess(array $medicEventsIdResult): array
    {
        $medicEventsIdList = $this->eventIdExtraction($medicEventsIdResult);
        $medicEvtOriginalDataStore = $this->medicEventDataGatherer->eventDataGatherer($medicEventsIdList);
        $medicEvtProcessedDataStore = $this->medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore);

        return $medicEvtProcessedDataStore;
    }

    /** Extraction des ID des events et création d'une liste
     * @param array $medicEventsIdResult    Liste des ID d'events au format renvoyé par la DB
     * @return array                        Liste des ID d'events simplifiée pour être utilisée pour la génération des WHERE
     */
    public function eventIdExtraction(array $medicEventsIdResult): array
    {
        $medicEventsIdList = array();
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, $value['medicEventID']);
        }

        return $medicEventsIdList;
    }
}
