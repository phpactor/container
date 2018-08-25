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
