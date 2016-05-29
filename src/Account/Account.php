<?php

/*
 * This file is part of the Accountmail Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\AccountmailBundle\Account;

use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Date;
use Contao\Encryption;
use Contao\Input;
use Contao\Message;
use Contao\Model;
use Craffft\AccountmailBundle\Util\Email;

abstract class Account extends Controller
{
    /**
     * @var array
     */
    protected $arrParameters = array();

    /**
     * @param DataContainer $dc
     * @return mixed
     */
    abstract protected function isDisabledAccountmail(DataContainer $dc);

    /**
     * @param null $dc
     */
    public function handlePalettesAndSubpalettes($dc = null)
    {
        // Front end call
        if (!$dc instanceof DataContainer) {
            return;
        }

        if ($this->isDisabledAccountmail($dc)) {
            // Palettes
            if (is_array($GLOBALS['TL_DCA'][$dc->table]['palettes'])) {
                foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $k => $v) {
                    $GLOBALS['TL_DCA'][$dc->table]['palettes'][$k] = str_replace(
                        ',sendLoginData',
                        '',
                        $GLOBALS['TL_DCA'][$dc->table]['palettes'][$k]
                    );
                }
            }

            // Subpalettes
            if (is_array($GLOBALS['TL_DCA'][$dc->table]['subpalettes'])) {
                foreach ($GLOBALS['TL_DCA'][$dc->table]['subpalettes'] as $k => $v) {
                    $GLOBALS['TL_DCA'][$dc->table]['subpalettes'][$k] = str_replace(
                        ',sendLoginData',
                        '',
                        $GLOBALS['TL_DCA'][$dc->table]['subpalettes'][$k]
                    );
                }
            }
        }
    }

    /**
     * @param null $dc
     * @throws \Exception
     */
    public function setAutoPassword($dc = null)
    {
        // Front end call
        if (!$dc instanceof DataContainer) {
            return;
        }

        if ($this->isDisabledAccountmail($dc)) {
            return;
        }

        $intId = $dc->id;

        if (Input::get('act') == 'overrideAll' && Input::get('fields') && $intId === null) {
            // Define indicator for given or not given password on overrideAll mode
            if (!isset($GLOBALS['ACCOUNTMAIL']['AUTO_PASSWORD'])) {
                $strPassword = $this->getPostPassword();
                $GLOBALS['ACCOUNTMAIL']['AUTO_PASSWORD'] = ($strPassword == '' || $strPassword == '*****') ? true : false;

                if ($GLOBALS['ACCOUNTMAIL']['AUTO_PASSWORD'] === true) {
                    // Set password, that no error occurs with "password not set"
                    $strNewPassword = substr(str_shuffle('abcdefghkmnpqrstuvwxyzABCDEFGHKMNOPQRSTUVWXYZ0123456789'), 0, 8);
                    $this->setPostPassword($strNewPassword);
                }

                Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['pw_changed']);
            }

            return;
        }

        $strPassword = $this->getPostPassword($intId);

        if ($strPassword !== null && $strPassword == '') {
            $strModel = Model::getClassFromTable($dc->table);
            $objAccount = $strModel::findByPk($intId);

            if ($objAccount !== null) {
                $strNewPassword = substr(str_shuffle('abcdefghkmnpqrstuvwxyzABCDEFGHKMNOPQRSTUVWXYZ0123456789'), 0, 8);
                $this->setPostPassword($strNewPassword, $intId);
                Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['pw_changed']);

                $objAccount->password = Encryption::hash($strNewPassword);
                $objAccount->save();
            }
        }
    }

    /**
     * @param null $dc
     */
    public function sendPasswordEmail($dc = null)
    {
        // Front end call
        if (!$dc instanceof DataContainer) {
            return;
        }

        if ($this->isDisabledAccountmail($dc)) {
            return;
        }

        // Return if there is no active record
        if (!$dc->activeRecord) {
            return;
        }

        // Send login data
        if ($dc->activeRecord->sendLoginData == 1) {
            // Set different passwords on overrideAll mode, when no password was given
            if (Input::get('act') == 'overrideAll' && $GLOBALS['ACCOUNTMAIL']['AUTO_PASSWORD'] === true) {
                $this->setPostPassword('', $dc->id);
            }

            if ($this->getPostPassword($dc->id) == '' || $this->getPostPassword($dc->id) == '*****') {
                // Set empty password
                $this->setPostPassword('', $dc->id);

                // Generate new password
                $this->setAutoPassword($dc);
            }

            if ($this->getPostPassword($dc->id) != '' && $this->getPostPassword($dc->id) != '*****') {
                $strType = $this->getType($dc);
                $arrParameters = $this->getParameters($dc);
                $strLanguage = $this->getAccountLanguage($dc);

                if (!strlen($strType)) {
                    return;
                }

                if ($this->sendEmail($dc->activeRecord->email, $strType, $arrParameters, $strLanguage)) {
                    // Disable sendLoginData field
                    $dc->activeRecord->sendLoginData = '';

                    // Disable sendLoginData field in the database
                    Database::getInstance()->prepare("UPDATE " . $dc->table . " SET sendLoginData='', loginDataAlreadySent='1' WHERE id=?")->execute($dc->activeRecord->id);

                    // Show success message
                    Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['login_data_send']);
                } else {
                    // Show error message
                    Message::addError($GLOBALS['TL_LANG']['MSC']['login_data_not_send']);
                }
            }
        }
    }

    /**
     * @param null $intId
     * @return mixed
     */
    protected function getPostPassword($intId = null)
    {
        return (Input::get('act') == 'editAll' && is_numeric($intId)) ? Input::post('password_' . $intId) : Input::post('password');
    }

    /**
     * @param $strNewPassword
     * @param null $intId
     */
    protected function setPostPassword($strNewPassword, $intId = null)
    {
        if ((Input::get('act') == 'editAll' && is_numeric($intId))) {
            Input::setPost('password_' . $intId, $strNewPassword);
            Input::setPost('password_' . $intId . '_confirm', $strNewPassword);
        } else {
            Input::setPost('password', $strNewPassword);
            Input::setPost('password_confirm', $strNewPassword);
        }
    }

    /**
     * @param DataContainer $dc
     * @return string
     */
    protected function getType(DataContainer $dc)
    {
        $strType = 'emailNew%s';

        if ($dc->activeRecord->loginDataAlreadySent) {
            $strType = 'emailChanged%sPassword';
        }

        switch ($dc->table) {
            case 'tl_member':
                $strType = sprintf($strType, 'Member');
                break;

            case 'tl_user':
                $strType = sprintf($strType, 'User');
                break;

            default:
                $strType = '';
                break;
        }

        return $strType;
    }

    /**
     * @param DataContainer $dc
     * @return array
     */
    protected function getParameters(DataContainer $dc)
    {
        if (!$dc->activeRecord) {
            return array();
        }

        $dc->loadDataContainer($dc->table);

        $strType = $this->getType($dc);

        $arrParameters = array();
        $arrFields = $GLOBALS['TL_DCA'][$dc->table]['fields'];

        if (is_array($arrFields)) {
            foreach ($arrFields as $strField => $arrField) {
                if (isset($dc->activeRecord->$strField)) {
                    $arrParameters[$strField] = $this->renderParameterValue(
                        $dc->table,
                        $this->getAccountLanguage($dc),
                        $strField,
                        $dc->activeRecord->$strField);
                }
            }
        }

        // Replace the password, because it's generated new
        $arrParameters['password'] = $this->getPostPassword($dc->id);

        // HOOK: replaceAccountmailParameters
        if (isset($GLOBALS['TL_HOOKS']['replaceAccountmailParameters']) && is_array($GLOBALS['TL_HOOKS']['replaceAccountmailParameters'])) {
            foreach ($GLOBALS['TL_HOOKS']['replaceAccountmailParameters'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $arrParameters = $this->$callback[0]->$callback[1]($strType, $arrParameters, $dc);
                } elseif (is_callable($callback)) {
                    $arrParameters = $callback($strType, $arrParameters, $dc);
                }
            }
        }

        return $arrParameters;
    }

    /**
     * @param $strTable
     * @param $strLanguage
     * @param $strName
     * @param $varValue
     * @return string
     */
    protected function renderParameterValue($strTable, $strLanguage, $strName, $varValue)
    {
        if ($varValue == '') {
            return '';
        }

        $this->loadLanguageFile('default', $strLanguage, true);
        $this->loadLanguageFile($strTable, $strLanguage, true);
        $this->loadDataContainer($strTable);

        if ($GLOBALS['TL_DCA'][$strTable]['fields'][$strName]['inputType'] == 'password') {
            return '';
        }

        $varValue = deserialize($varValue);
        $rgxp = $GLOBALS['TL_DCA'][$strTable]['fields'][$strName]['eval']['rgxp'];
        $opts = $GLOBALS['TL_DCA'][$strTable]['fields'][$strName]['options'];
        $rfrc = $GLOBALS['TL_DCA'][$strTable]['fields'][$strName]['reference'];

        if ($rgxp == 'date') {
            $varValue = Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $varValue);
        } elseif ($rgxp == 'time') {
            $varValue = Date::parse($GLOBALS['TL_CONFIG']['timeFormat'], $varValue);
        } elseif ($rgxp == 'datim') {
            $varValue = Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $varValue);
        } elseif (is_array($varValue)) {
            $varValue = implode(', ', $varValue);
        } elseif (is_array($opts) && array_is_assoc($opts)) {
            $varValue = isset($opts[$varValue]) ? $opts[$varValue] : $varValue;
        } elseif (is_array($rfrc)) {
            $varValue = isset($rfrc[$varValue]) ? ((is_array($rfrc[$varValue])) ? $rfrc[$varValue][0] : $rfrc[$varValue]) : $varValue;
        }

        $varValue = specialchars($varValue);

        return (string)$varValue;
    }

    /**
     * @param DataContainer $dc
     * @return string
     */
    protected function getAccountLanguage(DataContainer $dc)
    {
        if ($dc->activeRecord->language) {
            return $dc->activeRecord->language;
        }

        return '';
    }

    /**
     * @param $strRecipient
     * @param $strType
     * @param $arrParameters
     * @param null $strForceLanguage
     * @return bool
     */
    protected function sendEmail($strRecipient, $strType, $arrParameters, $strForceLanguage = null)
    {
        $objEmail = new Email($strType, $strForceLanguage);

        if (is_array($arrParameters)) {
            foreach ($arrParameters as $k => $v) {
                $objEmail->addParameter($k, $v);
            }
        }

        // Send email
        return $objEmail->sendTo($strRecipient);
    }
}
