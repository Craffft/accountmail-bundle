<?php

/*
 * This file is part of the Accountmail Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\AccountmailBundle\Util;

use Contao\BackendTemplate;
use Contao\BackendUser;
use Contao\Controller;
use Contao\Email as ContaoEmail;
use Contao\Environment;
use Contao\Idna;
use Contao\Session;
use Craffft\TranslationFieldsBundle\Service\Translator;

class Email extends Controller
{
    /**
     * @var
     */
    protected $strType;

    /**
     * @var
     */
    protected $strForceLanguage;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var array
     */
    protected $arrParameters = array();

    /**
     * @param $strType
     * @param null $strForceLanguage
     */
    public function __construct($strType, $strForceLanguage = null)
    {
        if (in_array($strType, $GLOBALS['TL_EMAIL'])) {
            $this->strType = $strType;
        }

        $this->strForceLanguage = $strForceLanguage;
        $this->translator = new Translator();

        // Set default parameters
        $this->addParameter('host', Idna::decode(Environment::get('host')));
        $this->addParameter('admin_name', BackendUser::getInstance()->name);
    }

    /**
     * @param $key
     * @param $varValue
     */
    public function addParameter($key, $varValue)
    {
        $this->arrParameters[$key] = $varValue;
    }

    /**
     * @param $key
     */
    public function removeParameter($key)
    {
        if (isset($this->arrParameters[$key])) {
            unset($this->arrParameters[$key]);
        }
    }

    /**
     * @param $strRecipient
     * @return bool
     */
    public function sendTo($strRecipient)
    {
        if (!$this->strType) {
            return false;
        }

        $objEmail = new ContaoEmail();

        $objEmail->from = $GLOBALS['TL_CONFIG']['emailFrom'];
        $strEmailFromName = $this->translator->translateValue(
            $GLOBALS['TL_CONFIG']['emailFromName'],
            $this->strForceLanguage
        );

        // Add sender name
        if ($strEmailFromName != '') {
            $objEmail->fromName = $strEmailFromName;
        }

        $strSubject = $this->getContent('subject');
        $strContent = $this->getContent();

        $objEmail->embedImages = true;
        $objEmail->imageDir = TL_ROOT . '/';
        $objEmail->subject = $strSubject;

        // Prepare html template
        $objTemplate = new BackendTemplate($this->getEmailTemplate());

        $objTemplate->title = $strSubject;
        $objTemplate->body = $strContent;
        $objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];
        $objTemplate->css = '';
        $objTemplate->recipient = $strRecipient;

        // Parse html template
        $objEmail->html = $objTemplate->parse();

        // Send email
        try {
            $objEmail->sendTo($strRecipient);
        } catch (\Swift_RfcComplianceException $e) {
            return false;
        }

        // Rejected recipients
        if ($objEmail->hasFailures()) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    protected function getEmailTemplate()
    {
        if (isset($GLOBALS['TL_CONFIG'][$this->strType . 'Template'])) {
            return $GLOBALS['TL_CONFIG'][$this->strType . 'Template'];
        }
    }

    /**
     * @param string $strName
     * @return string
     */
    protected function getContent($strName = 'content')
    {
        $strName = ucfirst(strtolower($strName));

        if (isset($GLOBALS['TL_CONFIG'][$this->strType . $strName])) {
            $strContent = $this->translator->translateValue(
                $GLOBALS['TL_CONFIG'][$this->strType . $strName],
                $this->strForceLanguage
            );

            $objSession = Session::getInstance();
            $objSession->set('ACCOUNTMAIL_PARAMETERS', $this->arrParameters);

            $strContent = $this->replaceInsertTags($strContent, false);

            $objSession->remove('ACCOUNTMAIL_PARAMETERS');

            return $strContent;
        }
    }
}
