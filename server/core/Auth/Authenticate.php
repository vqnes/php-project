<?php

namespace core\Auth;

use core\User\UserInterface;
use core\User\UserProviderInterface;

class Authenticate implements AuthenticateInterface
{
    protected ?UserInterface $user = null;
    protected UserProviderInterface $userProvider;
    protected Auth $auth;

    public function __construct(UserProviderInterface $userProvider, AuthInterface $auth)
    {
        $this->userProvider = $userProvider;
        $this->auth = $auth;
    }

    public function getUser(): ?UserInterface
    {
        if ($this->user === null) {
            $sessionData = $this->auth->get();

            if (
                $sessionData['username'] !== null &&
                $sessionData['authToken'] !== null
            ) {
                $user = $this->userProvider->findUser($sessionData['username']);

                if (
                    $user !== null &&
                    $user->getToken() === $sessionData['authToken']
                ) {
                    $this->user = $user;
                }
            }
        }

        return $this->user;
    }

    public function login(array $params): bool
    {
        $user = $this->userProvider->findUser($params['username']);

        if (
            $user !== null &&
            password_verify($params['password'], $user->getPassword())
        ) {
            $this->user = $user;

            $this->auth->store([
                'authToken' => $user->getToken(),
                'username'  => $user->getName(),
            ]);

            return true;
        }

        return false;
    }

    public function loginForUser(UserInterface $user): bool
    {
        $userDb = $this->userProvider->findUser($user->getName());

        if (
            $userDb === null ||
            $userDb->getToken() !== $user->getToken() ||
            $userDb->getPassword() !== $user->getPassword()
        ) {
            return false;
        }

        $this->user = $user;

        $this->auth->store([
            'authToken' => $user->getToken(),
            'username'  => $user->getName(),
        ]);

        return true;
    }
}
