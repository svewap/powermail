<?php
declare(strict_types=1);
namespace In2code\Powermail\Finisher;

use In2code\Powermail\Domain\Repository\MailRepository;
use In2code\Powermail\Domain\Service\ConfigurationService;
use In2code\Powermail\Utility\ObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\Exception;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException;

/**
 * SendParametersFinisher to send params via CURL
 */
class SendParametersFinisher extends AbstractFinisher
{

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * TypoScript configuration part sendPost
     *
     * Example configuration TypoScript:
     * plugin.tx_powermail.settings.setup.marketing.sendPost {
     *      _enable = TEXT
     *      _enable.value = 1
     *      targetUrl = http://target.com/
     *      values = COA
     *      values {
     *          10 = TEXT
     *          10 {
     *              field = firstname
     *              wrap = &fn=|
     *          }
     *      }
     * }
     *
     * @var array
     */
    protected $configuration;

    /**
     * Send values via curl to a third party software
     *
     * @return void
     */
    public function sendFinisher(): void
    {
        if ($this->isEnabled()) {
            $curlSettings = $this->getCurlSettings();
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $curlSettings['url']);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $curlSettings['params']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if (!empty($curlSettings['username']) && !empty($curlSettings['password'])) {
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($curl, CURLOPT_USERPWD, $curlSettings['username'] . ':' . $curlSettings['password']);
            }
            curl_exec($curl);
            curl_close($curl);
            $this->writeToDevelopmentLog();
        }
    }

    /**
     * @return void
     */
    protected function writeToDevelopmentLog(): void
    {
        if ($this->configuration['debug']) {
            $logger = ObjectUtility::getLogger(__CLASS__);
            $logger->info('SendPost Values', $this->getCurlSettings());
        }
    }

    /**
     * @return array
     */
    protected function getCurlSettings(): array
    {
        return [
            'url' => $this->configuration['targetUrl'],
            'username' => $this->configuration['username'],
            'password' => $this->configuration['password'],
            'params' => $this->getValues()
        ];
    }

    /**
     * Get parameters
     *
     * @return string
     */
    protected function getValues(): string
    {
        return $this->contentObject->cObjGetSingle($this->configuration['values'], $this->configuration['values.']);
    }

    /**
     * Check if sendPost is activated
     *      - if it's enabled via TypoScript
     *      - if form was final submitted (without optin)
     *
     * @return bool
     */
    protected function isEnabled(): bool
    {
        return $this->contentObject->cObjGetSingle(
            $this->configuration['_enable'],
            $this->configuration['_enable.']
        ) === '1' && $this->isFormSubmitted();
    }

    /**
     * @return void
     * @throws Exception
     * @throws InvalidSlotException
     * @throws InvalidSlotReturnException
     */
    public function initializeFinisher(): void
    {
        // @extensionScannerIgnoreLine Seems to be a false positive: getContentObject() is still correct in 9.0
        $mailRepository = GeneralUtility::makeInstance(MailRepository::class);
        $this->contentObject->start($mailRepository->getVariablesWithMarkersFromMail($this->mail));
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class);
        $configuration = $configurationService->getTypoScriptConfiguration();
        $this->configuration = $configuration['marketing.']['sendPost.'];
    }

    /**
     * @param ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }
}
