<?php
include_once "../ConnexionDB.php";
include_once "../functions.php";

function getNLastsFacts(int $nb): string
{
    $linkPDO = getInstance();
    $response = json_decode(FactsDAO::readAllChuckFact($linkPDO), true);
    if ($response["status_code"] !== 200) {
        return deliver_response($response["status_code"], $response["status_message"]);
    }
    $matchingData = $response["data"];
    $result = [];
    $iF = count($matchingData) - 1;
    $i = $iF;
    while ($i > ($iF - $nb)) {
        $result[] = $matchingData[$i];
        $i--;
    }
    return deliver_response(200, "récupération réussie", $result);
}

if (isset($_GET['nb'])) {
    $n = intval($_GET['nb']);
    echo getNLastsFacts($n);
} else {
    echo deliver_response(400, "Nombre de fact non spécifié");
}