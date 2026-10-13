<?php

declare(strict_types=1);
namespace In2code\Powermail\ViewHelpers\Validation;

use Exception;
use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Service\CalculatingCaptchaService;
use In2code\Powermail\Domain\Service\ConfigurationService;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Package\Exception as ExceptionCore;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Class CaptchaViewHelper
 */
class CaptchaViewHelper extends AbstractTagBasedViewHelper
{
    protected ?string $error = null;

    /**
     * Constructor
     *
     * @api
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('field', Field::class, 'powermail field', true);
        $this->registerArgument('alt', 'string', 'alt attribute');
        $this->registerArgument('class', 'string', 'class attribute');
        $this->registerArgument('id', 'string', 'id attribute');
    }

    /**
     * Render image or error message if image could not be created
     *
     * @return string
     */
    public function render(): string
    {
        try {
            return $this->getImage();
        } catch (Exception $exception) {
            return $this->getErrorMessage($exception);
        }
    }

    /**
     * @throws ExceptionCore
     */
    protected function getImage(): string
    {
        $this->tag->setTagName('img');
        $this->tag->addAttribute('src', $this->getImageSource($this->arguments['field']));
        $this->tag->addAttribute('alt', $this->arguments['alt']);
        $this->tag->addAttribute('class', $this->arguments['class']);
        $this->tag->addAttribute('id', $this->arguments['id']);
        return $this->tag->render();
    }

    protected function getErrorMessage(Exception $exception): string
    {
        $this->tag->setTagName('p');
        $this->tag->addAttribute('class', 'bg-danger');
        $this->tag->forceClosingTag(true);
        $this->tag->setContent($exception->getMessage());
        return $this->tag->render();
    }

    /**
     * @return string image URL
     * @throws ExceptionCore
     */
    protected function getImageSource(Field $field): string
    {
        $captchaService = GeneralUtility::makeInstance(CalculatingCaptchaService::class);
        $source = (string)$captchaService->render($field);
        if ($source === '' || PathUtility::hasProtocolAndScheme($source) || str_starts_with($source, '/')) {
            return $source;
        }
        // The service returns a path relative to the public folder ("typo3temp/..."). Since TYPO3 v12
        // absRefPrefix no longer rewrites ViewHelper output, so on any page below the root the browser
        // resolved it against the page URL and the captcha image was a 404.
        return PathUtility::getAbsoluteWebPath(Environment::getPublicPath() . '/' . $source);
    }

    public function getSettings(): array
    {
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class);
        return $configurationService->getTypoScriptSettings();
    }
}
