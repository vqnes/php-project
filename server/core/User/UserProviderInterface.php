<?php

namespace core\User;

interface UserProviderInterface
{
    /**
     * @param string|int $id
     * 
     * @return UserInterface|null
     */
    public function findUser($id): ?UserInterface;
}
