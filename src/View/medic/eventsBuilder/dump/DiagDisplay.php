<?php


function diagAppointDisplay($diagValue)
{
    //var_dump($diagValue);

    $elemListString = diagElementListBuilder($diagValue['elements']); // création des <li> pour l'affichage des élements de diagnostic
    $impactListString = diagSickStatusListBuilder($diagValue['sickStatus']); // création des <li> pour l'affichage des impacts du diagnostic

    ?>
    <div class="diag">
        <div class="diag_titles">
            <div class="diag_health_pb">
                <?php echo $diagValue['healthPb']['name']?>
            </div><!-- End of class="health_pb" -->
            <div class="diag_cat">
                <?php echo $diagValue['category'][0]['medicAppointDiagCatName']?>
            </div><!-- End of class="diag_cat" -->
        </div><!-- End of class="diag_titles" -->

        <?php
        if (strlen($elemListString) > 0) {
            ?>
            <br>
            <div class="diag_elems">
                <h6>Elements de diagnostic:</h6>
                <ul>
                    <?php echo $elemListString; ?>
                </ul>
            </div><!-- End of class="diag_elems" -->
            <?php
        }

        if (strlen($impactListString) > 0) {
            ?>
            <br>
            <div class="diag_impacts">
                <h6>Résultat du diagnostic:</h6>
                <ul>
                    <?php echo $impactListString; ?>
                </ul>
            </div><!-- End of class="diag_impacts" -->
            <?php
        }

        if (strlen($diagValue['comment']) > 0) {
            ?>
            <br>
            <div class="diag_comment">
                <h6>Commentaires:</h6>
                <p><?php echo $diagValue['comment']; ?></p>
            </div><!-- End of class="diag_comment" -->
            <?php
        }
        ?>

        </div><!-- End of class="diag" -->
    <?php
}


// création des <li> pour l'affichage des élements de diagnostic
function diagElementListBuilder($elementsArray)
{
    $elemListString = '';
    $elemListArray = array();

    foreach ($elementsArray as $elemKey => $elemValue) {
        $temp = '<li>' .$elemValue['medicAppointDiagElemName']. '</li>';
        array_push($elemListArray, $temp);
    }

    $elemListString = implode('', $elemListArray);
    return $elemListString;
}


// création des <li> pour l'affichage des impacts du diagnostic
function diagSickStatusListBuilder($impactsArray)
{
    $impactListString = '';
    $impactListArray = array();

    foreach ($impactsArray as $impactKey => $impactValue) {
        $diseaseStatus = '';

        switch ($impactValue['diseaseSickStatusType']) {
            case 'toBeChecked':
                $diseaseStatus = 'Contrôle à faire pour ';
                break;
            case 'suspected':
                $diseaseStatus = 'Suspicion de ';
                break;
            case 'refuted':
                $diseaseStatus = 'Discrimination de ';
                break;
            case 'confirmed':
                $diseaseStatus = 'Confirmation de ';
                break;
            case 'sickMonitor':
                $diseaseStatus = 'Toujours affecté mais suivi pour ';
                break;
            case 'healed':
                $diseaseStatus = 'Guéri de ';
                break;
            case 'healedMonitor':
                $diseaseStatus = 'Plus affecté mais suivi pour ';
                break;
        }

        $tempString =
            '<li>' .$diseaseStatus.
            '<span class="impact_disease_name">'.$impactValue['diseaseListName'].'</span></li>';

        array_push($impactListArray, $tempString);
    }

    $impactListString = implode('', $impactListArray);
    return $impactListString;
}