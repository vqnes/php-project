<?php

namespace core\base;

use core\base\View;
use core\Str;

abstract class Controller
{
    protected string $controllerName;

    /**
     * @var array<mixed,mixed>
     */
    protected array $vars = [];

    public function __construct()
    {
        $this->controllerName = Str::getControllerName(static::class);
    }

    public function getView(string $view, ?string $dir = null): void
    {
        $dir = $dir ?? $this->controllerName;

        $view = new View($dir, $view);
        $view->render($this->vars);
    }

    /**
     * @param array<mixed,mixed> $vars
     * 
     * @return $this
     */
    public function setVars(array $vars): self
    {
        $this->vars = $vars;
        return $this;
    }
}
