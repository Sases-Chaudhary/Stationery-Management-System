<?php
require('fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Sales Report', 0, 1, 'C'); // Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 10, 'Product Name', 1, 0, 'C');
        $this->Cell(30, 10, 'Product Quantity', 1, 0, 'C');
        $this->Cell(30, 10, 'Product Rate', 1, 0, 'C');
        $this->Cell(30, 10, 'Date', 1, 0, 'C');
        $this->Ln();
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Create a PDF object
$pdf = new PDF();
$pdf->AddPage();

// Include your database connection file
include 'includes/connection.php';

// Fetch data from the form
$fromDate = isset($_GET['from']) ? $_GET['from'] : '';
$toDate = isset($_GET['to']) ? $_GET['to'] : '';

// Fetch data from the database based on the date range
$query = "SELECT * FROM purchase_list WHERE purchase_date BETWEEN '$fromDate' AND '$toDate'";
$result = mysqli_query($con, $query);

// Check if there are rows
if (mysqli_num_rows($result) > 0) {
    // Add data to the PDF
    $pdf->SetFont('Arial', '', 10);
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(40, 10, $row['product_name'], 1, 0);
        $pdf->Cell(30, 10, $row['product_quantity'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['product_rate'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['purchase_date'], 1, 0, 'C');
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No records found for the specified date range', 0, 1);
}

// Output the PDF to the browser
$pdf->Output('sales_report.pdf', 'D');
?>
