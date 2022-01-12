<?php

namespace core\DependencyInjection;

interface ContainerInterface
{
    public function get(string $id): object;
    public function has(string $id): bool;
}
