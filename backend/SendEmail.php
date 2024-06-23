<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$jsonData = file_get_contents("php://input");
$requestDataObject = json_decode($jsonData);

$name = htmlspecialchars($requestDataObject->name);
$email = htmlspecialchars($requestDataObject->email);
$phone = htmlspecialchars($requestDataObject->phone);
$date = htmlspecialchars($requestDataObject->date);
$experience = htmlspecialchars($requestDataObject->experience);
$improvements = htmlspecialchars($requestDataObject->improvements);

$adminEmailSent = sendAdminEmail($requestDataObject);

$response = array();
if ($adminEmailSent) {
    $response['status'] = 'success';
    $response['message'] = 'Email sent successfully.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'There was an issue sending the email.';
}

echo json_encode($response);

function sendAdminEmail($data)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hamza786504@gmail.com';
        $mail->Password = 'cpei mgrg gkvb kskt';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('hamza786504@gmail.com', 'Hamza');
        $mail->addAddress('khuzaima786504@gmail.com', 'Khuzaima');

        $mail->isHTML(true);
        $mail->Subject = 'New Feedback Submission';
        $mail->Body = '<p>New feedback details:</p>' .
            '<table border="1" cellpadding="5" cellspacing="0">' .
            '<tr><td>Name:</td><td>' . $data->name . '</td></tr>' .
            '<tr><td>Email:</td><td>' . $data->email . '</td></tr>' .
            '<tr><td>Phone:</td><td>' . $data->phone . '</td></tr>' .
            '<tr><td>Date of Service:</td><td>' . $data->date . '</td></tr>' .
            '<tr><td>Experience:</td><td>' . $data->experience . '</td></tr>' .
            '<tr><td>Improvements:</td><td>' . $data->improvements . '</td></tr>' .
            '</table>';

        if (!empty($data->improvements)) {
            $mail->Body .= '<p>Thanks for sharing your experience and suggestions for improvements.</p>';
        } else {
            $mail->Body .= '<p>Thanks for sharing your experience.</p>';
        }

        $mail->Body .= '<p>Regards,<br>Plus Plumbing</p>';


        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
