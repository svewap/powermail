<?php

namespace In2code\Powermail\Tests\Unit\Domain\Service;

use In2code\Powermail\Domain\Service\ExportService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class ExportServiceTest
 * @coversDefaultClass \In2code\Powermail\Domain\Service\ExportService
 */
#[CoversClass(\In2code\Powermail\Domain\Service\ExportService::class)]
class ExportServiceTest extends UnitTestCase
{
    /**
     * @var ExportService
     */
    protected $generalValidatorMock;

    public function setUp(): void
    {
        $this->generalValidatorMock = $this->getAccessibleMock(
            ExportService::class,
            null
        );
    }

    public function tearDown(): void
    {
        unset($this->generalValidatorMock);
    }

    public static function getRelativeTemplatePathAndFileNameReturnsStringDataProvider(): array
    {
        return [
            [
                'csv',
                'Module/ExportCsv.html',
            ],
            [
                'xls',
                'Module/ExportXls.html',
            ],
            [
                'bullshit',
                'Module/ExportXls.html',
            ],
            [
                '',
                'Module/ExportXls.html',
            ],
        ];
    }

    /**
     * @param string $format
     * @param string $expectedResult
     * @covers ::getRelativeTemplatePathAndFileName
     */
    #[Test]
    #[DataProvider('getRelativeTemplatePathAndFileNameReturnsStringDataProvider')]
    public function getRelativeTemplatePathAndFileNameReturnsString($format, $expectedResult): void
    {
        $this->generalValidatorMock->setFormat($format);
        self::assertSame($this->generalValidatorMock->_call('getRelativeTemplatePathAndFileName'), $expectedResult);
    }

    public static function getFormatReturnsStringDataProvider(): array
    {
        return [
            [
                'csv',
                'csv',
            ],
            [
                'xls',
                'xls',
            ],
            [
                '',
                'xls',
            ],
            [
                'XLS',
                'xls',
            ],
            [
                'CSV',
                'xls',
            ],
        ];
    }

    /**
     * @param string $format
     * @param string $expectedResult
     * @covers ::getFormat
     */
    #[Test]
    #[DataProvider('getFormatReturnsStringDataProvider')]
    public function getFormatReturnsString($format, $expectedResult): void
    {
        $this->generalValidatorMock->setFormat($format);
        self::assertSame($this->generalValidatorMock->_call('getFormat'), $expectedResult);
    }
}
