<?php

namespace Phpactor\Container\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;
use RuntimeException;
use Phpactor\Container\PhpactorContainer;
use stdClass;
use Phpactor\Container\Container;

class PhpactorContainerTest extends TestCase
{
    private PhpactorContainer $container;

    public function setUp(): void
    {
        $this->container = new PhpactorContainer([
            'configKey1' => 'value1',
            'nullKey' => null,
        ]);
    }

    public function testFromExtensions(): void
    {
        $extension1 = new class implements Extension {
            public function configure(Resolver $resolver): void
            {
                $resolver->setDefaults([
                    'foo' => 'bar'
                ]);
            }

            public function load(ContainerBuilder $builder): void
            {
                $builder->register('stdclass', function () {
                    return new stdClass();
                });
            }
        };
        $container = PhpactorContainer::fromExtensions([
            get_class($extension1)
        ], [
            'foo' => 'foo',
        ]);
        $this->assertInstanceOf(Container::class, $container);
        $this->assertEquals('foo', $container->getParameter('foo'));
        $this->assertInstanceOf(stdClass::class, $container->get('stdclass'));

        $this->assertEquals([
            get_class($extension1)
        ], $container->getParameter('container.extension_classes'));
    }

    public function testThrowsExceptionForUnknownService(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No service with ID "foobar" exists');

        $this->container->get('foobar');
    }

    public function testRetrievesService(): void
    {
        $this->container->register('foobar', function (Container $container) {
            return new stdClass();
        });

        $service = $this->container->get('foobar');
        $this->assertInstanceOf(stdClass::class, $service);
    }

    public function testRetrievesExpectedService(): void
    {
        $this->container->register('foobar', function (Container $container) {
            return new stdClass();
        });

        $service = $this->container->expect('foobar', stdClass::class);
        $this->assertInstanceOf(stdClass::class, $service);
    }

    public function testRetrievesSameServiceWhenCalledMultipleTimes(): void
    {
        $this->container->register('foobar', function (Container $container) {
            return new stdClass();
        });

        $service1 = $this->container->get('foobar');
        $service2 = $this->container->get('foobar');
        $this->assertSame($service1, $service2);
    }

    public function testSaysIfItHasAService(): void
    {
        $this->container->register('foobar', function (Container $container) {
            return new stdClass();
        });

        $this->assertTrue($this->container->has('foobar'));
        $this->assertFalse($this->container->has('barfoo'));
    }

    public function testReturnsServicesByTag(): void
    {
        $this->container->register('foobar', function (Container $container) {
            return new stdClass();
        }, [ 'foobar' => []]);

        $serviceIds = $this->container->getServiceIdsForTag('foobar');
        $this->assertCount(1, $serviceIds);

        $this->assertEquals([
            'foobar' => []
        ], $serviceIds);
    }

    public function testThrowsExceptionIfParmaeterNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unknown parameter "foobar", known parameters');
        $this->container->getParameter('foobar');
    }

    public function testReturnsParameter(): void
    {
        $result = $this->container->getParameter('configKey1');
        $this->assertEquals('value1', $result);
    }

    public function testReturnsParameterObject(): void
    {
        $result = $this->container->parameter('configKey1');
        $this->assertEquals('value1', $result->value());
    }

    public function testReturnsNullParameter(): void
    {
        $result = $this->container->getParameter('nullKey');
        $this->assertEquals(null, $result);
    }

    public function testBuildReturnsAConfiguredContainer(): void
    {
        $container = $this->container->build([
            'hello' => 'goodbye'
        ]);

        $this->assertInstanceOf(Container::class, $container);
        $this->assertEquals('goodbye', $container->getParameter('hello'));
    }

    public function testReturnsServiceIds(): void
    {
        $this->container->register('foobar', function (Container $container) {
            return new stdClass();
        }, [ 'foobar' => []]);

        self::assertEquals(['foobar'], $this->container->getServiceIds());
    }

    public function testReturnsTags(): void
    {
        $this->container->register('foobar', function (Container $container) {
            return new stdClass();
        }, [ 'foobar' => [
            'foo' => 'bar',
        ]]);
        $this->container->register('barfoo', function (Container $container) {
            return new stdClass();
        }, [ 'foobar' => [
            'bar' => 'foo',
        ]]);

        self::assertEquals(['foobar' => [
            'foobar' => ['foo' => 'bar'],
            'barfoo' => ['bar' => 'foo'],
        ]], $this->container->getTags());
    }
}
