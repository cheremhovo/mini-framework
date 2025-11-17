<?php

declare(strict_types=1);

namespace Test\Framework\Container;

use Cheremhovo1990\Framework\Container\Container;
use Cheremhovo1990\Framework\Container\NotFoundServiceException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testInstantiating()
    {
        $container = new Container();

        $this->assertIsObject($object = $container->get(EmptyClass::class));
        $this->assertInstanceOf(EmptyClass::class, $object);
        $this->assertEquals($object, $container->get(EmptyClass::class));
    }

    public function testAutowiring()
    {
        $container = new Container();

        $this->assertInstanceOf(OutClass::class, $object = $container->get(OutClass::class));
        $this->assertInstanceOf(InnerClass::class, $object->innerClass);
        $this->assertEquals(5, $object->integer);
    }

    public function testDefinitions()
    {
        $config = [
            'definitions' => [
                EmptyClassInterface::class => EmptyClass::class,
                'service' => EmptyClass::class,
                'callable_callback' => function () {
                    return 'callable_callback';
                },
                'callable_object' => new InnerClass(),
                'callable_object_callback' => [new InnerClass(), 'run'],
                'callable_class_callback' => [InnerClass::class, 'execute'],
                'callable_class_callback_repeat' => InnerClass::class . '::execute',
                'run' => [
                    'class' => OutClass::class,
                    'integer' => 10
                ]
            ]
        ];
        $container = new Container($config);

        $this->assertInstanceOf(EmptyClass::class, $container->get(EmptyClassInterface::class));
        $this->assertInstanceOf(EmptyClass::class,  $container->get('service'));
        $this->assertEquals('callable_callback',  $container->get('callable_callback'));
        $this->assertEquals('callable_object',  $container->get('callable_object'));
        $this->assertEquals('callable_object_callback',  $container->get('callable_object_callback'));
        $this->assertEquals('callable_class_callback',  $container->get('callable_class_callback'));
        $this->assertEquals('callable_class_callback',  $container->get('callable_class_callback_repeat'));
        $this->assertInstanceOf(OutClass::class, $object = $container->get('run'));
        $this->assertEquals(10, $object->integer);
    }

    public function testNotFound()
    {
        $container = new Container();

        $this->expectException(NotFoundServiceException::class);
        $container->get('unknown');
    }
}
interface EmptyClassInterface
{

}
class EmptyClass {}

class OutClass {
    public InnerClass $innerClass;
    public int $integer;

    public function __construct(
        InnerClass $innerClass,
        int $integer = 5,
        $class = new InnerClass()
    )
    {
        $this->innerClass = $innerClass;
        $this->integer = $integer;
    }
}
class InnerClass {
    public function __invoke()
    {
        return 'callable_object';
    }

    public function run()
    {
        return 'callable_object_callback';
    }

    public static function execute()
    {
        return 'callable_class_callback';
    }
}