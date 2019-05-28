<?php
include 'header.php';
include "classes/Database.php";
$database = new Database();
$db = $database->getConnection();

$date = date('Y-m-d');
//Haal alle bestellingen van vandaag op en groepeer deze per tafel
$sql = "SELECT bestelling.tafel
        FROM ((bestelling INNER JOIN artikel ON bestelling.artikel_id = artikel.artikel_id) 
        INNER JOIN subgerecht ON artikel.subgerecht = subgerecht.subgerechtid) 
        WHERE bestelling.datum = '$date' AND subgerecht.subgerecht = 'drankjes'
        GROUP BY bestelling.tafel
        ORDER BY bestelling.tijd ASC";
$stmt = $db->query($sql);
$todaysorders = $stmt->fetchAll();

?>
<br>
<div class="container">
    <div class="row">

        <div class="col-lg-3 menu">

            <h1 class="my-4">Excellent Taste</h1>
            <div class="list-group">
                <a href="reserveringen.php" class="list-group-item">Reserveringen</a>
                <a href="overzichtbarman.php" class="list-group-item">Overzicht Barman</a>
                <a href="factuurklant.php" class="list-group-item">Bon voor klant</a>
            </div>

        </div>
        <div class="col-lg-9 content">
            <div class="page-item">
<div class='card' >
    <div class='card-header' >
        Bestellingen drankjes
    </div >
    <div class='card-body' >
        <div class='table-responsive-sm' >
            <table class='table table-striped' >
                <thead >
                <tr >
                    <th class='center' >Tafel </th >
                    <th class='center' >Aantal</th >
                    <th class='right'>Drankje</th>
                </tr >
                </thead >
                <tbody>
                <?php
                foreach ($todaysorders as $todaysorder) {
                    $bestellingtafel = $todaysorder['tafel'];
                    // Haal de bestelling aantal en de naam op
                    $sql = "SELECT bestelling.aantal, artikel.naam FROM ((bestelling INNER JOIN artikel ON bestelling.artikel_id = artikel.artikel_id) INNER JOIN subgerecht ON artikel.subgerecht = subgerecht.subgerechtid) WHERE bestelling.tafel = '$bestellingtafel' AND bestelling.datum = '$date' AND subgerecht.subgerecht = 'drankjes'";
                    $stmt = $db->query($sql);
                    $orderdetails = $stmt->fetchAll();

                    $i = 1;
                    foreach ($orderdetails as $orderdetail) {
                        if ($i == 1) {
                            //:Laat de eerste keer de tafel waarbij de bestelling hoort zien.
                            echo "<tr>
                                  <td class='center'>" . $todaysorder['tafel'] . "</td>
                                  <td class='center'>". $orderdetail['aantal'] ."</td>
                                  <td class='center'>". $orderdetail['naam'] ."</td></tr>";
                        }else{
                            echo "<tr><td></td>
                                  <td class='center'>". $orderdetail['aantal'] ."</td>
                                  <td class='center'>". $orderdetail['naam'] ."</td></tr>";
                        }
                        $i++;
                    }
                }
                ?>
                </tbody>
                </table>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</div>
