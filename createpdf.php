<?php
include "classes/Database.php";
require('classes/fpdf/fpdf.php');

$database = new Database();
$db = $database->getConnection();
$date = date('Y-m-d');

if(isset($_GET['tafel'])) {
    $tafel = $_GET['tafel'];
    //Haal bestellingen op voor vandaag voor het doorgegeven tafel.
    $sql = "SELECT bestelling.aantal, artikel.naam, artikel.prijs, reserveringinfo.datum, reserveringinfo.tijd
                            FROM ((bestelling INNER JOIN artikel ON bestelling.artikel_id = artikel.artikel_id) INNER JOIN reserveringinfo ON bestelling.reserveringinfo = reserveringinfo.reservering_id)
                            WHERE bestelling.datum = '" . $date . "'  AND bestelling.tafel =" . $tafel;
    $stmt = $db->query($sql);
    $orderdetails = $stmt->fetchAll();
    $i = 1;
    $totaal = 0;
    //Creer lege PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',10);

    foreach ($orderdetails as $orderdetail) {
        if ($i == 1) {
            $itemprijs = $orderdetail['prijs'] * $orderdetail['aantal'];
            $totaal += $itemprijs;
            //Voeg tafel, datum en tijd toe bij eerste toevoeging.
            $pdf->Cell(40,10,'Tafel: '. $tafel);
            $pdf->Ln(5);
            $pdf->Cell(40,10,'Datum: '. $orderdetail['datum']);
            $pdf->Ln(5);
            $pdf->Cell(40,10,'Tijd: '. $orderdetail['tijd']);
            $pdf->Ln();

            //Voeg artikel toe aan pdf
            $pdf->Cell(40, 10, $orderdetail['aantal']. 'x');
            $pdf->Cell(40, 10, $orderdetail['naam']);
            $pdf->Cell(40, 10, $orderdetail['prijs']);
            $pdf->Cell(40, 10, number_format($itemprijs, 2));
            $pdf->Ln(5);

        }else {
            $itemprijs = $orderdetail['prijs'] * $orderdetail['aantal'];
            $totaal += $itemprijs;
            //Voeg artikel toe aan pdf
            $pdf->Cell(40, 10, $orderdetail['aantal']. 'x');
            $pdf->Cell(40, 10, $orderdetail['naam']);
            $pdf->Cell(40, 10, $orderdetail['prijs']);
            $pdf->Cell(40, 10, number_format($itemprijs, 2));
            $pdf->Ln(5);
        }
        $i++;
    }
    $pdf->Cell(40, 10, 'Totaal: ');
    $pdf->Cell(40, 10, '');
    $pdf->Cell(40, 10, '');
    $pdf->Cell(40, 10, number_format($totaal, 2));
    $pdf->Output();
}else{
    header("Location: factuurklant.php");
}
?>