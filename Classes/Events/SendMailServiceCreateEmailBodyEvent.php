<?php

declare(strict_types=1);
namespace In2code\Powermail\Events;

use In2code\Powermail\Domain\Service\Mail\SendMailService;
use TYPO3\CMS\Core\View\ViewInterface;

final class SendMailServiceCreateEmailBodyEvent
{
    public function __construct(protected ViewInterface $view, protected array $email, protected SendMailService $sendMailService)
    {
    }

    public function getView(): ViewInterface
    {
        return $this->view;
    }

    public function setView(ViewInterface $view): SendMailServiceCreateEmailBodyEvent
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @deprecated since powermail 14, use getView() instead. Returns the new ViewInterface, not StandaloneView.
     */
    public function getStandaloneView(): ViewInterface
    {
        return $this->view;
    }

    /**
     * @deprecated since powermail 14, use setView() instead. Accepts the new ViewInterface, not StandaloneView.
     */
    public function setStandaloneView(ViewInterface $view): SendMailServiceCreateEmailBodyEvent
    {
        $this->view = $view;
        return $this;
    }

    public function getEmail(): array
    {
        return $this->email;
    }

    public function setEmail(array $email): SendMailServiceCreateEmailBodyEvent
    {
        $this->email = $email;
        return $this;
    }

    public function getSendMailService(): SendMailService
    {
        return $this->sendMailService;
    }
}