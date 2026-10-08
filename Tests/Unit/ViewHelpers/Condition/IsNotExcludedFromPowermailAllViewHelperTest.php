<?php

namespace In2code\Powermail\Tests\Unit\ViewHelpers\Condition;

use In2code\Powermail\ViewHelpers\Condition\IsNotExcludedFromPowermailAllViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class IsNotExcludedFromPowermailAllViewHelperTest
 * @coversDefaultClass \In2code\Powermail\ViewHelpers\Condition\IsNotExcludedFromPowermailAllViewHelper
 */
#[CoversClass(\In2code\Powermail\ViewHelpers\Condition\IsNotExcludedFromPowermailAllViewHelper::class)]
class IsNotExcludedFromPowermailAllViewHelperTest extends UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $isNotExcludedFromPowermailAllViewHelperMock;

    public function setUp(): void
    {
        $this->isNotExcludedFromPowermailAllViewHelperMock = $this->getAccessibleMock(
            IsNotExcludedFromPowermailAllViewHelper::class,
            null
        );
    }

    public function tearDown(): void
    {
        unset($this->isNotExcludedFromPowermailAllViewHelperMock);
    }

    /**
     * Dataprovider for getExcludedValuesReturnArray()
     */
    public static function getExcludedValuesReturnArrayDataProvider(): array
    {
        return [
            [
                'createAction',
                [
                    'excludeFromPowermailAllMarker' => [
                        'submitPage' => [
                            'excludeFromFieldTypes' => 'hidden, captcha, input',
                        ],
                    ],
                ],
                'excludeFromFieldTypes',
                [
                    'hidden',
                    'captcha',
                    'input',
                ],
            ],
            [
                'confirmationAction',
                [
                    'excludeFromPowermailAllMarker' => [
                        'confirmationPage' => [
                            'excludeFromFieldTypes' => 'hidden, input',
                        ],
                    ],
                ],
                'excludeFromFieldTypes',
                [
                    'hidden',
                    'input',
                ],
            ],
            [
                'sender',
                [
                    'excludeFromPowermailAllMarker' => [
                        'senderMail' => [
                            'excludeFromMarkerNames' => 'abc, daafsd',
                            'excludeFromFieldTypes' => 'hidden, captcha',
                        ],
                    ],
                ],
                'excludeFromFieldTypes',
                [
                    'hidden',
                    'captcha',
                ],
            ],
            [
                'receiver',
                [
                    'excludeFromPowermailAllMarker' => [
                        'receiverMail' => [
                            'excludeFromMarkerNames' => 'email, firstname',
                            'excludeFromFieldTypes' => 'hidden, input',
                        ],
                    ],
                ],
                'excludeFromMarkerNames',
                [
                    'email',
                    'firstname',
                ],
            ],
            [
                'optin',
                [
                    'excludeFromPowermailAllMarker' => [
                        'optinMail' => [
                            'excludeFromMarkerNames' => 'email, firstname',
                            'excludeFromFieldTypes' => 'hidden, input',
                        ],
                    ],
                ],
                'excludeFromMarkerNames',
                [
                    'email',
                    'firstname',
                ],
            ],
            [
                'optin',
                [
                    'excludeFromPowermailAllMarker' => [
                        'optinMail' => [
                            'excludeFromMarkerNames' => 'email, firstname',
                            'excludeFromFieldTypes' => 'hidden, input',
                        ],
                    ],
                ],
                'excludeFromFieldTypes',
                [
                    'hidden',
                    'input',
                ],
            ],
        ];
    }

    /**
     * @param string $type
     * @param array $settings
     * @param string $configurationType
     * @param array $expectedResult
     * @covers ::render
     * @covers ::getExcludedValues
     */
    #[Test]
    #[DataProvider('getExcludedValuesReturnArrayDataProvider')]
    public function getExcludedValuesReturnArray($type, $settings, $configurationType, $expectedResult): void
    {
        $result = $this->isNotExcludedFromPowermailAllViewHelperMock->_call(
            'getExcludedValues',
            $type,
            $settings,
            $configurationType
        );
        self::assertSame($expectedResult, $result);
    }
}
