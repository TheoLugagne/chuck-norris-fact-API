<?php

use classes\AcessToken;
use classes\User;

include_once '../v1/functions.php';
include_once './classes/User.php';
include_once './classes/AcessToken.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted_data = file_get_contents('php://input');
    $user = new User($posted_data);
    try {
        if ($user->acessGranted()) {
            $token = new AcessToken($user->getLogin(), $user->getRole(), 3600);
            echo deliver_response(200, "Connexion réussie", $token->getToken());
        } else {
            echo deliver_response(400, "[R401 API REST AUTH] : mot de passe incorrect");
        }
    } catch (Exception $e) {
        echo deliver_response(500, "[R401 API REST AUTH] : login incorrect");
    }
}
