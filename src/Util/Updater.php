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
use Contao\Config;
use Contao\Controller;
use Contao\Database;
use Contao\System;
use TranslationFields\TranslationFieldsModel;

class Updater
{
    /**
     * Add translation field database table and fields if the are not existing already
     */
    public function addTranslationFieldTableAndFields()
    {
        $db = Database::getInstance();

        if (!$db->tableExists('tl_translation_fields')) {
            $sql = trim("
                CREATE TABLE `tl_translation_fields` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
                  `fid` int(10) unsigned NOT NULL DEFAULT '0',
                  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `fid_language` (`fid`,`language`),
                  KEY `fid` (`fid`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
            ");
            $db->execute($sql);
        }
    }

    /**
     * Add default email contents to the configuration fields of the accountmail extension
     */
    public function addDefaultEmailContents()
    {
        $config = Config::getInstance();
        $this->addContentToField('emailFrom', $config->get('adminEmail'));
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
        $config = Config::getInstance();

        if ($config->get($strField) === null) {
            $config->persist($strField, $strValue);
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
        $config = Config::getInstance();

        if ($config->get($strField) === null) {
            $arrValues = array();

            System::loadLanguageFile('tl_email', 'de', true);
            $arrValues['de'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['de_AT'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['de_CH'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['de_DE'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];

            System::loadLanguageFile('tl_email', 'en', true);
            $arrValues['en'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['en_AU'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['en_CA'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['en_GB'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['en_IE'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['en_US'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];
            $arrValues['en_ZA'] = $GLOBALS['TL_LANG']['tl_email']['defaultContents'][$strField];

            // Load translation file by current language
            System::loadLanguageFile('tl_email', null, true);

            $config->persist(
                $strField,
                TranslationFieldsModel::saveValuesAndReturnFid($arrValues)
            );
        }
    }
}
