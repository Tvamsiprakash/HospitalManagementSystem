<?php
session_start();
include 'connection.php';
require('fpdf182/fpdf.php');

if (!isset($_GET['id'])) {
    die('Report ID is required');
}

$report_id = intval($_GET['id']);

// Fetch the report from the DB
$stmt = $conn->prepare("SELECT * FROM lab_reports WHERE id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();

if (!$report) {
    die('Report not found');
}

// Create PDF
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'Lab Report',0,1,'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','',12);
$pdf->Cell(40,10, 'Patient Name: ', 0, 0);
$pdf->Cell(0,10, $report['patient_name'], 0, 1);

$pdf->Cell(40,10, 'Test Name: ', 0, 0);
$pdf->Cell(0,10, $report['test_name'], 0, 1);

$pdf->Cell(40,10, 'Report Date: ', 0, 0);
$pdf->Cell(0,10, $report['report_date'], 0, 1);

$pdf->Ln(5);
$pdf->MultiCell(0,10, "Result:\n" . $report['result']);

$pdf->Output('I', 'LabReport_' . $report_id . '.pdf');
?>
