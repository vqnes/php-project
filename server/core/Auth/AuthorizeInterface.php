<?php

namespace core\Auth;

interface AuthorizeInterface
{
    public function isLoggedIn(): bool;
}
