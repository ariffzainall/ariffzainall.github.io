<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "air_guardian";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['mq135_value'])) {

    $mq135_value = $_GET['mq135_value'];
    $mq5_value = $mq135_value * 0.2;
    $mq6_value = $mq135_value * 0.3;
    $mq7_value = $mq135_value * 0.5;

    $sql = "INSERT INTO gas_read (mq5_value, mq6_value, mq7_value, mq135_value) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $mq5_value, $mq6_value, $mq7_value, $mq135_value);

    if ($stmt->execute()) {
        echo "Data inserted successfully";

        $aqi = ($mq5_value + $mq6_value + $mq7_value + $mq135_value) / 4;

        if ($aqi <= 50) {
            $airType = 'Good';
            $action = 'Go Outside';
        } elseif ($aqi <= 100) {
            $airType = 'Intermediate';
            $action = 'Wear Mask';
        } else {
            $airType = 'Danger';
            $action = 'Stay Inside!';
        }

        $date = date('Y-m-d');
        $reportSql = "INSERT INTO reports (`AQI Reading`, `Air Type`, `Action To Be Taken`, `Date`) VALUES (?, ?, ?, ?)";
        $reportStmt = $conn->prepare($reportSql);
        $reportStmt->bind_param("isss", $aqi, $airType, $action, $date);

        if ($reportStmt->execute()) {
            echo "Report inserted successfully";
        } else {
            echo "Error: " . $reportSql . "<br>" . $conn->error;
        }

        if ($aqi > 100) {
            $emailQueryAdmin = "SELECT email FROM admin";
            $emailResultAdmin = $conn->query($emailQueryAdmin);

            $emailQueryStudent = "SELECT email FROM student";
            $emailResultStudent = $conn->query($emailQueryStudent);

            if ($emailResultAdmin->num_rows > 0 || $emailResultStudent->num_rows > 0) {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'PengetrolWargaGM2023@gmail.com';
                $mail->Password = 'tusvafbilbguckax';
                $mail->Port = 465;
                $mail->SMTPSecure = 'ssl';

                while ($adminEmailRow = $emailResultAdmin->fetch_assoc()) {
                    $recipientEmailAdmin = $adminEmailRow['email'];

                    $mail->setFrom('PengetrolWargaGM2023@gmail.com', 'Admin');
                    $mail->addAddress($recipientEmailAdmin);
                    $mail->Subject = "Air Pollution Detected!!!";
                    $mail->isHTML(true);
                    $mail->Body = "Polluted gas detected. AQI Reading: $aqi. Please take precaution.";

                    try {
                        $mail->send();
                        echo "Email sent successfully to admin: " . $recipientEmailAdmin . "<br>";
                    } catch (Exception $e) {
                        echo "Error sending email to admin " . $recipientEmailAdmin . ": " . $mail->ErrorInfo . "<br>";
                    }
                }

                while ($studentEmailRow = $emailResultStudent->fetch_assoc()) {
                    $recipientEmailStudent = $studentEmailRow['email'];

                    $mail->setFrom('PengetrolWargaGM2023@gmail.com', 'Admin');
                    $mail->addAddress($recipientEmailStudent);
                    $mail->Subject = "Air Pollution Detected!!!";
                    $mail->isHTML(true);
                    $mail->Body = "Polluted gas detected. AQI Reading: $aqi. Please take precaution.";

                    try {
                        $mail->send();
                        echo "Email sent successfully to student: " . $recipientEmailStudent . "<br>";
                    } catch (Exception $e) {
                        echo "Error sending email to student " . $recipientEmailStudent . ": " . $mail->ErrorInfo . "<br>";
                    }
                }
            } else {
                echo "No email addresses found in the user table.";
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "No gas data received";
}

$conn->close();
?>
