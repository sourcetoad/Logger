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
            ],
            'deeply nested property' => [
                'input' => [
                    [
                        'data' => [
                            0 => [
                                'id' => 'Value',
                                'person' => [
                                    'updated_at' => 'Value'
                                ]
                            ]
                        ]
                    ]
                ],
                'expected' => [
                    'id',
                    'person.updated_at'
                ]
            ],
            'deeply nested numeric property' => [
                'input' => [
                    [
                        'data' => [
                            0 => [
                                'id' => 'Value',
                                'persons' => [
                                    0 => [
                                        'updated_at' => 'Value'
                                    ],
                                    1 => [
                                        'updated_at' => 'Value'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'expected' => [
                    'id',
                    'persons.updated_at'
                ]
            ],
            'entries deeply nested w/ numerics and duplicates' => [
                'input' => [
                    'entries' => [
                        0 => [
                            'content' => [
                                'response' => [
                                    'data' => [
                                        21 => [
                                            'created_at' => 'Value'
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        1 => [
                            'content' => [
                                'response' => [
                                    'data' => [
                                        22 => [
                                            'created_at' => 'Value'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'expected' => [
                    'content.response.created_at'
                ]
            ]
        ];
    }
}
