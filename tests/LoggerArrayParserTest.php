<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Test;

use Sourcetoad\Logger\Helpers\DataArrayParser;

class LoggerArrayParserTest extends TestCase
{
    /** @dataProvider dataProvider */
    public function testDedupeParser(array $input, array $expected): void
    {
        $this->assertEquals($expected, DataArrayParser::dedupe($input));
    }

    public function dataProvider(): array
    {
        return [
            'empty' => [
                'input' => [],
                'expected' => []
            ],
            'single key' => [
                'input' => [
                    'username' => 'Foo'
                ],
                'expected' => [
                    'username'
                ]
            ],
            'multiple keys' => [
                'input' => [
                    'username' => 'Foo',
                    'password' => 'Bar'
                ],
                'expected' => [
                    'password',
                    'username',
                ]
            ],
            'sorted properties' => [
                'input' => [
                    'z' => 'value',
                    'a' => 'value',
                ],
                'expected' => [
                    'a',
                    'z'
                ]
            ],
            'duplicate properties' => [
                'input' => [
                    0 => [
                        'first_name' => 'Name',
                        'last_name' => 'Last',
                    ],
                    1 => [
                        'first_name' => 'Name2',
                        'last_name' => 'Last2'
                    ],
                    2 => [
                        'first_name' => 'Name3',
                        'last_name' => 'Last3'
                    ]
                ],
                'expected' => [
                    'first_name',
                    'last_name'
                ]
            ]
        ];
    }
}
