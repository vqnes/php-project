<?php

namespace core\DependencyInjection;

use core\Singleton;

class Container implements ContainerInterface
{
    use Singleton;

    /**
     * @var array<string,object>
     */
    protected array $dependencies = [];

    /**
     * @param class-string $id
     * @param class-string $class
     * 
     * @return $this
     */
    public function set(string $id, string $class): self
    {
        if (!class_exists($id) && !interface_exists($id)) {
            throw new \Exception("Class `{$id}` does not exists");
        }

        $this->dependencies[$id] = $this->resolve($class);
        return $this;
    }

    /**
     * @template TResolvedObject of object
     * 
     * @param class-string<TResolvedObject> $class
     * 
     * @return TResolvedObject
     */
    protected function resolve(string $class): object
    {
        $reflector = new \ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class `{$class}` is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return $reflector->newInstance();
        }

        $params = $constructor->getParameters();

        foreach ($params as $key => $param) {
            if ($param->isDefaultValueAvailable()) {
                $params[$key] = $param->getDefaultValue();
                continue;
            }

            $paramType = $param->getType();
            $paramType = $paramType === null ? '' : $paramType->getName();

            if (class_exists($paramType) || interface_exists($paramType)) {
                if (isset($this->dependencies[$paramType])) {
                    $params[$key] = $this->dependencies[$paramType];
                } else {
                    $params[$key] = $this->resolve($paramType);
                }

                continue;
            }

            throw new \Exception("Can not resolve class dependency `{$param->name}`");
        }

        return $reflector->newInstanceArgs($params);
    }

    public function get(string $id): object
    {
        if (!isset($this->dependencies[$id])) {
            throw new \Exception("Class with ID `{$id}` not found");
        }

        return $this->dependencies[$id];
    }

    public function has(string $id): bool
    {
        if (!isset($this->dependencies[$id])) {
            return false;
        }

        return true;
    }
}
