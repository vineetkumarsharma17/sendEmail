<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'smtp.hostinger.com';  // Replace with your SMTP server address
$mail->SMTPAuth = true;
$mail->Username = 'contact@cruxtech.in';  // Replace with your SMTP username
$mail->Password = 'A1B2C3d4@';  // Replace with your SMTP password
$mail->SMTPSecure = 'tls';  // Enable TLS encryption, 'ssl' is also possible
$mail->Port = 587;  // TCP port to connect to
 // get email and subject and body from api
$email = $_POST['email'];
$subject = $_POST['subject'];
$body = $_POST['body'];




$mail->setFrom('contact@cruxtech.in', 'Crux Team');  // Replace with the sender's email address and name
$mail->addAddress($email, );  // Replace with the recipient's email address and name

if(isset($_POST['subject'])){
$mail->Subject = $subject;
}
if(isset($_POST['body'])){
$mail->Body = $body;
}
// add html data 
$mail->isHTML(true);
$tmp_files = array();
// attach multiple files from form data
if (!empty($_FILES)) {
    $fileCount = count($_FILES);
    // echo $fileCount;
    // print_r( $_FILES );
    // echo json_encode(array('status' => 'error', 'message' =>$_FILES['attachment']['name'] ));
    for ($ct = 0; $ct  < $fileCount; $ct++) {
        $targetDir = 'tmp/'.basename($_FILES['attachment'.$ct]['name']);  // Specify the directory where you want to save the files
        $filename = $_FILES['attachment'.$ct]['name'];
        $uploadfile = $targetDir;
        if (move_uploaded_file($_FILES['attachment'.$ct]['tmp_name'], $uploadfile)) {
            $mail->addAttachment($uploadfile, $filename);
            $tmp_files[]=$uploadfile;
            // echo "file attached-".$filename;
            // delete moved file from path
            

           
        } else {
            $msg .= 'Failed to move file to ' . $uploadfile;
            // return error message to api 
            echo json_encode(array('status' => 0, 'message' => $msg));
            break;
        }
    }
}

// check attachment is attached or not in $mail
// echo "\nis attached:". $mail->attachmentExists();





if (!$mail->send()) {
    // echo 'Error: ' . $mail->ErrorInfo;
    // response to api
    echo json_encode(array('status' => 0, 'message' => $mail->ErrorInfo));
} else {
   //clear all files from temp directory

    foreach($tmp_files as $file){ // iterate files
    if(is_file($file))
    unlink($file); // delete file
    }
    // response to api
    echo json_encode(array('status' => 1, 'message' => 'Email sent successfully!'));
}




?>