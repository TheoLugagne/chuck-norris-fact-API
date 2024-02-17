<?php
include_once "ConnexionDB.php";
include_once "functions.php";

$factDAO = new FactsDAO();

$linkPDO = getInstance();
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method) {
    case "GET":
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            echo FactsDAO::readChuckFact($linkPDO, $id);
        } else {
            echo FactsDAO::readAllChuckFact($linkPDO);
        }
        break;
    case "POST":
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);
        if (isset($data["phrase"])) {
            echo FactsDAO::createChuckFact($linkPDO, $data["phrase"]);
        } else {
            echo FactsDAO::createChuckFact($linkPDO, "");
        }
        break;
    case "PATCH":
        header("Content-Type:application/json; charset=utf-8");
        header("Access-Control-Allow-Origin: *");
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            $phrase = null;
            $vote = null;
            $faute = null;
            $signalement = null;
            if (isset($data["phrase"])) {
                $phrase = $data["phrase"];
            }
            if (isset($data["vote"])) {
                $vote = $data["vote"];
            }
            if (isset($data["faute"])) {
                $faute = $data["faute"];
            }
            if (isset($data["signalement"])) {
                $signalement = $data["signalement"];
            }
            echo FactsDAO::patchChuckFact($linkPDO, $id, $phrase, $vote, $faute, $signalement);
        } else {
            echo deliver_response(400, "Identifiant non spécifié");
        }
        break;
    case "PUT":
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            echo FactsDAO::putChuckFact($linkPDO, $id, $data["phrase"], $data["vote"], $data["faute"], $data["signalement"]);
        }
        break;
    case "DELETE":
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            echo FactsDAO::deleteChuckFact($linkPDO, $id);
        }
        break;
    case "OPTIONS":
        header("Access-Control-Allow-Methods:*");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        echo deliver_response(204, "Envoie des headers réussi");
        break;
}
