<?php

namespace core\Auth;

use core\User\UserInterface;

interface AuthenticateInterface
{
    public function getUser(): ?UserInterface;

    /**
     * @param array<mixed,mixed> $params
     * 
     * @return bool
     */
    public function login(array $params): bool;

    public function loginForUser(UserInterface $user): bool;
}
