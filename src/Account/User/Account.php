<?php

/*
 * This file is part of the Accountmail Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\AccountmailBundle\Account\User;

use Contao\DataContainer;
use Craffft\AccountmailBundle\Account\Account as AccountParent;

class Account extends AccountParent
{
    protected function isDisabledAccountmail(DataContainer $dc)
    {
        if ($GLOBALS['TL_CONFIG']['disableUserAccountmail']) {
            return true;
        }

        return false;
    }
}
