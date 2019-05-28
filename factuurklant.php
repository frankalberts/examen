<?php
include "header.php";
include "classes/Database.php";
$database = new Database();
$db = $database->getConnection();
?>
<div class="container">

    <div class="row">

        <div class="col-lg-3 menu">

            <h1 class="my-4">Excellent taste</h1>
            <div class="list-group">
                <a href="reserveringen.php" class="list-group-item">Reserveringen</a>
                <a href="overzichtbarman.php" class="list-group-item">Overzicht Barman</a>
                <a href="factuurklant.php" class="list-group-item">Bon voor klant</a>
                <a href="weekoverzicht.php" class="list-group-item">Weekomzet</a>
            </div>

        </div>
        <div class="col-lg-9 content">
            <div class="page-item">
                <h1>Bon voor klant</h1><hr>
                <?php
                $date = date('Y-m-d');
                //Kijk of er gezocht is, zoek daarna alle bestellingen van deze tafel van vandaag.
                if(isset($_POST['zoek'])){
                    $tafel = $_POST['tafel'];
                    $sql = "SELECT bestelling.aantal, artikel.naam, artikel.prijs, reserveringinfo.datum, reserveringinfo.tijd
                            FROM ((bestelling INNER JOIN artikel ON bestelling.artikel_id = artikel.artikel_id) INNER JOIN reserveringinfo ON bestelling.reserveringinfo = reserveringinfo.reservering_id)
                            WHERE bestelling.datum = '". $date . "'  AND bestelling.tafel =". $tafel;
                    $stmt = $db->query($sql);
                    if($stmt->rowCount() == 0){
                        echo "<script>alert('Er zijn geen bestellingen gevonden.'); location='factuurklant.php';</script>";
                        return;
                    }
                    $orderdetails = $stmt->fetchAll();
                    $i = 1;
                    $totaal = 0;
                    //Print voor elk van de bestellingen een rij op de bon
                    foreach ($orderdetails as $orderdetail){
                        if ($i == 1) {
                            $itemprijs = $orderdetail['prijs'] * $orderdetail['aantal'];
                            $totaal += $itemprijs;
                            //Print de eerste keer Tafel, Datum en tijd
                            echo "Tafel: ". $tafel .
                                 "<br>Datum: ". $orderdetail['datum'] .
                                 "<br>Tijd: " . $orderdetail['tijd'].
                                 "<br><table class='table table-striped'><tr><td class='center'>". $orderdetail['aantal'] ."x </td>".
                                 "<td class='center'>". $orderdetail['naam']. " </td>".
                                 "<td class='center'>". $orderdetail['prijs']. " </td>".
                                "<td class='center'>". number_format($itemprijs, 2) . " </td></tr>";
                        }else{
                            $itemprijs = $orderdetail['prijs'] * $orderdetail['aantal'];
                            $totaal += $itemprijs;
                            echo "<br><tr><td>". $orderdetail['aantal'] ."x </td>".
                                 "<td class='center'>". $orderdetail['naam']. " </td>".
                                 "<td class='center'>". $orderdetail['prijs']. " </td>".
                                "<td class='center'>". number_format($itemprijs, 2) . " </td></tr>";
                        }
                        $i++;
                    }
                    echo "</table>
 <div class=\"row\">
                <div class=\"col-lg-4 col-sm-5\">

                </div>

                <div class=\"col-lg-4 col-sm-5 ml-auto\">
                    <table class=\"table table-clear\" id=\"exportTable\">
                        <tbody>
                        <tr>
                            <td class=\"left\">
                                <strong>Totaal</strong>
                            </td>
                            <td class=\"right\">&euro;". number_format($totaal, 2) ."</td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                        </div>
                        <a href='createpdf.php?tafel=". $tafel ."'>
                        <button id=\"exportButton\"  class=\"btn btn-lg btn-danger clearfix\"><span class=\"fa fa-file-pdf-o\"></span> Print deze bon.</button>
</a>";


                }else {
                    echo"<form method='post'>
                           <input type=number placeholder='Vul tafelnummer in.' name='tafel'>
                           <button type='submit' name='zoek'>Zoek</button>
                          </form>";
                }
                ?>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->
