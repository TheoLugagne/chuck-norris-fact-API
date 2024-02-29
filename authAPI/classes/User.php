<?php

namespace classes;

include_once '../v1/ConnexionDB.php';
use Exception;
use PDO;

class User
{
    private string $login;
    private string $role;
    private string $password;

    public function __construct(string $json)
    {
        $data = json_decode($json, true);
        $this->setLogin($data['login']);
        $this->setPassword($data['password']);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    private function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @throws Exception
     */
    public function acessGranted(): bool
    {
        $query = "SELECT password, role FROM user WHERE login = :login";
        $stmt = getInstance()->prepare($query);
        $stmt->bindParam(":login", $this->login, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $dbdata = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() === 1 and password_verify($this->password, $dbdata['password'])) {
                $this->setRole($dbdata["role"]);
                return true;
            }
            return false;
        }
        throw new Exception("Erreur dans la requête");
    }
}