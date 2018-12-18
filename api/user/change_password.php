<?PHP
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
include "../../jwt.php";
include "../../protected_session.php";

if ($_POST["newPassword"] && $_POST["confirmPassword"] && $_POST["currentPassword"] && $_POST["AUTH_TOKEN"]) {

    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];
    $currentPassword = $_POST["currentPassword"];
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;
 
    //performing test for the current passowrd, making sure it is valid
    if (strlen($currentPassword) < 6 || !preg_match('@[A-Z]@', $currentPassword) || preg_match('@[0-9]@', $currentPassword)) {
        header("INVALID PASSWORD", true, 400);
        exit();
    }
    //performing test for the new passowrd, making sure it is valid
    if (strlen($newPassword) < 6 || !preg_match('@[A-Z]@', $newPassword) || preg_match('@[0-9]@', $newPassword)) {
        header("INVALID PASSWORD", true, 400);
        exit();
    }
    //performing test for the new passowrd's confirmed value, making sure it is valid
    if (strlen($confirmPassword) < 6 || !preg_match('@[A-Z]@', $confirmPassword) || preg_match('@[0-9]@', $confirmPassword)) {
        header("INVALID PASSWORD", true, 400);
        exit();
    }
    //making sure the new password was written the same both instances
    if ($newPassword !== $confirmPassword) {
        header("PASSWORDS DO NOT MATCH", true, 400);
        exit();
    }

    try {
        //prepared statement to get the current password of the user
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        $result = $stmt->fetchAll();
        foreach ($result as $user) {
            if (!password_verify($currentPassword, $user['password'])) {
                header("WRONG PASSWORD", true, 400);
                exit();
            }
        }

        if ($newPassword == $confirmPassword) {
            $options = [
                'cost' => 12
            ];
            $password_hash = password_hash($newPassword, PASSWORD_BCRYPT, $options);
            try {
                //prepared statement to update the password with the new value
                $stmt = $conn->prepare("UPDATE users SET password = :password_hash, forgot_password = 0 WHERE user_id = :user_id");
                $stmt->bindParam(':password_hash', $password_hash);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                exit();
            } catch (Exception $e) {
                header("FAILED OPERATION", false, 500);
                exit();
            }
        }
        header("COULD NOT AUTHENTICATE", true, 400);
        exit();
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO CHANGE PASSWORD", true, 400);
    exit();
}