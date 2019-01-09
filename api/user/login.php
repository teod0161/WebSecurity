<?PHP
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
include "../../jwt.php";

if ($_POST["email"] && $_POST["password"] && $_POST["captcha"]) {

    $email = $_POST["email"];
    $password = $_POST["password"];
    
    //the necessary information for the captcha check
    //basically on the backend, the test for making sure the captcha is valid is made
    /*$captcha = $_POST["captcha"];
    $key = "6LeJhYIUAAAAALlsFny-_wZEXFPrp9JZx6Mum-36";
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $params = array(
        'secret' => $key,
        'response' => $captcha
    );

    //initiate the connection
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url); //set the API link (to Google's own for recaptcha)
    curl_setopt($ch, CURLOPT_POST, 1); //signal I want the request to be of POST type
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params)); //set the params
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //this tells the request not to echo anything back, aside from the essential fields

    //execute the post request
    $resultRecaptcha = curl_exec($ch);

    //if an error arrises
    if(curl_errno($ch) !== 0) {
        error_log('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
    }

    //this takes place in case the recaptcha call to the API fails and google send back that the authentificated user is not real
    if (json_decode($resultRecaptcha)->success === false) {
        header("FAILED OPERATION", false, 500);
        curl_close($ch);
        exit();
    }

    //close the connection
    curl_close($ch);*/

        try {
            //prepared statement for the user's login
            $stmt = $conn->prepare("SELECT password, user_id, forgot_password FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);

            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach ($result as $user) {
                if (!password_verify($password, $user['password'])) {
                    header("COULD NOT AUTHENTICATE", true, 400);
                    exit();
                }
                //necessary for the token
                $header = [
                    "alg" => "HS256",
                    "typ" => "JWT"
                ];
                //the token's payload
                $payload = [
                    "user_id" => $user['user_id'],
                    "expires" => time() + 2*60*60
                ];

                $requestChangePassword = false;
                //if the user requested to change password, the flag is used
                if ($user['forgot_password'] == 1) {
                    $requestChangePassword = true;
                }
                //method from jwt.php used to generate the token from the values above
                $token = generateJWT('sha256', $header, $payload, $secret);
                $response = json_decode("{}");
                $response->token = $token;
                $response->requestChangePassword = $requestChangePassword;
                echo json_encode($response);
                exit();
            }

            header("COULD NOT AUTHENTICATE", true, 400);
            exit();
        } catch (Exception $e) {
            header("FAILED OPERATION", false, 500);
            exit();
        }
    }

//basics for stuff
