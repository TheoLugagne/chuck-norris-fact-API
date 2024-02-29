<?php

namespace classes;

include_once 'jwt_utils.php';

class AcessToken
{
    private string $token;

public function __construct(string $login, string $role, int $expiration)
    {
        $this->generateToken($login, $role, $expiration);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function generateToken(string $login, string $role, int $expiration): void
    {
        $headers = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
        $payload = [
            "login" => $login,
            "role" => $role,
            "exp" => time() + $expiration
        ];
        $secret = "ksjwc3MJ/55;z-9!w^69}UAzQ(:9G2QE";
        $this->token = generate_jwt($headers, $payload, $secret);
    }

    public function __toString(): string
    {
        return $this->getToken();
    }
}