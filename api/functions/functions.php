<?php
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";

//check whether a specific user has admin functionality allowed or not
function isAdmin($user_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT role FROM users WHERE user_id = :user_id AND role='admin'");

        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();
        $result = $stmt->fetchAll();
        if (count($result) > 0) {
            return true;
        }
        return false;
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }
}
