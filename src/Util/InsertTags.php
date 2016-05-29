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

use Contao\Session;

class InsertTags
{
    public function replaceInsertTags($strTag)
    {
        $flags = explode('|', $strTag);
        $tag = array_shift($flags);
        list($strName, $strValue) = explode('::', $tag);

        if ($strName == 'accountmail') {
            $objSession = Session::getInstance();
            $arrData = $objSession->get('ACCOUNTMAIL_PARAMETERS');

            if (isset($arrData[$strValue])) {
                return $arrData[$strValue];
            }
        }

        return false;
    }
}
