<?php

declare(strict_types=1);
namespace In2code\Powermail\ViewHelpers\Misc;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class CreateRowTagsViewHelper
 */
class CreateRowTagsViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('columns', 'int', 'number of columns - 0 disables function', true);
        $this->registerArgument('iteration', 'array', 'Field iteration array', true);
        $this->registerArgument('tagName', 'string', 'Tag to render');
        $this->registerArgument('class', 'string', 'CSS class');
        $this->registerArgument('additionalAttributes', 'array', 'Any attributes to render');
    }

    public function render(): string
    {
        $arguments = $this->arguments;
        $content = '';
        if ((int)$arguments['columns'] > 0) {
            $content .= self::getBeginningTag($arguments);
            $content .= (string)$this->renderChildren();
            $content .= self::getEndingTag($arguments);
        } else {
            $content .= (string)$this->renderChildren();
        }

        return $content;
    }

    public static function getBeginningTag(array $arguments): string
    {
        if (self::shouldAddBeginningTag($arguments)) {
            return '<' . self::getTagName($arguments) . self::getAttributes($arguments) . '>';
        }

        return '';
    }

    public static function getEndingTag(array $arguments): string
    {
        if (self::shouldAddEndingTag($arguments)) {
            return '</' . self::getTagName($arguments) . '>';
        }

        return '';
    }

    protected static function getTagName(array $arguments): string
    {
        if (!empty($arguments['tagName'])) {
            return $arguments['tagName'];
        }

        return 'div';
    }

    protected static function getAttributes(array $arguments): string
    {
        $attributes = '';
        if (!empty($arguments['additionalAttributes'])) {
            foreach ($arguments['additionalAttributes'] as $key => $value) {
                $attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        if (!empty($arguments['class'])) {
            $attributes .= ' class="' . $arguments['class'] . '"';
        }

        return $attributes;
    }

    protected static function shouldAddBeginningTag(array $arguments): bool
    {
        return $arguments['iteration']['isFirst'] === true
            || !(($arguments['iteration']['cycle'] - 1) % $arguments['columns']);
    }

    protected static function shouldAddEndingTag(array $arguments): bool
    {
        return $arguments['iteration']['isLast'] === true
            || !($arguments['iteration']['cycle'] % $arguments['columns']);
    }
}
