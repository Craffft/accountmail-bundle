<?php

/*
 * This file is part of the Accountmail Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Palettes
$strPalette = '{accountmail_legend},disableMemberAccountmail,disableUserAccountmail';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = preg_replace(
    '#([;]?)$#',
    ';' . $strPalette,
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['disableMemberAccountmail'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['disableMemberAccountmail'],
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['disableUserAccountmail'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['disableUserAccountmail'],
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50')
);
