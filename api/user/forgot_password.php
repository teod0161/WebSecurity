<?PHP
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
require '../../vendor/autoload.php';

require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/phpmailer/phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_POST["email"]) {

    $email = $_POST["email"];

    //making sure the email exists and is valid
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        header("INVALID EMAIL ADDRESS", true, 400);
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT username FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        $result = $stmt->fetchAll();
        if (count($result) > 0) {
            foreach ($result as $user) {
                $emailForgotPasswordTemplateContent = file_get_contents( '../email-templates/forgot-password.html');
                $newPassword = uniqid();
                $options = [
                    'cost' => 12
                ];
                $password_hash = password_hash($newPassword, PASSWORD_BCRYPT, $options);
                try {
                    $stmt = $conn->prepare("UPDATE users SET password=:password_hash, forgot_password=1 WHERE email=:email;");

                    $stmt->bindParam(':password_hash', $password_hash);
                    $stmt->bindParam(':email', $email);

                    $stmt->execute();

                    $emailForgotPasswordTemplateContent = str_replace('{{password}}', $newPassword, $emailForgotPasswordTemplateContent);

                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->Host = 'smtp.sendgrid.net';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'apikey';
                    $mail->Password = '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';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;

                    $mail->setFrom('noreply@article-kea.com', 'ArticleKEA');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Forgot Password - ArticleKEA';
                    $mail->Body = $emailForgotPasswordTemplateContent;

                    $mail->send();
                    exit();
                } catch (Exception $e) {
                    header("FAILED OPERATION", false, 500);
                    exit();
                }

            }
        }
        header("COULD NOT FIND USER WITH THAT EMAIL", true, 400);
        exit();
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO REQUEST A PASSWORD RECOVERY", true, 400);
    exit();
}

//basics for stuff
