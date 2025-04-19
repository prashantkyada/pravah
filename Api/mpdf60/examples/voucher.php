<?php
include("../mpdf.php");
include("../../../include/conf.php");

// Add PHPMailer (ensure it's installed via Composer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Path to Composer autoload

$result = mysqli_query($mysqli, "SELECT ord.*, us.name, us.mobile_no, us.transport_name, us.gst_no 
                                FROM `tbl_order` ord, `tbl_user` us 
                                WHERE us.id=ord.user_id AND ord.id='".$_GET['id']."'");

if(mysqli_num_rows($result) > 0) {    
    $row = mysqli_fetch_array($result);
    $date = date("d-m-Y");

    // ... [Your existing HTML/CSS code] ...

    ob_clean();
    
    // Generate PDF
    $mpdf = new mPDF();
    $mpdf->WriteHTML($html);
    
    // Save PDF to folder
    $pdfFolder = __DIR__ . '/generated_pdfs/'; // Create this folder first
    $pdfFileName = 'order_'.$row["order_id"].'_'.time().'.pdf';
    $pdfPath = $pdfFolder . $pdfFileName;
    $mpdf->Output($pdfPath, 'F'); // 'F' saves to file

    // Send email with PDF attachment
    $mail = new PHPMailer(true);
    try {
        // Configure SMTP (Update with your credentials)
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your@email.com';
        $mail->Password   = 'your-password';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply@yourdomain.com', 'Pravah Foot Valve');
        $mail->addAddress($row["email"]); // Add recipient email from DB

        // Attach PDF
        $mail->addAttachment($pdfPath, 'Order_'.$row["order_id"].'.pdf');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Order Details - '.$row["order_id"];
        $mail->Body    = 'Attached is your order confirmation.';
        
        $mail->send();
        echo 'PDF generated and email sent!';
        
        // Optional: Delete the PDF after sending
        // unlink($pdfPath);
        
    } catch (Exception $e) {
        echo "Email failed: {$mail->ErrorInfo}";
    }

    exit;
}
?>

?>