<?php

namespace Phpactor\Container;

use Closure;
use Phpactor\MapResolver\Resolver;
use RuntimeException;

class PhpactorContainer implements Container, ContainerBuilder
{
    const PARAM_EXTENSION_CLASSES = 'container.extension_classes';

    /**
     * @var array
     */
    private $tags;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $factories;

    /**
     * @var array
     */
    private $services;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public static function fromExtensions(array $extensionClasses, array $parameters = []): Container
    {
        $container = new self();

        $extensions = array_map(function (string $class) {
            return new $class;
        }, $extensionClasses);

        $resolver = new Resolver();
        $resolver->setDefaults([
            self::PARAM_EXTENSION_CLASSES => $extensionClasses
        ]);
        foreach ($extensions as $extension) {
            $extension->configure($resolver);
        }

        $parameters = $resolver->resolve($parameters);

        foreach ($extensions as $extension) {
            $extension->load($container);
        }

        return $container->build($parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        if (isset($this->services[$id])) {
            return $this->services[$id];
        }

        if (!isset($this->factories[$id])) {
            throw new RuntimeException(sprintf(
                'No service with ID "%s" exists',
                $id
            ));
        }

        $this->services[$id] = $this->factories[$id]($this);

        return $this->services[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return array_key_exists($id, $this->factories);
    }

    public function getServiceIdsForTag(string $tag): array
    {
        if (false === isset($this->tags[$tag])) {
            return [];
        }

        return $this->tags[$tag];
    }

    public function register(string $serviceId, Closure $factory, array $tags = [])
    {
        $this->factories[$serviceId] = $factory;

        foreach ($tags as $tagName => $tagAttrs) {
            if (false === isset($this->tags[$tagName])) {
                $this->tags[$tagName] = [];
            }

            $this->tags[$tagName][$serviceId] = $tagAttrs;
        }
    }

    public function getParameter(string $name)
    {
        if (!array_key_exists($name, $this->parameters)) {
            throw new RuntimeException(sprintf(
                'Unknown parameter "%s", known parameters "%s"',
                $name,
                implode('", "', array_keys($this->parameters))
            ));
        }

        return $this->parameters[$name];
    }

    public function build(array $parameters): Container
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
