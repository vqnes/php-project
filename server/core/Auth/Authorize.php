<?php

namespace core\Auth;

class Authorize implements AuthorizeInterface
{
    protected AuthenticateInterface $authenticate;

    public function __construct(AuthenticateInterface $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    public function isLoggedIn(): bool
    {
        if ($this->authenticate->getUser() === null) {
            return false;
        }

        return true;
    }

    public function getAuthenticate(): AuthenticateInterface
    {
        return $this->authenticate;
    }
}
