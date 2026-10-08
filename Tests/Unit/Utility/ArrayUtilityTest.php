<?php

namespace In2code\Powermail\Tests\Unit\Utility;

use In2code\Powermail\Utility\ArrayUtility;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class ArrayUtilityTest
 * @coversDefaultClass \In2code\Powermail\Utility\ArrayUtility
 */
#[CoversClass(\In2code\Powermail\Utility\ArrayUtility::class)]
class ArrayUtilityTest extends UnitTestCase
{
    /**
     * @covers ::getAbcArray
     */
    #[Test]
    public function getAbcArrayReturnsArray(): void
    {
        self::assertSame(
            [
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z',
            ],
            ArrayUtility::getAbcArray()
        );
    }

    /**
     * Data Provider for isJsonArrayReturnsBool()
     */
    public static function isJsonArrayReturnsBoolDataProvider(): array
    {
        return [
            [
                json_encode(['a']),
                true,
            ],
            [
                json_encode('a,b:c'),
                false,
            ],
            [
                json_encode(['object' => 'a']),
                true,
            ],
            [
                json_encode([['title' => 'test2'], ['title' => 'test2']]),
                true,
            ],
            [
                'a,b:c',
                false,
            ],
            [
                '',
                false,
            ],
        ];
    }

    /**
     * @param string $value
     * @param bool $expectedResult
     * @covers ::isJsonArray
     */
    #[Test]
    #[DataProvider('isJsonArrayReturnsBoolDataProvider')]
    public function isJsonArrayReturnsBool($value, $expectedResult): void
    {
        self::assertSame($expectedResult, ArrayUtility::isJsonArray($value));
    }

    /**
     * Data Provider for htmlspecialcharsOnArrayReturnsArray()
     */
    public static function htmlspecialcharsOnArrayReturnsArrayDataProvider(): array
    {
        return [
            [
                [
                    '<te&st>',
                ],
                [
                    '&lt;te&amp;st&gt;',
                ],
            ],
            [
                [
                    '<test>',
                    [
                        '<test>' => '<test>',
                    ],
                ],
                [
                    '&lt;test&gt;',
                    [
                        '&lt;test&gt;' => '&lt;test&gt;',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $array
     * @param array $expectedResult
     * @covers ::htmlspecialcharsOnArray
     */
    #[Test]
    #[DataProvider('htmlspecialcharsOnArrayReturnsArrayDataProvider')]
    public function htmlspecialcharsOnArrayReturnsArray($array, $expectedResult): void
    {
        self::assertSame($expectedResult, ArrayUtility::htmlspecialcharsOnArray($array));
    }

    /**
     * @covers ::getValueByPath
     */
    public function testGetValueByPath(): void
    {
        $array = [
            'foo' => [
                'bar' => 123,
            ],
        ];
        self::assertSame(123, ArrayUtility::getValueByPath($array, 'foo.bar'));
        self::assertSame('', ArrayUtility::getValueByPath($array, 'foo.test'));
    }

    /**
     * Data Provider for htmlspecialcharsOnArrayReturnsArray()
     */
    public static function flattenDataProvider(): array
    {
        return [
            'simple' => [
                [
                    [
                        'title' => 'foo',
                    ],
                    [
                        'title' => 'bar',
                    ],
                ],
                'title',
                [
                    'foo',
                    'bar',
                ],
            ],
            'multiple keys' => [
                [
                    [
                        'uid' => 123,
                        'title' => 'foo',
                    ],
                    [
                        'uid' => 234,
                        'title' => 'bar',
                    ],
                ],
                'title',
                [
                    'foo',
                    'bar',
                ],
            ],
            'invalid' => [
                [],
                '',
                [],
            ],
        ];
    }

    /**
     * @covers ::flatten
     */
    #[DataProvider('flattenDataProvider')]
    public function testFlatten(array $testcase, string $key, array $expected): void
    {
        self::assertSame($expected, ArrayUtility::flatten($testcase, $key));
    }
}
