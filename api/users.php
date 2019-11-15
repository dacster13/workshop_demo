<?php

require_once('../includes/dbconnect.php');
require_once('../includes/utils.php');
require_once('../includes/functions.php');

header('Content-type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {

    // READ
    case 'GET':

        if (isset($_GET['uid'])) {
            $user_id = urlencode($_GET['uid']);
            echo json_encode([
                'data' => get_user_by_id($pdo, $user_id)
            ]);
            exit;
        }

        echo json_encode([ 
            'data' => get_users($pdo)
        ]);
    break;

    // CREATE OR INSERT
    case 'POST':
        $_POST['password'] = password_hash($_POST['password'],  PASSWORD_DEFAULT);
        echo json_encode(add_user($pdo, $_POST));
    break;

    // UPDATE
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $data['password'] = password_hash($data['password'],  PASSWORD_DEFAULT);
        echo json_encode(update_user($pdo, $data));
    break;

    // DELETE
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        echo json_encode(delete_user($pdo, $data['user_id']));
    break;

}