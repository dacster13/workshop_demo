<?php
session_start();

require_once('includes/dbconnect.php');
require_once('includes/utils.php');
require_once('includes/functions.php');

if (!empty($_POST)) {

    // get login credentials
    $username = isset($_POST['username']) ?  
        htmlentities(trim($_POST['username'])) : null;

    $password = isset($_POST['password']) ? 
        htmlentities(trim($_POST['password'])) : null;

    $hashed_password = get_hash_by_username($pdo, $username);

    if (password_verify($password, $hashed_password)) {
        $_SESSION['workshop'] = get_user_data($pdo, $username);
        echo json_encode(createResponse(null, 'login successful'));
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'login failed invalid username or password'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'login failed'
    ]);
}