<?php
include "header.php";
include "classes/Database.php";
$database = new Database();
$db = $database->getConnection();

//Haal ID op als die geset is, en vul daarmee de klantinfo. Zo niet, worden de placeholders leeg gemaakt.
if(isset($_GET['ID'])){
    if ($_GET['ID']) {
        $id = $_GET['ID'];
        $sql = "SELECT reservering.reservering_id, reserveringinfo.datum, reserveringinfo.tijd, reserveringinfo.tafel, klant.naam, klant.telefoon, klant.straat, klant.postcode, klant.plaats, klant.land, reservering.aantal, reservering.gebruikt, reservering.allergie, reservering.opmerkingen
        FROM ((reservering INNER JOIN reserveringinfo ON reservering.reservering_id = reserveringinfo.reservering_id)
        INNER JOIN klant ON reservering.klant_id = klant.klant_ID)
        WHERE reservering.reservering_id = " . $id;
        $stmt4 = $db->query($sql);
        $klantinfo = $stmt4->fetch(PDO::FETCH_ASSOC);
    }
}else{
    $klantinfo['naam'] = "";
    $klantinfo['telefoon'] = "";
    $klantinfo['straat'] = "";
    $klantinfo['postcode'] = "";
    $klantinfo['plaats'] = "";
    $klantinfo['land'] = "";

    $klantinfo['datum'] = "";
    $klantinfo['tijd'] = "";
    $klantinfo['tafel'] = "";
    $klantinfo['aantal'] = "";
    $klantinfo['allergie'] = "";
    $klantinfo['opmerkingen'] = "";
}
if(isset($_POST['reservatie'])){
    //Variabelen voor de klant
    $naam = $_POST['naam'];
    $number = $_POST['nummer'];
    $straat = $_POST['straat'];
    $postcode = $_POST['postcode'];
    $plaats = $_POST['plaats'];
    $land = $_POST['land'];

    $datum = $_POST['datum'];
    $tijd = $_POST['tijd'];
    $tafel = $_POST['tafel'];
    $aantal = $_POST['aantal'];
    $allergie = $_POST['allergie'];
    $opmerkingen = $_POST['opmerkingen'];
    $gebruikt = 0;
    $deleteafterupdate = 0;
    $todeleteid;
    if(isset($_GET['ID'])){
        $deleteafterupdate = 1;
        $todeleteid = $_GET['ID'];
    }else
    {
        $checkforreservering = "SELECT * FROM reserveringinfo WHERE datum = '". $datum . "' AND tijd = '" . $tijd . "' AND tafel = '" . $tafel . "'";
        $stmt = $db->query($checkforreservering);
        if($stmt->rowCount() != 0){
            echo "<script>alert('De reservering op deze tijd, datum en tafel is niet mogelijk. Er is al een reservering aanwezig voor de ingevulde tijd, datum en tafel.'); location='reserveringen.php';</script>";
            return;
        }
    }
    if(isset($_POST['gebruikt'])){
        $gebruikt = 1;
    }
    $sql = "SELECT * FROM klant WHERE telefoon = " . $number;
    $stmt = $db->query($sql);
    $klant = $stmt->fetch();
    //Als de klant bestaat gebruik de opgevraagde info.
    if($klant){
        //Kijk of de klant de reservering vaker heeft gebruikt of niet. Geef een Alert als vaker dan 1 keer niet heeft gebruikt.
        $sql = "SELECT sum(gebruikt = 1) as yes, sum(gebruikt = 0) as no FROM reservering WHERE klant_id =" . $klant['klant_ID'];
        $stmt = $db->query($sql);
        $result = $stmt->fetch();
        if($result['no'] > 0){
            echo "<script>alert('Deze klant heeft ".$result['no']." keer de reservering niet gebruikt.')</script>";
        }
        try {
            $stmt1 = $db->prepare("REPLACE into `reserveringinfo` (datum, tijd, tafel) VALUES ('$datum', '$tijd', '$tafel')");
            $result = $stmt1->execute();
            $reserveringid = $db->lastInsertId();
            $klantid = $klant['klant_ID'];

            // Zet de reservering in de reservering.
            $stmt2 = $db->prepare("REPLACE into `reservering` (reservering_id, klant_id, aantal, gebruikt, allergie, opmerkingen) VALUES ('$reserveringid', '$klantid', '$aantal', '$gebruikt', '$allergie', '$opmerkingen')");
            $stmt2->execute();
            if($deleteafterupdate){
                //Als de reservering al bestaat, verwijder deze dan als deze aangepast wordt.
                $deletesql = "DELETE FROM reservering WHERE reservering_id = '". $todeleteid ."';
                              DELETE FROM reserveringinfo WHERE reservering_id = '". $todeleteid ."'";
                $stmt5 = $db->query($deletesql);
            }
        }catch (PDOException $e){

        }
    }else{
        //Anders zet de info in de klant tabel en maak de reservering.
        $stmt2 = $db->prepare("INSERT into `klant` (naam, telefoon, straat, postcode, plaats, land) VALUES ('$naam', '$number', '$straat', '$postcode', '$plaats', '$land')");
        $result2 = $stmt2->execute();
        $klantid = $db->lastInsertId();
        $stmt3 = $db->prepare("REPLACE into `reserveringinfo` (datum, tijd, tafel) VALUES ('$datum','$tijd', '$tafel') ");
        $result = $stmt3->execute();
        $reserveringid = $db->lastInsertId();
        $stmt4 = $db->prepare("REPLACE into `reservering` (reservering_id, klant_id, aantal, gebruikt, allergie, opmerkingen) VALUES ('$reserveringid', '$klantid', '$aantal', '$gebruikt', '$allergie', '$opmerkingen')");
        $stmt4->execute();
        if($deleteafterupdate){
            $deletesql = "DELETE FROM reservering WHERE reservering_id = '". $todeleteid ."';
                              DELETE FROM reserveringinfo WHERE reservering_id = '". $todeleteid ."'";
            $stmt5 = $db->query($deletesql);
        }
    }
    //Probeer reservering te inserten
    echo "<script>location='reserveringen.php'</script>";
}
?>
<div class="container">
<div class="col-md-12">
    <h4 class="mb-3">Reservering</h4>
    <form class="needs-validation" method="post" novalidate>
        <div class="mb-3">
            <label for="naam">Naam</label>
            <div class="input-group">
                <input required type="text" class="form-control" id="naam" placeholder="Naam" name="naam" value="<?php echo $klantinfo['naam'] ?>" required>
                <div class="invalid-feedback" style="width: 100%;">
                    Your username is required.
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="nummer">Telefoon</label>
            <input required type="text" class="form-control" id="nummer" placeholder="0612345678" name="nummer" value="<?php echo $klantinfo['telefoon'] ?>">
            <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
            </div>
        </div>

        <div class="mb-3">
            <label for="straat">Straatnaam met Huisnummer en toevoeging.</label>
            <input required type="text" class="form-control" id="straat" placeholder="Hammerstraat 123a" name="straat" value="<?php echo $klantinfo['straat'] ?>">
            <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
            </div>
        </div>

        <div class="row">
        <div class="col-md-3 mb-3">
            <label for="postcode">Postcode</label>
            <input required type="text" class="form-control" id="postcode" placeholder="1234AB" name="postcode" value="<?php echo $klantinfo['postcode'] ?>">
            <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
            </div>
        </div>

        <div class="col-md-5 mb-3">
            <label for="nummer">Woonplaats</label>
            <input required type="text" class="form-control" id="plaats" placeholder="Hardenberg" name="plaats" value="<?php echo $klantinfo['plaats'] ?>">
            <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
            </div>
        </div>
        </div>
        <div class="mb-3">
            <label for="nummer">Land</label>
            <input required type="email" class="form-control" id="land" placeholder="Nederland" name="land" value="<?php echo $klantinfo['land'] ?>">
            <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
            </div>
        </div>
        <div class="mb-3">
            <label for="datum">Datum (YYYY-MM-DD)</label>
            <input required type="text" class="form-control" id="datum" placeholder="2019-09-17" name="datum" value="<?php echo $klantinfo['datum'] ?>" required>
            <div class="invalid-feedback">
                Please enter your shipping address.
            </div>
        </div>

        <div class="mb-3">
            <label for="tijd">Tijd (HH:MM:SS)</label>
            <input required type="text" class="form-control" id="tijd" name="tijd" placeholder="18:00:00" value="<?php echo $klantinfo['tijd'] ?>">
        </div>

        <div class="mb-3">
            <label for="tafel">Tafel</label>
            <input required type="text" class="form-control" id="tafel" name="tafel" value="<?php echo $klantinfo['tafel'] ?>">
        </div>

        <div class="mb-3">
            <label for="aantal">Aantal</label>
            <input required type="text" class="form-control" id="aantal" name="aantal" value="<?php echo $klantinfo['aantal'] ?>">
        </div>
        <div class="mb-3">
            <label for="allergie">Eventuele allergieen</label>
            <input required type="text" class="form-control" id="allergie" name="allergie" value="<?php echo $klantinfo['allergie'] ?>">
        </div>
        <div class="mb-3">
            <label for="opmerking">Overige opmerkingen </label>
            <input required type="text" class="form-control" id="opmerkingen" name="opmerkingen" value="<?php echo $klantinfo['opmerkingen'] ?>">
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="gebruikt" name="gebruikt" value="0">
            <label class="custom-control-label" for="gebruikt">De klant heeft de reservatie gebruikt</label>
        </div>
        <hr class="mb-4">

        <button class="btn btn-primary btn-lg btn-block" type="submit" name="reservatie">Plaats reservatie</button>
        <br>
    </form>
</div>
</div>
