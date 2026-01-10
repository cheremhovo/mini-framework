<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework;

use Cheremhovo1990\Framework\Container\Container;
use Psr\Container\ContainerInterface;

class App
{
    protected static string $rootDirectory;
    protected static null|ContainerInterface $container = null;

    public static function getContainer(): ContainerInterface
    {
        if (self::$container === null) {
            return self::$container = new Container();
        }
        return self::$container;
    }

    public static function setContainer(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function getRootDirectory(string $append = ''): string
    {
        return self::$rootDirectory . DIRECTORY_SEPARATOR . $append;
    }

    public static function setRootDirectory(string $rootDirectory): void
    {
        self::$rootDirectory = $rootDirectory;
    }

    public static function get($class)
    {
        if (is_string($class)) {
            return self::$container->get($class);
        } else {
            return $class;
        }
    }
}