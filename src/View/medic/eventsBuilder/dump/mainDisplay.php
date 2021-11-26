<?php


    // affichage global
    //displayer($dataStore['user']['pdoResult'], $appointsBeforeArray, $appointsTodayArray, $appointsAfterArray); // affichage des rendez-vous
    function displayer($userResult, $appointsBeforeArray, $appointsTodayArray, $appointsAfterArray)
    {
        ?>
        <body>
            <div class="container">
                <h3>Liste des rendez-vous, ordonnances, prescriptions pour tous les docteurs</h3>
                <h4>Différenciation entre rdv passé et à venir</h4>
                <br>
                <div>
                    <label for="">User:</label>
                    <output><?php echo $userResult[0]['userFirstName']. ' ' .$userResult[0]['userLastName'] ?></output>
                </div>
                <br>

                <?php
                if (count($appointsAfterArray) > 0) {
                    appointDisplay('after', $appointsAfterArray);
                }

                if (count($appointsTodayArray) > 0) {
                    appointDisplay('today', $appointsTodayArray);
                }

                if (count($appointsBeforeArray) > 0) {
                    appointDisplay('before', $appointsBeforeArray);
                }
                ?>
            </div> <!-- End of class="container" -->
        </body>
        <?php
    }





    // affichage des traitements dans les rendez-vous
    function treatAppointDisplay($treatValue)
    {
        ?>
        <div class="treat_appoint">

        </div>
        <?php
    }






    // affichage des ordonnances pharma
    function pharmaOrdoDisplay($ordoValue)
    {
        ?>
        <div class="ordo">
            <div>
                <h6><b>Traitements génériques disponibles en pharmacie ou magasin</b></h6>
                Ordonnance n° P<?php echo $ordoValue['treatPharmaOrdoListID'] ?>
                    datée du <?php echo $ordoValue['time']['date']?><br>
            </div>

            <?php
            if ($ordoValue['comment']) {
                ?>
                <br>
                <div>
                    <u>Commentaire de l'ordonnance:</u><br>
                    <p class="cattext"><?php echo nl2br(trim($ordoValue['comment'])) ?></p>
                </div>
                <?php
            }

            foreach ($ordoValue['pharmaPresc'] as $pharmaPrescKey => $pharmaPrescValue) {
                pharmaPrescDisplay($pharmaPrescValue);
            }

            ?>
        </div>
        <?php
    }


    // affichage des prescriptions de traitements pharma
    function pharmaPrescDisplay($ordoValue)
    {
        ?>
        <div class=pharmapresc>
            <div>
                <output><i><?php echo $ordoValue['treatPharmaPrescListID'] .
                '</i> - <b>' . $ordoValue['treatPharmaListName'] ?></b></output>
            </div>
            <div>
                <p><?php echo nl2br(trim($ordoValue['treatPharmaPrescListContent'])) ?></p>
            </div>
            <?php
            if (strlen($ordoValue['treatPharmaPrescListComment']) > 0) {
                ?>
                <div>
                    <u>Commentaire de la prescription:</u>
                    <p><?php echo nl2br(trim($ordoValue['treatPharmaPrescListComment'])) ?></p>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }







    // affichage des ordonnances de lunettes
    function glassOrdoDisplay($ordoValue)
    {
        ?>
        <div class="ordo">
            <div>
                <h6><b>Ordonnance pour lunettes</b></h6>
                Ordonnance n° LU<?php echo $ordoValue['glassOrdoListID'] ?>
                    datée du <?php echo $ordoValue['time']['date']?><br>
            </div>
            <br>

            <div>
                <label for="">Distance pupillaire:</label>
                <output><?php echo $ordoValue['bothEyes']['pupDist']?> mm</output>
            </div>

            <div>
                <label for="">Oeil droit:</label>
                <output><?php echo $ordoValue['rightEye']['sentence']?></output>
            </div>

            <div>
                <label for="">Oeil gauche:</label>
                <output><?php echo $ordoValue['leftEye']['sentence']?></output>
            </div>
            <br>

            <div>
                <?php
                if ($ordoValue['comment']) {
                    ?>
                    <u>Commentaire concernant l'ordonnance:</u>
                    <p class="cattext"><?php echo nl2br(trim($ordoValue['comment'])) ?></p>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }


    // affichage des ordonnances de lentilles
    function lensOrdoDisplay($ordoValue)
    {
        ?>
        <div class="ordo">
            <div>
                <h6><b>Ordonnance pour lentilles</b></h6>
                Ordonnance n° LE<?php echo $ordoValue['lensOrdoListID'] ?>
                    datée du <?php echo $ordoValue['time']['date']?><br>
            </div>
            <br>

            <div>
                Modèle: <?php echo $ordoValue['bothEyes']['model'] ?><br>
                Diamètre: <?php echo roundNumberExtender($ordoValue['bothEyes']['diameter']) ?><br>
                Rayon: <?php echo roundNumberExtender($ordoValue['bothEyes']['radius']) ?><br>
            </div>
            <br>

            <div>
                <label for="">Oeil droit:</label>
                <output><?php echo $ordoValue['rightEye']['sentence']?></output>
                <br>

                <label for="">Oeil gauche:</label>
                <output><?php echo $ordoValue['leftEye']['sentence']?></output>
            </div>

            <div>
                <br>

                <?php
                if ($ordoValue['comment']) {
                    ?>
                    <u>Commentaire concernant l'ordonnance:</u>
                    <p class="cattext"><?php echo nl2br(trim($ordoValue['comment'])) ?></p>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
