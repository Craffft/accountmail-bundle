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

use Contao\Controller;

class Helpwizard extends Controller
{
    /**
     * @return array
     */
    public function getHelpwizardReferencesByMember()
    {
        return $this->getHelpwizardReferencesBy('tl_member');
    }

    /**
     * @return array
     */
    public function getHelpwizardReferencesByUser()
    {
        return $this->getHelpwizardReferencesBy('tl_user');
    }

    /**
     * @param $strTable
     * @return array
     */
    protected function getHelpwizardReferencesBy($strTable)
    {
        $arrReferences = array();

        $this->loadLanguageFile($strTable);
        $this->loadDataContainer($strTable);

        if (isset($GLOBALS['TL_DCA'][$strTable]['fields']) && is_array($GLOBALS['TL_DCA'][$strTable]['fields'])) {
            foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $strField => $arrValues) {
                $strLabel = $strField;

                if (isset($arrValues['label'][0]) && strlen($arrValues['label'][0])) {
                    $strLabel = $arrValues['label'][0];
                }

                $arrReferences[] = array(
                    sprintf('{{%s::%s|%s}}', 'accountmail', $strField, 'refresh'),
                    sprintf($GLOBALS['TL_LANG']['MSC']['helpwizard_insert_tag'], $strLabel)
                );
            }
        }

        return $arrReferences;
    }
}
