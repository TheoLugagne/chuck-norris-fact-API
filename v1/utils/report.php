<?php
include_once "../ConnexionDB.php";
include_once "../functions.php";

function report(int $id): string
{
    if ($id === 0) {
        return deliver_response(400, "Identifiant invalide");
    } else if ($id <= 44) {
        return deliver_response(403, "Vous ne pouvez pas signaler pour les ressources par défaut");
    }
    $linkPDO = getInstance();
    $responseGet = json_decode(FactsDAO::readChuckFact($linkPDO, $id), true);
    $statusResponse = $responseGet["status_code"];
    if ($statusResponse !== 200) {
        $statusMessage = $responseGet["status_message"];
        return deliver_response($statusResponse, $statusMessage);
    }

    $responsePatch = json_decode(FactsDAO::patchChuckFact($linkPDO, $id, signalement: true), true);
    if ($responsePatch["status_code"] !== 200) {
        return deliver_response($responsePatch["status_code"], $responsePatch["status_message"]);
    }
    return deliver_response(200, "Signalement enregistré", $responsePatch["data"]);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    echo report($id);
} else {
    echo deliver_response(400, "Identifiant non spécifié");
}