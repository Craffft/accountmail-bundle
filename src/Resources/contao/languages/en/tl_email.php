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
 * Title
 */
$GLOBALS['TL_LANG']['tl_email']['edit'] = 'Edit automated emails';

/**
 * Labels
 */
$GLOBALS['TL_LANG']['tl_email']['emailSender_legend'] = 'Email sender';
$GLOBALS['TL_LANG']['tl_email']['emailNewMember_legend'] = 'New member';
$GLOBALS['TL_LANG']['tl_email']['emailChangedMemberPassword_legend'] = 'Password changed for member';
$GLOBALS['TL_LANG']['tl_email']['emailNewUser_legend'] = 'New user';
$GLOBALS['TL_LANG']['tl_email']['emailChangedUserPassword_legend'] = 'Password changed for user';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_email']['emailFrom'] = array('Sender’s email address', 'Please enter the sender’s email address.');
$GLOBALS['TL_LANG']['tl_email']['emailFromName'] = array('Sender’s name', 'Please enter the sender’s name.');
$GLOBALS['TL_LANG']['tl_email']['emailSubject'] = array('Subject line', 'Please enter the subject here.');
$GLOBALS['TL_LANG']['tl_email']['emailTemplate'] = array('Template', 'Please select the email template.');
$GLOBALS['TL_LANG']['tl_email']['emailContent'] = array('Email content', 'Please enter the content of the email here.');

/**
 * Helpwizard
 */
$GLOBALS['TL_LANG']['tl_email']['helpwizard'] = array('General description', 'You can use in this field the Contao insert tags and the extension "insert tags".<br><strong>IMPORTANT: Each insert-tag must have the flag "| refresh"</strong>, because otherwise it could lead to erroneous emails.<br>Among other things, you can also use the following wildcards:');

/**
 * Default contents
 */
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailFromName'] = 'Contao CMS';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewMemberSubject'] = 'New member account';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewMemberContent'] = '<p>Hello {{accountmail::firstname|refresh}} {{accountmail::lastname|refresh}},</p>
<p>It was created a new account for you.</p>
<p>Your data are as follows:<br>Name: {{accountmail::username|refresh}}<br>Password: {{accountmail::password|refresh}}</p>
<p>Sent with the Contao CMS <a href="http://www.craffft.de/accountmail.html">accountmail</a> extension.</p>';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedMemberPasswordSubject'] = 'New member password';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedMemberPasswordContent'] = '<p>Hello {{accountmail::firstname|refresh}} {{accountmail::lastname|refresh}},</p>
<p>You receive a new password for your member account "{{accountmail::username|refresh}}".</p>
<p>It reads:<br>{{accountmail::password|refresh}}</p>
<p>Sent with the Contao CMS <a href="http://www.craffft.de/accountmail.html">accountmail</a> extension.</p>';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewUserSubject'] = 'New Contao user account';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewUserContent'] = '<p>Hello {{accountmail::name|refresh}},</p>
<p>It was a new Contao account created for you.</p>
<p>You can log in at the following address:<br>http://{{env::host|refresh}}/contao</p>
<p>Your data are as follows:<br>Name: {{accountmail::username|refresh}}<br>Password: {{accountmail::password|refresh}}</p>
<p>Sent with the Contao CMS <a href="http://www.craffft.de/accountmail.html">accountmail</a> extension.</p>';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedUserPasswordSubject'] = 'New Contao user password';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedUserPasswordContent'] = '<p>Hello {{accountmail::name|refresh}},</p>
<p>You have a new password for your user account "{{accountmail::username|refresh}}" receive.</p>
<p>It reads:<br>{{accountmail::password|refresh}}</p>
<p>Sent with the Contao CMS <a href="http://www.craffft.de/accountmail.html">accountmail</a> extension.</p>';
