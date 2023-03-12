<?php

namespace Phpactor\Container;

use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface
{
    /**
     * @template T of object
     * @param class-string<T>|string $id
     * @return ($id is class-string<T> ? T : mixed)
     */
    public function get($id);

    /**
     * @template T of object
     * @param class-string<T> $expected
     * @return T
     */
    public function expect(string $id, string $expected): object;

    /**
     * Return array of serviceId to tag names and attributes
     *
     * @return array<string,array<string,mixed>>
     */
    public function getServiceIdsForTag(string $tag): array;

    /**
     * @return mixed
     * @deprecated Use parameter() to return a value object instead.
     */
    public function getParameter(string $name);

    public function parameter(string $name): Parameter;

    /**
     * @return array<string,mixed>
     */
    public function getParameters(): array;

    /**
     * Return all tags for the container
     *
     * @return array<string, array<string, array<string,mixed>>>
     */
    public function getTags(): array;

    /**
     * Return all service IDs
     *
     * @return list<string>
     */
    public function getServiceIds(): array;
}
