<?php

/*
 * This file is part of the Accountmail Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Config
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array(
    'Craffft\\AccountmailBundle\\Account\\User\\Account',
    'handlePalettesAndSubpalettes'
);
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array(
    'Craffft\\AccountmailBundle\\Account\\User\\Account',
    'setAutoPassword'
);
$GLOBALS['TL_DCA']['tl_user']['config']['onsubmit_callback'][] = array(
    'Craffft\\AccountmailBundle\\Account\\User\\Account',
    'sendPasswordEmail'
);

// Palettes
if (is_array($GLOBALS['TL_DCA']['tl_user']['palettes'])) {
    foreach ($GLOBALS['TL_DCA']['tl_user']['palettes'] as $k => $v) {
        if ($k == 'login') {
            continue;
        }

        $GLOBALS['TL_DCA']['tl_user']['palettes'][$k] = preg_replace('#([,;]+)password([,;]?)#',
            '$1password,sendLoginData$2', $v);
    }
}

// Fields
$GLOBALS['TL_DCA']['tl_user']['fields']['sendLoginData'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['sendLoginData'],
    'exclude'   => true,
    'default'   => 1,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['loginDataAlreadySent'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['loginDataAlreadySent'],
    'sql'   => "char(1) NOT NULL default ''"
);
