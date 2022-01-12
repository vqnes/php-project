<?php

namespace core\base;

class View
{
    protected string $dir;
    protected string $view;

    public function __construct(string $dir, string $view)
    {
        $this->dir = $dir;
        $this->view = $view;
    }

    /**
     * @param array<string,mixed> $vars
     * 
     * @return void
     */
    public function render(array $vars): void
    {
        extract($vars);

        $fileView = APP . "/views/{$this->dir}/{$this->view}.php";

        if (is_file($fileView)) {
            require_once $fileView;
        } else {
            throw new \Exception("View $fileView not found");
        }
    }
}
