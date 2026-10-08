<?php

namespace In2code\Powermail\Tests\Unit\Utility;

use In2code\Powermail\Exception\FileCannotBeCreatedException;
use In2code\Powermail\Tests\Helper\TestingHelper;
use In2code\Powermail\Utility\BasicFileUtility;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class BasicFileUtiltyTest
 * @coversDefaultClass \In2code\Powermail\Utility\BasicFileUtility
 */
#[CoversClass(\In2code\Powermail\Utility\BasicFileUtility::class)]
class BasicFileUtilityTest extends UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        TestingHelper::setDefaultConstants();
    }

    /**
     * @covers ::getFilesFromRelativePath
     */
    #[Test]
    public function getFilesFromRelativePathReturnsString(): void
    {
        // TYPO3 v14 no longer ships public/typo3/ entry scripts, so build an own fixture folder
        $relativePath = 'typo3temp/var/tests/powermail-files/';
        $absolutePath = Environment::getPublicPath() . '/' . $relativePath;
        GeneralUtility::mkdir_deep($absolutePath);
        touch($absolutePath . 'index.php');
        touch($absolutePath . 'install.php');
        try {
            $result = BasicFileUtility::getFilesFromRelativePath($relativePath);
            self::assertSame(['index.php', 'install.php'], $result);
        } finally {
            GeneralUtility::rmdir($absolutePath, true);
        }
    }

    /**
     * @covers ::getPathFromPathAndFilename
     */
    #[Test]
    public function getPathFromPathAndFilenameReturnsString(): void
    {
        $result = BasicFileUtility::getPathFromPathAndFilename('typo3/index.php');
        self::assertSame('typo3', $result);
    }

    /**
     * @covers ::createFolderIfNotExists
     * @throws FileCannotBeCreatedException
     */
    #[Test]
    public function createFolderIfNotExistsReturnsVoid(): void
    {
        $testpath = TestingHelper::getWebRoot() . 'fileadmin/';

        BasicFileUtility::createFolderIfNotExists($testpath);
        self::assertDirectoryExists($testpath);
        GeneralUtility::rmdir($testpath);
    }

    /**
     * @covers ::prependContentToFile
     * @throws FileCannotBeCreatedException
     */
    #[Test]
    public function prependContentToFileReturnsVoid(): void
    {
        $testpath = TestingHelper::getWebRoot() . 'fileadmin/';
        BasicFileUtility::createFolderIfNotExists($testpath);
        $fileName = $testpath . 'unittest.txt';

        BasicFileUtility::prependContentToFile($fileName, 'abc');
        BasicFileUtility::prependContentToFile($fileName, 'def');
        $content = file($fileName);
        GeneralUtility::rmdir($testpath, true);
        self::assertSame(['defabc'], $content);
    }

    /**
     * @covers ::getRelativeFolder
     */
    #[Test]
    public function getRelativeFolderReturnsString(): void
    {
        $testPath = 'typo3conf/ext/powermail/';
        self::assertStringEndsWith(
            $testPath,
            BasicFileUtility::getRelativeFolder(TestingHelper::getWebRoot() . $testPath)
        );
        self::assertSame($testPath, BasicFileUtility::getRelativeFolder($testPath));
    }
}
