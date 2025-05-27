<?php
session_start();
include 'connection.php';
require('fpdf182/fpdf.php');  // Ensure this path is correct

if (!isset($_GET['id'])) {
    die('Report ID is required');
}

$report_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM lab_reports WHERE id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();

if (!$report) {
    die('Report not found');
}

// Extend FPDF to customize PDF
class PDF extends FPDF {
    function Header() {
        // Add hospital logo
        if (file_exists('hospital_logo.png')) {
            $this->Image('hospital_logo.png', 10, 10, 25); // (file, x, y, width)
        }

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'City Diagnostic Center', 0, 1, 'C');

        $this->SetFont('Arial', 'I', 12);
        $this->Cell(0, 10, 'Comprehensive Lab Test Report', 0, 1, 'C');
        $this->Ln(5);
        $this->Line(10, 35, 200, 35); // Horizontal line
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Add report details in a box
$pdf->Ln(10);
$pdf->SetFillColor(230, 230, 250); // Light lavender background
$pdf->SetDrawColor(100, 100, 100); // Gray border

$pdf->Cell(0, 10, 'Patient Details', 1, 1, 'C', true);
$pdf->Cell(40, 10, 'Patient Name:', 1);
$pdf->Cell(0, 10, $report['patient_name'], 1, 1);

$pdf->Cell(40, 10, 'Test Name:', 1);
$pdf->Cell(0, 10, $report['test_name'], 1, 1);

$pdf->Cell(40, 10, 'Report Date:', 1);
$pdf->Cell(0, 10, $report['report_date'], 1, 1);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Test Result', 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, $report['result'], 1);

$pdf->Output('I', 'LabReport_' . $report_id . '.pdf');
?>
