<?php

/*
 * This file is part of the Accountmail Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

\Contao\System::loadLanguageFile('tl_member');

// Config
$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][] = array(
    'Craffft\\AccountmailBundle\\Account\\Member\\Account',
    'handlePalettesAndSubpalettes'
);
$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][] = array(
    'Craffft\\AccountmailBundle\\Account\\Member\\Account',
    'setAutoPassword'
);
$GLOBALS['TL_DCA']['tl_member']['config']['onsubmit_callback'][] = array(
    'Craffft\\AccountmailBundle\\Account\\Member\\Account',
    'sendPasswordEmail'
);

// Palettes
if (is_array($GLOBALS['TL_DCA']['tl_member']['palettes'])) {
    foreach ($GLOBALS['TL_DCA']['tl_member']['palettes'] as $k => $v) {
        $GLOBALS['TL_DCA']['tl_member']['palettes'][$k] = preg_replace(
            '#([,;]+)password([,;]?)#',
            '$1password,sendLoginData$2',
            $v
        );
    }
}

// Subpalettes
if (is_array($GLOBALS['TL_DCA']['tl_member']['subpalettes'])) {
    foreach ($GLOBALS['TL_DCA']['tl_member']['subpalettes'] as $k => $v) {
        $GLOBALS['TL_DCA']['tl_member']['subpalettes'][$k] = preg_replace(
            '#([,;]+)password([,;]?)#',
            '$1password,sendLoginData$2',
            $v
        );
    }
}

// Fields
$GLOBALS['TL_DCA']['tl_member']['fields']['sendLoginData'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_member']['sendLoginData'],
    'exclude'   => true,
    'default'   => 1,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['loginDataAlreadySent'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['loginDataAlreadySent'],
    'sql'   => "char(1) NOT NULL default ''"
);
