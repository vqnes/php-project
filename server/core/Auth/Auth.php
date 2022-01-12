<?php

namespace core\Auth;

class Auth implements AuthInterface
{
    public function store(array $params): void
    {
        $_SESSION['authToken'] = $params['authToken'];
        $_SESSION['username'] = $params['username'];
    }

    public function forget(): void
    {
        unset($_SESSION['authToken'], $_SESSION['username']);
    }

    public function get(): array
    {
        return [
            'authToken' => $_SESSION['authToken'] ?? null,
            'username'  => $_SESSION['username'] ?? null,
        ];
    }
}
