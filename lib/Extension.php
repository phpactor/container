<?php

namespace Phpactor\Container;

use Phpactor\MapResolver\Resolver;

interface Extension
{
    /**
     * Register services with the container.
     *
     * @return void
     */
    public function load(ContainerBuilder $container);

    /**
     * Return the default parameters for the container.
     *
     * @return void
     */
    public function configure(Resolver $schema);
}
