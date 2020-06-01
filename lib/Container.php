<?php

namespace Phpactor\Container;

use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface
{
    /**
     * Return array of serviceId to tag names and attributes
     *
     * @return array<string,array<string,array>>
     */
    public function getServiceIdsForTag(string $tag): array;

    /**
     * @return mixed
     */
    public function getParameter(string $name);

    /**
     * @return array<mixed>
     */
    public function getParameters(): array;

    /**
     * Return all tags for the container
     *
     * @return array<string, array<string, array>>
     */
    public function getTags(): array;

    /**
     * Return all service IDs
     *
     * @return array<string>
     */
    public function getServiceIds(): array;
}
