<?php

    // affichage des examens en labo médical
    function laboAppointDisplay($laboValue)
    {
        var_dump($laboValue);
        ?>
        <div class="labo_appoint">

        </div>
        <?php
    }


    // affichage des ordonnances prise de sang
    function laboOrdoDisplay($ordoValue)
    {
        //var_dump($ordoValue);
        $laboOrdoElementsList = laboOrdoElementsListBuilder($ordoValue['elements']); // création des <li> des élements des ordonnances de prise de sang
        $slots = slotsBuilder($ordoValue['slots']); // création des <div> de slots

        ?>
        <div class="ordo">
            <div>
                <h6><b>Prise de sang</b></h6>
                Ordonnance n° S<?php echo $ordoValue['laboOrdoListID'] ?>
                datée du <?php echo $ordoValue['time']['date']?><br>
            </div>
            <br>

            <div>
                <u>Contenu de l'ordonnance:</u>
                <ul class="cattext"><?php echo $laboOrdoElementsList ?></ul>
            </div>

            <div>
                <?php
                if ($ordoValue['comment']) {
                    ?>
                    <u>Commentaire de l'ordonnance:</u>
                    <p class="cattext"><?php echo nl2br(trim($ordoValue['comment'])) ?></p>
                    <?php
                }
                ?>
            </div>

            <div>
                <u>Rendez-vous de prise de sang:</u>
                <?php echo $slots ?>

            </div>
        </div>
        <?php
    }




        // création des <li> des élements des ordonnances de prise de sang
        function laboOrdoElementsListBuilder($elements)
        {
            $laboOrdoElementsListString = '';
            $laboOrdoElementsListArray = array();

            foreach ($elements as $key => $value) {
                array_push($laboOrdoElementsListArray, '<li>' .$value['laboOrdoElementContent']. '</li>');
            }
            $laboOrdoElementsListString = implode('', $laboOrdoElementsListArray);

            return $laboOrdoElementsListString;
        }


        // création des <div> de slots
        function slotsBuilder($slots)
        {
            $slotsString = '';
            $slotsArray = array();

            foreach ($slots as $slotKey => $slotValue) {
                $tempString = '<div class="slot">';
                $tempString = $tempString;

                if ($slotValue['laboOrdoSlotImmediacy']) {
                    $tempString = $tempString. 'Prise de sang à faire au plus vite<br>';
                } else {
                    $tempString = $tempString. 'Prise de sang à faire sur la période de: ' .$slotValue['laboOrdoSlotTimePeriod']. '<br>';
                }

                switch ($slotValue['laboOrdoSlotStatus']) {
                    case 'pending':
                        $tempString = $tempString. 'Statut: A faire';
                        break;
                    case 'taken':
                        $tempString = $tempString. 'Statut: Fait';
                        break;
                    case 'disabled':
                        $tempString = $tempString. 'Statut: Annulé';
                        break;
                }

                $tempString = $tempString . '</div>';
                array_push($slotsArray, $tempString);
            }

            $slotsString = implode('<br>', $slotsArray);
            return $slotsString;
        }