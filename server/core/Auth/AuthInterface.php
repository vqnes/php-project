<?php

namespace core\Auth;

interface AuthInterface
{
    /**
     * @param array<mixed,mixed>
     * 
     * @return void
     */
    public function store(array $params): void;

    public function forget(): void;

    /**
     * @return array<mixed,mixed>
     */
    public function get(): array;
}
