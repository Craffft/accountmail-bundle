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
 * Table tl_email
 */
$GLOBALS['TL_DCA']['tl_email'] = array
(
    // Config
    'config'   => array
    (
        'dataContainer' => 'File'
    ),
    // Palettes
    'palettes' => array
    (
        'default' => '{emailSender_legend},emailFrom,emailFromName'
    ),
    // Fields
    'fields'   => array
    (
        // Email sender
        'emailFrom'     => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_email']['emailFrom'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'rgxp' => 'email', 'tl_class' => 'w50')
        ),
        'emailFromName' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_email']['emailFromName'],
            'exclude'   => true,
            'inputType' => 'TranslationTextField',
            'eval'      => array('mandatory' => true, 'rgxp' => 'alpha', 'tl_class' => 'w50')
        )
    )
);

/**
 * Add email fields dynamically
 */
if (is_array($GLOBALS['TL_EMAIL'])) {
    foreach ($GLOBALS['TL_EMAIL'] as $strName) {
        $arrReferences = array();
        $arrReferences[] = $GLOBALS['TL_LANG']['tl_email']['helpwizard'];

        switch ($strName) {
            case 'emailNewMember':
            case 'emailChangedMemberPassword':
                $arrReferences = array_merge($arrReferences, \System::getContainer()->get('craffft.accountmail.util.helpwizard')->getHelpwizardReferencesByMember());
                break;

            case 'emailNewUser':
            case 'emailChangedUserPassword':
                $arrReferences = array_merge($arrReferences, \System::getContainer()->get('craffft.accountmail.util.helpwizard')->getHelpwizardReferencesByUser());
                break;
        }

        $GLOBALS['TL_DCA']['tl_email']['palettes']['default'] .= sprintf(';{%s_legend:hide}', $strName);
        $GLOBALS['TL_DCA']['tl_email']['palettes']['default'] .= sprintf(',%s%s', $strName, 'Subject');
        $GLOBALS['TL_DCA']['tl_email']['palettes']['default'] .= sprintf(',%s%s', $strName, 'Template');
        $GLOBALS['TL_DCA']['tl_email']['palettes']['default'] .= sprintf(',%s%s', $strName, 'Content');

        $GLOBALS['TL_DCA']['tl_email']['fields'][$strName . 'Subject'] = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_email']['emailSubject'],
            'exclude'   => true,
            'inputType' => 'TranslationTextField',
            'reference' => $arrReferences,
            'eval'      => array('mandatory' => true, 'helpwizard' => true, 'tl_class' => 'w50')
        );

        $GLOBALS['TL_DCA']['tl_email']['fields'][$strName . 'Template'] = array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_email']['emailTemplate'],
            'default'          => 'mail_default',
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => function () {
                return \Contao\Backend::getTemplateGroup('mail_');
            },
            'eval'             => array('mandatory' => true, 'tl_class' => 'w50')
        );

        $GLOBALS['TL_DCA']['tl_email']['fields'][$strName . 'Content'] = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_email']['emailContent'],
            'exclude'   => true,
            'inputType' => 'TranslationTextArea',
            'reference' => $arrReferences,
            'eval'      => array('mandatory' => true, 'helpwizard' => true, 'rte'=>'tinyMCE', 'tl_class' => 'clr')
        );
    }
}
