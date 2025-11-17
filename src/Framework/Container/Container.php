<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    protected array $builds = [];

    protected array $definitions = [];

    public function __construct(array $config = [])
    {
        $this->definitions = $config['definitions'] ?? [];
    }

    public function get(string $id)
    {
        $class = $id;
        $arguments = [];
        if (array_key_exists($id, $this->builds)) {
            return $this->builds[$id];
        }
        if (array_key_exists($id, $this->definitions)) {
            $definition = $this->definitions[$id];
            if (is_callable($definition)) {
                return $this->builds[$id] = $definition();
            } elseif (is_string($definition)) {
                $class = $definition;
            } elseif (is_object($definition)) {
                return $this->builds[$id] = $definition;
            } elseif (is_array($definition)) {
                if (is_string($definition['class']) && !empty($definition['class'])) {
                    $class = $definition['class'];
                    $arguments = array_diff_key($definition, ['class' => null]);
                } else {
                    throw new NotFoundServiceException('invalid declaration in service definition "' . $id . '"');
                }
            } else {
                throw new NotFoundServiceException('invalid declaration in service definition "' . $id . '"');
            }
        }
        if (class_exists($class)) {
            $params = [];
            $reflection = new \ReflectionClass($class);
            if (($constructor = $reflection->getConstructor()) !== null) {
                foreach ($constructor->getParameters() as $parameter) {
                    $name = $parameter->getName();
                    $type = $parameter->getType();

                    if ($type !== null && class_exists($type->getName())) {
                        $params[] = $this->get($type->getName());
                    } else {
                        if ($parameter->isDefaultValueAvailable()) {
                            if (array_key_exists($name, $arguments)) {
                                $params[] = $arguments[$name];
                            } else {
                                $params[] = $parameter->getDefaultValue();
                            }
                        } else {
                            if (array_key_exists($name, $arguments)) {
                                $params[] = $arguments[$name];
                            } else {
                                throw new NotFoundServiceException('Unable to resolve "'.$parameter->getName().'" in service "' . $id . '"');
                            }
                        }
                    }
                }
            }
            return $this->builds[$id] = new $class(...$params);
        }
        throw new NotFoundServiceException('Unknown service "' . $id . '"');
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->builds) || class_exists($id);
    }
}