<?php

namespace core\User;

use app\Models\User as UserModel;

class UserProvider implements UserProviderInterface
{
    public function findUser($id): ?User
    {
        $model = new UserModel();
        $userData = $model->findOne($id);

        if (count($userData)) {
            //bin2hex(random_bytes(32));
            return new User($userData['name'], $userData['password'], $userData['token']);
        }

        return null;
    }
}
