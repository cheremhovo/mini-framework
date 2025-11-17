<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router;

use Cheremhovo1990\Framework\Helpers\StringHelper;
use Cheremhovo1990\Framework\Helpers\UrlHelper;
use Cheremhovo1990\Framework\Router\Attribute\Route;
use Cheremhovo1990\Framework\Router\Attribute\RouteGroup;
use ReflectionClass;
use ReflectionMethod;

class RouterMapController
{
    private array $classes;

    protected ReflectionClass $reflection;
    protected array $results = [];
    protected string|null $prefixName = null;
    protected string|null $prefixPattern = null;
    protected string|null $prefixNameByClass = null;
    protected string|null $prefixPatternByClass = null;
    protected array $attributesOfClass = [];
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }

    /**
     * @throws RouteMapControllerException
     */
    public function __invoke(): array
    {
        foreach ($this->classes as $class) {
            if (!class_exists($class)) {
                continue;
            }
            $this->reflection = new ReflectionClass($class);
            $this->attributesOfClass = $this->reflection->getAttributes();
            $this->prefixName = null;
            $this->prefixPattern = null;
            $this->prefixNameByClass = null;
            $this->prefixPatternByClass = null;
            $this->initPrefix();
            $this->reflectionClass();
            $this->reflectionMethods();
        }
        return $this->results;
    }

    protected function reflectionClass(): void
    {
        foreach ($this->attributesOfClass as $refAttribute) {
            if (Route::class === $refAttribute->getName()) {
                /** @var Route $attribute */
                $attribute = $refAttribute->newInstance();
                if ($this->reflection->hasMethod('__invoke')) {
                    $this->addResult(
                        $attribute->name,
                        $attribute->pattern,
                        [$this->reflection->getName(), '__invoke'],
                        $attribute->methods,
                        $attribute->options
                    );
                } else {
                    throw new RouteMapControllerException('Method "__invoke" not found in class "' . $this->reflection . '"');
                }
            }
        }
    }

    protected function reflectionMethods(): void
    {
        $methods = $this->reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();
            if (empty($attributes)) {
                if (StringHelper::equalEnd($method->getName(), 'Action')) {
                    $name = StringHelper::replaceEnd('Action', '', $method->getName());
                    $name = StringHelper::camelCaseToId($name);
                    $name = strtolower($name);
                    $this->intiPrefixByClass();
                    $this->addResult(
                        $this->prefixNameByClass  . $name,
                        $this->prefixPatternByClass . $name,
                        [$this->reflection->getName(), $method->getName()],
                    );
                }
            } else {
                foreach ($attributes as $refAttribute) {
                    if (Route::class === $refAttribute->getName()) {
                        /** @var Route $attribute */
                        $attribute = $refAttribute->newInstance();
                        $this->addResult(
                            $this->concatenateString($this->prefixName, $attribute->name, '.'),
                            $this->concatenateString($this->prefixPattern, $attribute->pattern, '/'),
                            [$this->reflection->getName(), $method->getName()],
                            $attribute->methods,
                            $attribute->options
                        );
                    }
                }
            }
        }
    }

    protected function concatenateString(string $begin, string $string, string $replace): string
    {
        if ($begin === '') {
            return $string;
        }
        return  $begin . StringHelper::replaceStart($replace, '', $string);
    }

    protected function initPrefix(): void
    {
        if ($this->prefixName === null) {
            $this->prefixName = '';
            $this->prefixPattern = '';
            foreach ($this->attributesOfClass as $refAttribute) {
                if (RouteGroup::class === $refAttribute->getName()) {
                    /** @var RouteGroup $attributeClass */
                    $attributeClass = $refAttribute->newInstance();
                    $this->prefixName = $attributeClass->name . '.';
                    $this->prefixPattern = $attributeClass->pattern . '/';
                }
            }
        }
    }

    public function intiPrefixByClass(): void
    {
        if ($this->prefixName === '' && $this->prefixNameByClass === null) {
            $fullName = StringHelper::replaceStart('App\Controller\\', '', $this->reflection->getName());
            $name = StringHelper::replaceEnd('Controller', '', $fullName);
            $id = StringHelper::camelCaseToId($name);

            $this->prefixNameByClass =  str_replace('\\', '.', $id) . '.';
            $this->prefixPatternByClass = str_replace('\\', '/', $id) . '/';

        } else {
            $this->prefixNameByClass = $this->prefixName;
            $this->prefixPatternByClass = $this->prefixPattern;
        }
    }

    protected function addResult(
        string $name,
        string $pattern,
        array $method,
        array $methods = [],
        array $options = []
    )
    {
        $this->results[] = [
            $name,
            $pattern,
            $method,
            $methods,
            $options
        ];
    }
}