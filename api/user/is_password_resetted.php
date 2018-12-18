<?PHP
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
include "../../jwt.php";
include "../../protected_session.php";

if ($_POST["AUTH_TOKEN"]) {

    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;

    try {
        $stmt = $conn->prepare("SELECT forgot_password FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        $result = $stmt->fetchAll();
        foreach ($result as $user) {
            if ($user['forgot_password'] == 1) {
                echo false;
                exit();
            }
            echo true;
            exit();
        }
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO GET PASSWORD RESET INFO FLAG", true, 400);
    exit();
}

//basics for stuff
