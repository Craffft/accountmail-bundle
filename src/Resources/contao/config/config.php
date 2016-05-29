<?php

/*
 * This file is part of the Accountmail Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * EMAIL CONFIGURATION
 */
$GLOBALS['TL_EMAIL'] = array
(
    'emailNewMember',
    'emailChangedMemberPassword',
    'emailNewUser',
    'emailChangedUserPassword'
);

/**
 * BACK END MODULES
 */
$GLOBALS['BE_MOD']['content']['email'] = array
(
    'tables' => array('tl_email'),
    'icon'   => 'bundle/craffftaccountmail/icon.png'
);

/**
 * HOOKS
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('\\Craffft\\AccountmailBundle\\Util\\InsertTags', 'replaceInsertTags');
