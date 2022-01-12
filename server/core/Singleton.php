<?php

namespace core;

trait Singleton
{
    /**
     * @var array<self>
     */
    protected static array $instances = [];

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }

    public static function getInstance(): self
    {
        $class = static::class;

        if (self::$instances[$class] === null) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }
}
