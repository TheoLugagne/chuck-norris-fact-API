<?php


class FactsDAO
{
    public static function readChuckFact($linkPDO, $id): string
    {
        if ($id === 0) {
            return deliver_response(400, "Identifiant invalide");
        } else {
            $query = "SELECT * FROM chuckn_facts WHERE id = :id";
            $stmt = $linkPDO->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return deliver_response(200, "récupération réussie", [$row]);
            } else {
                return deliver_response(404, "L'identifiant " . $id . " non trouvé");
            }
        }
    }

    public static function readAllChuckFact($linkPDO): string
    {
        $query = "SELECT * FROM chuckn_facts";
        $stmt = $linkPDO->prepare($query);
        if ($stmt->execute()) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return deliver_response(200, "récupération réussie", $row);
        } else {
            return deliver_response(400, "Erreur dans la requête");
        }
    }

    public static function createChuckFact($linkPDO, $phrase): string
    {
        if ($phrase !== "") {
            $query = "INSERT INTO chuckn_facts(phrase, date_ajout) VALUES (:phrase, :date_ajout)";
            $stmt = $linkPDO->prepare($query);
            $stmt->bindParam(":phrase", $phrase, PDO::PARAM_STR);
            $date_ajout = (new DateTime())->format('Y-m-d H:i:s');
            $stmt->bindParam(":date_ajout", $date_ajout);
            $linkPDO->beginTransaction();
            if ($stmt->execute()) {
                $newId = $linkPDO->lastInsertId();
                $linkPDO->commit();
                $new = json_decode(self::readChuckFact($linkPDO, $newId), true)["data"];
                return deliver_response(201, "La ressourec à bien été crée", $new);
            } else {
                return deliver_response(400, "Erreur dans la requete");
            }
        }
        return "";
    }

    public static function patchChuckFact($linkPDO, $id, $phrase = null, $vote = null, $faute = null, $signalement = null): string
    {
        if ($id === 0) {
            return deliver_response(400, "Identifiant invalide");
        } else if ($id <= 44) {
            return deliver_response(403, "Vous ne pouvez pas modifier les ressources par défaut");
        }
        $query = "UPDATE chuckn_facts SET
            phrase = :phrase,
            date_modif = :date_modif,
            vote = :vote,
            faute = :faute,
            signalement = :sign
            WHERE id = :id
        ";
        $date_modif = (new DateTime())->format('Y-m-d H:i:s');
        $stmt = $linkPDO->prepare($query);
        $data = self::readChuckFact($linkPDO, $id);
        $old = json_decode($data, true)["data"][0];
        if ($old === null) {
            return deliver_response(404, "L'identifiant " . $id . " non trouvé");
        }
        if ($phrase == null) {
            $phrase = $old["phrase"];
        }
        if ($vote !== 0 && $vote == null) {
            $vote = $old["vote"];
        }
        if ($faute == null) {
            $faute = $old["faute"];
        }
        if ($signalement == null) {
            $signalement = $old["signalement"];
        }
        $stmt->bindParam(":phrase", $phrase, PDO::PARAM_STR);
        $stmt->bindParam(":date_modif", $date_modif);
        $stmt->bindParam(":vote", $vote, PDO::PARAM_INT);
        $stmt->bindParam(":faute", $faute, PDO::PARAM_BOOL);
        $stmt->bindParam(":sign", $signalement, PDO::PARAM_BOOL);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $linkPDO->beginTransaction();
        if ($stmt->execute()) {
            $linkPDO->commit();
            $new = json_decode(self::readChuckFact($linkPDO, $id), true)["data"];
            return deliver_response(200, "La ressource a bien été modifiée", $new);
        } else {
            return deliver_response(404, "L'identifiant " . $id . " non trouvé");
        }
        //return self::execution($linkPDO, $stmt, $id, $phrase, $date_modif, $vote, $faute, $signalement);
    }

    public static function putChuckFact($linkPDO, $id, $phrase = null, $vote = null, $faute = null, $signalement = null): string
    {
        if ($id === 0) {
            return deliver_response(400, "Identifiant invalide");
        } else if ($id <= 44) {
            return deliver_response(403, "Vous ne pouvez pas modifier les ressources par défaut");
        }
        $query = "UPDATE chuckn_facts SET
            phrase = :phrase,
            date_modif = :date_modif,
            vote = :vote,
            faute = :faute,
            signalement = :sign
            WHERE id = :id
        ";
        $date_modif = (new DateTime())->format('Y-m-d H:i:s');
        $stmt = $linkPDO->prepare($query);
        $old = json_decode(self::readChuckFact($linkPDO, $id), true)["data"];
        if ($old === null) {
            return deliver_response(404, "L'identifiant " . $id . " non trouvé");
        }
        $stmt->bindParam(":phrase", $phrase, PDO::PARAM_STR);
        $stmt->bindParam(":date_modif", $date_modif);
        $stmt->bindParam(":vote", $vote, PDO::PARAM_INT);
        $stmt->bindParam(":faute", $faute, PDO::PARAM_BOOL);
        $stmt->bindParam(":sign", $signalement, PDO::PARAM_BOOL);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $linkPDO->beginTransaction();
        if ($stmt->execute()) {
            $linkPDO->commit();
            $new = json_decode(self::readChuckFact($linkPDO, $id), true)["data"];
            return deliver_response(200, "La ressource a bien été modifiée", $new);
        } else {
            return deliver_response(404, "L'identifiant " . $id . " non trouvé");
        }
        //return self::execution($linkPDO, $stmt, $id, $phrase, $date_modif, $vote, $faute, $signalement);

    }

    public static function deleteChuckFact($linkPDO, $id): string
    {
        if ($id === 0) {
            return deliver_response(400, "Identifiant invalide");
        }
        if ($id <= 44) {
            return deliver_response(403, "Vous ne pouvez pas supprimer les ressources par défaut");
        }
        $query = "DELETE FROM chuckn_facts WHERE id = :id";
        $stmt = $linkPDO->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return deliver_response(200, "La ressource a bien été supprimée");
        } else {
            return deliver_response(404, "L'identifiant " . $id . " non trouvé");
        }
    }

    /**
     * @param PDO $linkPDO
     * @param mixed $stmt
     * @param $id
     * @param mixed $phrase
     * @param string $date_modif
     * @param mixed $vote
     * @param mixed $faute
     * @param mixed $signalement
     * @return string
     */
    private static function execution(PDO $linkPDO, mixed $stmt, $id, mixed $phrase, string $date_modif, mixed $vote, mixed $faute, mixed $signalement): string
    {
        $stmt->bindParam(":phrase", $phrase, PDO::PARAM_STR);
        $stmt->bindParam(":date_modif", $date_modif);
        $stmt->bindParam(":vote", $vote, PDO::PARAM_INT);
        $stmt->bindParam(":faute", $faute, PDO::PARAM_BOOL);
        $stmt->bindParam(":sign", $signalement, PDO::PARAM_BOOL);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $linkPDO->beginTransaction();
        if ($stmt->execute()) {
            $linkPDO->commit();
            $new = json_decode(self::readChuckFact($linkPDO, $id), true)["data"];
            return deliver_response(200, "La ressource a bien été modifiée", $new);
        } else {
            return deliver_response(404, "L'identifiant " . $id . " non trouvé");
        }
    }
}

/// Envoi de la réponse au Client
function deliver_response($status_code, $status_message, $data = null): string
{
    /// Paramétrage de l'entête HTTP
    http_response_code($status_code);
    //Utilise un message standardisé en fonction du code HTTP
    //header("HTTP/1.1 $status_code $status_message");
    //Permet de personnaliser le message associé au code HTTP
    header("Content-Type:application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    //Indique au client le format de la réponse
    $response['status_code'] = $status_code;
    $response['status_message'] = $status_message;
    $response['data'] = $data;
    /// Mapping de la réponse au format JSON
    $json_response = json_encode($response);
    if ($json_response === false) {
        die('json encode ERROR : ' . json_last_error_msg());
        /// Affichage de la réponse (Retourné au client)
    }
    return $json_response;
}
