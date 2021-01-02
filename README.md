Phpactor Container
==================

Phpactor's minimal PSR-compliant dependency injection container, featuring tags and parameters

```php
$container = new PhpactorContainer([
     'param1' => 'value1',
     'param2' => 'value2',
     // ...
]);

$container->register('Foobar', function (Container $container) {
    return new MyClass(
        $container->get(SomeOtherClass::class),
        $container->getParameter('param1')
    );
};
```

Contributing
------------

This package is open source and welcomes contributions! Feel free to open a
pull request on this repository.

Support
-------

- Create an issue on the main [Phpactor](https://github.com/phpactor/phpactor) repository.
- Join the `#phpactor` channel on the Slack [Symfony Devs](https://symfony.com/slack-invite) channel.

