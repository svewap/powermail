<?php

namespace In2code\Powermail\Tests\Unit\Utility;

use In2code\Powermail\Utility\TypoScriptUtility;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class TypoScriptUtilityTest
 * @coversDefaultClass \In2code\Powermail\Utility\TypoScriptUtility
 */
#[CoversClass(\In2code\Powermail\Utility\TypoScriptUtility::class)]
class TypoScriptUtilityTest extends UnitTestCase
{
    /**
     * @covers ::getCaptchaExtensionFromSettings
     */
    #[Test]
    public function getCaptchaExtensionFromSettingsReturnsString(): void
    {
        $settings = [
            'captcha' => [
                'use' => [
                    'captcha',
                ],
            ],
        ];
        $value = TypoScriptUtility::getCaptchaExtensionFromSettings($settings);
        self::assertSame('default', $value);
    }
}
