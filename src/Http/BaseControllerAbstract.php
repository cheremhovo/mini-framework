<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Http;

use Cheremhovo1990\Framework\App;

abstract class BaseControllerAbstract
{
    public array $params = [];

    protected string|null $extends = null;
    protected \SplStack $blockNames;
    protected array $blocks = [];
    public function __construct()
    {
        $this->blockNames = new \SplStack();
    }

    public function render(string $name, array $params = []): string
    {
        $level = ob_get_level();
        $this->extends = null;
        try {
            ob_start();
            extract($params);
            require App::getRootDirectory('views/' . $name . '.php');
            $content = ob_get_clean();
        } catch (\Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }

        if ($this->extends === null) {
            return $content;
        }

        return $this->render($this->extends, ['content' => $content]);
    }

    public function extends($view): void
    {
        $this->extends = $view;
    }

    public function renderBlock(string $name): string
    {
        return $this->blocks[$name] ?? '';
    }

    public function beginBlock(string $name): void
    {
        $this->blockNames->push($name);
        ob_start();
    }

    public function endBlock()
    {
        $content = ob_get_clean();
        $name = $this->blockNames->pop();
        if ($this->hasBlock($name)) {
            return;
        }
        $this->blocks[$name] = $content;

    }

    public function hasBlock(string $name): bool
    {
        return array_key_exists($name, $this->blocks);
    }
}