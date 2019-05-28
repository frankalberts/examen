<?php
include 'header.php';
include "classes/Database.php";
$database = new Database();
$db = $database->getConnection();
// Haal alle informatie die geshowd moet worden op van reservatie.
$sql = "SELECT reservering.reservering_id, reserveringinfo.datum, reserveringinfo.tijd, reserveringinfo.tafel, klant.naam, klant.telefoon, reservering.aantal, reservering.gebruikt
        FROM ((reservering INNER JOIN reserveringinfo ON reservering.reservering_id = reserveringinfo.reservering_id)
        INNER JOIN klant ON reservering.klant_id = klant.klant_ID)";

$stmt = $db->query($sql);
$reservaties = $stmt->fetchAll();

//Haal de ID van de te verwijderen reserveringen op en verwijder de reservering en de reservatieinfo met deze ID.
if(isset($_GET['actie'])){
    switch ($_GET['actie']){
        case "remove":
            if($_GET['ID']){
                $todeleteid = $_GET['ID'];
                $deletesql = "DELETE FROM reservering WHERE reservering_id = '". $todeleteid ."';
                              DELETE FROM reserveringinfo WHERE reservering_id = '". $todeleteid ."'";
                $stmt = $db->query($deletesql);

            }
            header("Location: reserveringen.php");
            break;
    }
}



?>
<div class="container content">

<legend > Reservaties Excellent Taste</legend >
<hr >
    <a href="editreservering.php"><button type = 'button' class='btn btn-success'>
            <span class='glyphicon glyphicon-new' ></span > Nieuwe reservering
        </button ></a>
    <br>
<br >
<div class='card' >
    <div class='card-header' >
        Reservaties Excellent Taste
    </div >
    <div class='card-body' >
        <div class='table-responsive-sm' >
            <table class='table table-striped' >
                <thead >
                <tr >
                    <th class='center' >Datum </th >
                    <th class='center'>Tijd</th >
                    <th class='right'>Tafel</th>

                    <th class='right'>Naam</th>
                    <th class='right'>Telefoon</th>
                    <th class='right'>Aantal personen</th>
                    <th class='right'>Reservering gebruikt</th>
                    <th class='right'>Wijzigen</th>
                    <th class='right'>Verwijderen</th>

                </tr >
                </thead >
                <tbody>
                <?php
                // Zet Ja of Nee neer in plaats van 0 of 1
                foreach ($reservaties as $reservatie){
                    if ($reservatie['gebruikt'] == 0) {
                        $gebruikt = "Nee";
                    }else{
                        $gebruikt = "Ja";
                    }
                    echo "<tr >
                            <td class='center'>". $reservatie['datum']."</td >
                            <td class='center'>". $reservatie['tijd']."</td >
                            <td class='right'>". $reservatie['tafel']."</td>
                            <td class='right'>". $reservatie['naam']."</td>
                            <td class='right'>". $reservatie['telefoon']."</td>
                            <td class='right'>". $reservatie['aantal']."</td>
                            <td class='right'>". $gebruikt ."</td>
                            <td class='right'><a href='editreservering.php?ID=". $reservatie['reservering_id'] ."'>
                            <button type = 'button' class='btn btn-warning'>
                            <span class='glyphicon glyphicon-edit' ></span > Aanpassen
                                </button >
                            </a></td>
                            <td class='right'><a href='reserveringen.php?actie=remove&ID=" . $reservatie['reservering_id'] . "'><button type = 'button' class='btn btn-danger'>
                            <span class='glyphicon glyphicon-remove' ></span > Verwijder
                                </button ></a> </td>
                            ";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>