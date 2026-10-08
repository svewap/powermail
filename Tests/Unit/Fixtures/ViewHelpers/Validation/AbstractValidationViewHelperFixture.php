<?php

namespace In2code\Powermail\Tests\Unit\Fixtures\ViewHelpers\Validation;

use In2code\Powermail\ViewHelpers\Validation\AbstractValidationViewHelper;

/**
 * Concrete fixture: Fluid 5 declares AbstractViewHelper::render() abstract,
 * so the abstract class itself can no longer be mocked directly
 */
class AbstractValidationViewHelperFixture extends AbstractValidationViewHelper
{
    public function render(): string
    {
        return '';
    }
}
