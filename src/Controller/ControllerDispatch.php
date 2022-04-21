<?php

namespace HealthKerd\Controller;

/** Classe de dispatch qui lance tout le reste
 * * Récupération et analyse du contenu de $_GET['controller'] et $_GET['action']
 */
class ControllerDispatch extends GetAndPostCleaner
{
    private array $cleanedUpGet; // Recoit les données du $_GET une fois nettoyées
    private array $cleanedUpPost; // Recoit les données du $_POST une fois nettoyées
    private string $selectedController; // Recoit le nom du controleur qui est forcément inclut dans $_GET['controller']

    /** Nettoyage des $_GET et $_POST avant envoi pour la suite
     */
    public function __construct()
    {
        $this->cleanedUpGet = (isset($_GET)) ? $this->inputCleaner($_GET) : array();
        $this->cleanedUpPost = (isset($_POST)) ? $this->inputCleaner($_POST) : array();

        $this->generalSetter();
        $this->sessionChecker();
    }

    public function __destruct()
    {
    }

    /** Lancement de fonctions et options pouvant être utilisées sur toute l'appli
     */
    private function generalSetter(): void
    {
        session_start();
        setlocale(LC_TIME, 'fr_FR', 'fra');
        date_default_timezone_set('Europe/Paris');
        \HealthKerd\Services\common\DateAndTimeManagement::envDateSetter();
        $_ENV['APPROOTPATH'] = __DIR__ . '/' . '../../'; // pour avoir le chemin de la racine du projet accessible depuis n'importe quel fichier
    }

    /** Vérifie si $_SESSION contient des données ou pas, s'il n'en contient pas ce qu'on est pas loggé
     * Donc on part sur différents controleurs adaptés au bon cas de figure
    */
    private function sessionChecker(): void
    {
        if (empty($_SESSION)) {
            // récupération du controleur voulu, si aucun n'est précisé on part sur 'login'
            $this->selectedController = (isset($this->cleanedUpGet['controller'])) ? $this->cleanedUpGet['controller'] : 'loginPage';
            $this->unloggedDispatcher();
        } else {
            // récupération du controleur voulu, si aucun n'est précisé on part sur 'home'
            $this->selectedController = (isset($this->cleanedUpGet['controller'])) ? $this->cleanedUpGet['controller'] : 'home';
            $this->loggedInDispatcher();
        }
    }

    /** Oriente vers la page de login ou de création de compte si $_SESSION est vide
     */
    private function unloggedDispatcher(): void
    {
        switch ($this->selectedController) {
            case 'login': // Afficher la page de login
                $controllerObj = new \HealthKerd\Controller\login\LoginGetController();
                $controllerObj->displayLoginPage($this->cleanedUpGet);
                break;

            case 'loginPost': // Envoyer les logins pour se connecter
                $controllerObj = new \HealthKerd\Controller\login\LoginPostController();
                $controllerObj->actionReceiver($this->cleanedUpGet, $this->cleanedUpPost);
                break;

            case 'userAccount': // Afficher la page de création de compte
                $controllerObj = new \HealthKerd\Controller\userAccount\UserAccountGetController();
                $controllerObj->actionReceiver($this->cleanedUpGet);
                break;

            case 'userAccountPost': // Création du compte dans la DB
                $controllerObj = new \HealthKerd\Controller\userAccount\UserAccountPostController();
                $controllerObj->actionReceiver($this->cleanedUpGet, $this->cleanedUpPost);
                break;

            default: // Renvoi vers la page de login si rien n'est précisé
                $controllerObj = new \HealthKerd\Controller\login\LoginGetController();
                $controllerObj->displayLoginPage($this->cleanedUpGet);
        }
    }

    /** Lancement du contrôleur voulu si $_SESSION est vide, si aucun ne correspond on part sur HomeGetController
     * * Tout se fait par le cleanedUpGet['controller'], le cleanedUpPost n'est jamais sollicité
     * * Les cases finissant par Post viennent des submits de formulaires, les autres viennent des liens de nav
     * * Cette technique empeche l'insertion de données venant du $_POST en appuyant sur F5 puisque le $_POST n'est pas sollicité
     */
    private function loggedInDispatcher(): void
    {
        switch ($this->selectedController) {
            case 'login':
                $controllerObj = new \HealthKerd\Controller\login\LoginGetController();
                $controllerObj->actionReceiver($this->cleanedUpGet);
                break;

            case 'userAccount':
                $controllerObj = new \HealthKerd\Controller\userAccount\UserAccountGetController();
                $controllerObj->actionReceiver($this->cleanedUpGet);
                break;

            case 'userAccountPost':
                $controllerObj = new \HealthKerd\Controller\userAccount\UserAccountPostController();
                $controllerObj->actionReceiver($this->cleanedUpGet, $this->cleanedUpPost);
                break;

            case 'home':
                $controllerObj = new \HealthKerd\Controller\home\HomeGetController();
                $controllerObj->displayHomePage();
                break;

            case 'medic': // controleur général de la partie 'medic'
                $controllerObj = new \HealthKerd\Controller\medic\MedicMainController();
                $controllerObj->subContReceiver($this->cleanedUpGet, $this->cleanedUpPost);
                break;

            default:
                $controllerObj = new \HealthKerd\Controller\home\HomeGetController();
                $controllerObj->displayHomePage();
        }
    }
}
