<?php

namespace core\Registration;

interface RegistrationInterface
{
    /**
     * @param array<mixed,mixed> $params
     * 
     * @return bool
     */
    public function store(array $params): bool;
}
