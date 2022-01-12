<?php

namespace core\User;

class User implements UserInterface
{
    protected string $name;
    protected string $password;
    protected string $token;

    public function __construct(string $name, string $password, string $token)
    {
        $this->name = $name;
        $this->password = $password;
        $this->token = $token;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getToken()
    {
        return $this->token;
    }
}
