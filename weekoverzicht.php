<?php
include "header.php";
include "classes/Database.php";
$database = new Database();
$db = $database->getConnection();
?>
<div class="container">

    <div class="row">

        <div class="col-lg-3 menu">

            <h1 class="my-4 ">Excellent Taste</h1>
            <div class="list-group">
                <a href="reserveringen.php" class="list-group-item">Reserveringen</a>
                <a href="overzichtbarman.php" class="list-group-item">Overzicht Barman</a>
                <a href="factuurklant.php" class="list-group-item">Bon voor klant</a>
                <a href="weekoverzicht.php" class="list-group-item">Weekomzet</a>
            </div>

        </div>
        <div class="col-lg-9 content">
            <div class="page-item ">
                <h1>Home Excellent taste</h1><hr><br>
                <?php
                if(isset($_POST['zoek'])) {
                    $begin = $_POST['begin'];
                    $eind = $_POST['eind'];
                    $sql = "SELECT bestelling.aantal, artikel.prijs
                            FROM bestelling INNER JOIN artikel ON bestelling.artikel_id = artikel.artikel_id
                            WHERE bestelling.datum BETWEEN '". $begin . "' AND '" . $eind . "'";
                    $stmt = $db->query($sql);
                    if($stmt->rowCount() == 0){
                        echo "<script>alert('Er zijn geen bestellingen gevonden tussen deze twee datums'); location='weekoverzicht.php';</script>";
                        return;
                    }
                    $bestellings = $stmt->fetchAll();
                    $totaal = 0;
                    foreach ($bestellings as $bestelling){
                        $itemprijs = $bestelling['prijs'] * $bestelling['aantal'];
                        $totaal += $itemprijs;
                    }
                    echo "De totale omzet tussen " . $begin ." en ". $eind ." is: " . number_format($totaal, 2);
                }else {
                    echo"<form method='post'>
                           <label for=\"datum\">Datum (YYYY-MM-DD)</label><br>
                           <input type='text' placeholder='Vul begindatum in.' name='begin'><br>
                           <label for=\"datum\">Datum (YYYY-MM-DD)</label><br>
                           <input type='text' placeholder='Vul einddatum in.' name='eind'><br><br>
                           <button type='submit' name='zoek'>Zoek</button>
                         </form>";
                }
                ?>
            </div>

        </div>

    </div>

</div>
