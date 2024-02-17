<?php
include_once "../ConnexionDB.php";
include_once "../functions.php";

function getReportedFact(): string
{
    $linkPDO = getInstance();
    $response = json_decode(FactsDAO::readAllChuckFact($linkPDO), true);
    if ($response["status_code"] !== 200) {
        return deliver_response($response["status_code"], $response["status_message"]);
    }
    $matchingData = $response["data"];
    $result = [];
    foreach ($matchingData as $fact) {
        if ($fact['signalement'] === 1) {
            $result[] = $fact;
        }
    }
    return deliver_response(200, "récupération réussie", $result);
}

echo getReportedFact();