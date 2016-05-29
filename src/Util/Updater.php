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

use Contao\ClassLoader;
use Contao\Controller;
use Contao\System;
use TranslationFields\TranslationFieldsModel;

class Updater extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->import('Config');
    }

    /**
     * Add default email contents to the configuration fields of the accountmail extension
     */
    public function addDefaultEmailContents()
    {
        $this->addContentToField('emailFrom', $this->Config->get('adminEmail'));
        $this->addContentToField('emailNewMemberTemplate', 'mail_default');
        $this->addContentToField('emailChangedMemberPasswordTemplate', 'mail_default');
        $this->addContentToField('emailNewUserTemplate', 'mail_default');
        $this->addContentToField('emailChangedUserPasswordTemplate', 'mail_default');

        foreach ($this->getFieldNames() as $strField) {
            $this->addTranslationContentToField($strField);
        }
    }

    /**
     * @param $strField
     * @param $strValue
     */
    protected function addContentToField($strField, $strValue)
    {
        if ($this->Config->get($strField) === null) {
            $this->Config->persist($strField, $strValue);
        }
    }

    /**
     * @return array
     */
    protected function getFieldNames()
    {
        $arrFields = array();
        $arrFields[] = 'emailFromName';

        foreach ($GLOBALS['TL_EMAIL'] as $strField) {
            $arrFields[] = $strField . 'Subject';
            $arrFields[] = $strField . 'Content';
        }

        return $arrFields;
    }

    /**
     * @param $strField
     */
    protected function addTranslationContentToField($strField)
    {
        if ($this->Config->get($strField) === null) {
            $arrValues = array();

            System::loadLanguageFile('tl_email', 'de', true);
            $arrValues['de'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];

            System::loadLanguageFile('tl_email', 'en', true);
            $arrValues['en'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];

            // Load translation file by current language
            System::loadLanguageFile('tl_email', null, true);

            $this->Config->persist(
                $strField,
                TranslationFieldsModel::saveValuesAndReturnFid($arrValues)
            );
        }
    }
}
