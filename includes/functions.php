<?php

function get_users($pdo) {
    $query = "SELECT user_id, username, password, office FROM users";
    $statement = $pdo->query($query);
    return $statement->fetchAll();
}

function get_user_by_id($pdo, $user_id) {
    $query = "SELECT * FROM users WHERE user_id = ? ";
    $statement = $pdo->prepare($query);
    $statement->execute([ $user_id ]);
    return $statement->fetchAll();
}

function get_hash_by_username($pdo, $username) {
    $query = "SELECT password FROM users where username = ? ";
    $statement = $pdo->prepare($query);
    $statement->execute([ $username ]);
    return $statement->fetchColumn();
}

function get_user_data($pdo, $username) {
    $query = "SELECT user_id, username, office FROM users where username = ? ";
    $statement = $pdo->prepare($query);
    $statement->execute([ $username ]);
    return $statement->fetchObject();
}

function add_user($pdo, $data) {
    extract($data);
    try {
        $query = "INSERT INTO users(username, password, office) ";
        $query .= "VALUES (?, ?, ?) ";
        $statement = $pdo->prepare($query);
        $statement->execute([ $username, $password, $office ]);
        $resp = createResponse(null, 'user added successfully');
    } catch (\Exception $e) {
        $resp = createResponse($e, null);
    }
    return $resp;
}

function update_user($pdo, $data) {
    extract($data);
    try {
        $query = "UPDATE users SET username = ?, 
            password = ?, 
            office = ? 
            WHERE user_id = ?";

        $statement = $pdo->prepare($query);
        $statement->execute([ $username, $password, $office, $user_id ]);
        $resp = createResponse(null, 'user updated');
    } catch (\Exception $e) {
        $resp = createResponse($e, null);
    }
    return $resp;
}

function delete_user($pdo, $user_id) {
    try {
        $query = "DELETE FROM users WHERE user_id = ? ";
        $statement = $pdo->prepare($query);
        $statement->execute([ $user_id ]);
        $resp = createResponse(null, 'user deleted');
    } catch (\Exception $e) {
        $resp = createResponse($e, null);
    }
    return $resp;
}

function verify_user($pdo, $username, $password) {
    $query = "SELECT 1 FROM users ";
    $query .= "WHERE username = ? AND password = ? ";
    $statement = $pdo->prepare($query);
    $statement->execute([ $username, password_hash($password, PASSWORD_DEFAULT) ]);
    return $statement->fetchColumn();
}