<?php

namespace Phpactor\Container\Tests\Unit\Schema;

use PHPUnit\Framework\TestCase;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Container\Schema\JsonSchemaBuilder;
use Phpactor\MapResolver\Resolver;

class JsonSchemaBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $extensions = [];
        $extensions[] = $this->createExtension1();

        $json = (new JsonSchemaBuilder('test', $extensions))->dump();
        self::assertJsonStringEqualsJsonString(
            <<<'EOT'
            {
                "$schema": "https =>\/\/json-schema.org\/draft\/2020-12\/schema",
                "title": "test",
                "type": "object",
                "properties": {
                    "bar.foo": {
                        "description": "This does something",
                        "type": [
                            "string"
                        ]
                    },
                    "foo.bar": {
                        "description": null,
                        "type": [
                            "string"
                        ]
                    }
                }
            }
            EOT
            , 
            $json
        );
    }

    private function createExtension1(): Extension
    {
        return new class implements Extension {
            public function configure(Resolver $resolver): void
            {
                $resolver->setDefaults([
                    'bar.foo' => 1234,
                    'foo.bar' => 'bar',
                ]);
                $resolver->setRequired([
                    'bar.foo',
                ]);
                $resolver->setTypes([
                    'bar.foo' => 'string',
                    'foo.bar' => 'string',
                ]);
                $resolver->setDescriptions([
                    'bar.foo' => 'This does something',
                ]);
            }

            public function load(ContainerBuilder $builder): void
            {
            }
        };
    }
}
