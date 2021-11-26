<?php

    // affichage des rendez-vous
    function appointDisplay($timePeriod, $appointArray)
    {
        switch ($timePeriod) {
            case 'after':
                echo '<h3> '. count($appointArray) .' rendez-vous à venir:</h3>';
                break;

            case 'today':
                echo '<h3> '. count($appointArray) .' rendez-vous de la journée:</h3>';
                break;

            case 'before':
                echo '<h3> '. count($appointArray) .' rendez-vous passés:</h3>';
                break;

            default:
                echo '<h3>Rendez-vous à la temporalité inconnue:</h3>';
        }

        foreach ($appointArray as $appointKey => $appointValue) {
            //var_dump($appointValue);
            ?>
            <div class="appoint">
                <div>
                    <!-- Titre du rendez-vous -->
                    <h5><b><?php echo $appointValue['title'] ?></b><br></h5>

                    <!-- ID, type et date du rdv -->
                    Rendez-vous n° <?php echo $appointValue['medicAppointListID'] .
                    ' le  '.$appointValue['time']['startDateTime']. ' <a href="
                    https://www.doctolib.fr/account/appointments" target="_blank">DoctoLib</a>';

                    $docFullName = $appointValue['doc']['docFirstName'] . ' ' . $appointValue['doc']['docLastName'];
                    $speSentence = $appointValue['doc']['speMedic']['sentence'];
                    $docNameAndSpe = $docFullName . $speSentence;
                    echo '<br>' . $docNameAndSpe;


                    // affichage supplémentaire si c'était un remplacant
                    if ($appointValue['substit']['substitdID'] > 0) {
                        echo '<br>Remplaçant de '
                            .$appointValue['substit']['substitFirstName']. ' '
                            .$appointValue['substit']['substitLastName'];
                    }
                    ?>
                    <br>
                    <?php
                    // affichage de l'emplacement du rdv
                        echo $appointValue['cab']['cabName'];
                    ?>
                </div>

                <?php
                // affichage du contenu de la description du rdv s'il y en a un
                if ($appointValue['comment']) {
                    ?>
                    <div>
                        <br>
                        <u>Commentaire:</u><br>
                        <p class="cattext"><?php echo nl2br(trim($appointValue['comment'])) ?></p>
                    </div>
                    <?php
                }

                // affichage des diagnostics
                if (count($appointValue['diag']) > 0) {
                    echo '<br>
                    <h5><u>Diagnostic(s):</u></h5>';

                    foreach ($appointValue['diag'] as $diagKey => $diagValue) {
                        diagAppointDisplay($diagValue);
                    }
                }

                // affichage des rdv labo
                if (count($appointValue['labo']) > 0) {
                    echo '<br>
                    <h5><u>Laboratoire médicale:</u></h5>';

                    foreach ($appointValue['labo'] as $laboKey => $laboValue) {
                        laboAppointDisplay($laboValue);
                    }
                }

                // affichage des vaccinations
                if (count($appointValue['vax']) > 0) {
                    echo '<br>
                    <h5><u>Vaccination:</u></h5>';

                    foreach ($appointValue['vax'] as $vaxKey => $vaxValue) {
                        vaxAppointDisplay($laboValue);
                    }
                }

                // affichage des traitements
                if (count($appointValue['treat']) > 0) {
                    echo '<br>
                    <h5><u>Traitement(s):</u></h5>';

                    foreach ($appointValue['treat'] as $treatKey => $treatValue) {
                        treatAppointDisplay($treatValue);
                    }
                }

                // affichage des ordonnances
                if (count($appointValue['ordo']) > 0) {
                    echo '<br>
                        <h5><u>Ordonnance(s):</u></h5>';

                    // affichage du contenu des ordonnances
                    foreach ($appointValue['ordo'] as $ordokey => $ordoValue) {
                        switch ($ordoValue['ordoType']) {
                            case 'pharma':
                                pharmaOrdoDisplay($ordoValue);
                                break;

                            case 'labo':
                                laboOrdoDisplay($ordoValue);
                                break;

                            case 'glass':
                                glassOrdoDisplay($ordoValue);
                                break;

                            case 'lens':
                                lensOrdoDisplay($ordoValue);
                                break;

                            default:
                                echo 'Type inconnu';
                        }
                    }
                }
                ?>
            </div> <!-- End of class="appoint" -->
            <?php
        }
    }


    // création de la phrase contenant toutes les spé medic
    function speMedicSentenceBuilder(&$everyAppointSortedWithEveryData)
    {
        foreach ($everyAppointSortedWithEveryData as $key => $value) {
            $speSentence = ' (' . implode(', ', $value['doc']['speMedic']) . ')';
            $everyAppointSortedWithEveryData[$key]['doc']['speMedic']['sentence'] = $speSentence;
        }
    }


