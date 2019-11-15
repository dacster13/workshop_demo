<?php

function createResponse($err, $message) {
    return [
        'success' => !isset($err),
        'message' => isset($err) ? $err->getMessage() : $message
    ];
}