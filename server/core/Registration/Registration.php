<?php

namespace core\Registration;

use app\Models\User;
use core\Db;
use core\Auth\AuthInterface;

class Registration implements RegistrationInterface
{
    protected AuthInterface $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function store(array $params): bool
    {
        $model = new User();

        if (count($model->findOne($params['username'])) > 0) {
            return false;
        }

        $token = $this->createToken();

        $db = Db::getInstance();

        $db->beginTransaction();
        try {
            $isQuery = $model->insertOne([
                $params['username'],
                password_hash($params['password'], PASSWORD_BCRYPT),
                $token
            ]);

            $db->commit();
        } catch (\PDOException $e) {
            $db->rollBack();

            throw $e;
        }

        $this->auth->store([
            'username'  => $params['username'],
            'authToken' => $token,
        ]);

        return $isQuery ?? false;
    }

    public function createToken(int $length = 32): string
    {
        if ($length > 32 || $length < 6) {
            $length = 32;
        }

        return bin2hex(random_bytes($length));
    }
}
