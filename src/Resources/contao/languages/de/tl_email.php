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
$GLOBALS['TL_LANG']['tl_email']['edit'] = 'Automatisierte E-Mails bearbeiten';

/**
 * Labels
 */
$GLOBALS['TL_LANG']['tl_email']['emailSender_legend'] = 'E-Mail Absender';
$GLOBALS['TL_LANG']['tl_email']['emailNewMember_legend'] = 'Neues Mitglied';
$GLOBALS['TL_LANG']['tl_email']['emailChangedMemberPassword_legend'] = 'Passwort bei Mitglied geändert';
$GLOBALS['TL_LANG']['tl_email']['emailNewUser_legend'] = 'Neuer Benutzer';
$GLOBALS['TL_LANG']['tl_email']['emailChangedUserPassword_legend'] = 'Passwort bei Benutzer geändert';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_email']['emailFrom'] = array('Absender E-Mail Adresse', 'Bitte geben Sie die E-Mail Adresse des Absenders ein.');
$GLOBALS['TL_LANG']['tl_email']['emailFromName'] = array('Absender Name', 'Bitte geben Sie den Name des Absenders ein.');
$GLOBALS['TL_LANG']['tl_email']['emailSubject'] = array('Betreff', 'Bitte geben Sie den Betreff ein.');
$GLOBALS['TL_LANG']['tl_email']['emailTemplate'] = array('Template', 'Bitte wählen Sie das E-Mail Template aus.');
$GLOBALS['TL_LANG']['tl_email']['emailContent'] = array('Inhalt', 'Bitte geben Sie den Inhalt der E-Mail ein.');

/**
 * Helpwizard
 */
$GLOBALS['TL_LANG']['tl_email']['helpwizard'] = array('Allgemeine Beschreibung', 'Sie können in diesem Feld die Contao Insert-Tags und die, der Erweiterung "Inserttags" verwenden.<br><strong>WICHTIG: Jeder Insert-Tag muss das Flag "|refresh" haben</strong>, denn sonst kann es zu fehlerhaften E-Mails kommen.<br>Unter anderem können Sie auch die folgenden Platzhalter nutzen:');

/**
 * Default contents
 */
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailFromName'] = 'Contao CMS';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewMemberSubject'] = 'Neuer Mitglieder Account';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewMemberContent'] = '<p>Hallo {{accountmail::firstname|refresh}} {{accountmail::lastname|refresh}},</p>
<p>Es wurde ein neuer Account für Sie erstellt.</p>
<p>Ihre Zugangsdaten lauten wie folgt:<br>Name: {{accountmail::username|refresh}}<br>Passwort: {{accountmail::password|refresh}}</p>
<p>Gesendet mit der Contao CMS <a href="http://www.craffft.de/accountmail.html">Accountmail</a> Erweiterung.</p>';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedMemberPasswordSubject'] = 'Neues Mitglieder Passwort';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedMemberPasswordContent'] = '<p>Hallo {{accountmail::firstname|refresh}} {{accountmail::lastname|refresh}},</p>
<p>Sie haben ein neues Passwort für Ihren Mitglieder Account "{{accountmail::username|refresh}}" erhalten.</p>
<p>Es lautet:<br>{{accountmail::password|refresh}}</p>
<p>Gesendet mit der Contao CMS <a href="http://www.craffft.de/accountmail.html">Accountmail</a> Erweiterung.</p>';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewUserSubject'] = 'Neuer Contao Benutzer Account';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailNewUserContent'] = '<p>Hallo {{accountmail::name|refresh}},</p>
<p>Es wurde ein neuer Contao Account für Sie erstellt.</p>
<p>Sie können sich unter folgender Adresse einloggen:<br>http://{{env::host|refresh}}/contao</p>
<p>Ihre Zugangsdaten lauten wie folgt:<br>Name: {{accountmail::username|refresh}}<br>Passwort: {{accountmail::password|refresh}}</p>
<p>Gesendet mit der Contao CMS <a href="http://www.craffft.de/accountmail.html">Accountmail</a> Erweiterung.</p>';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedUserPasswordSubject'] = 'Neues Contao Benutzer Passwort';
$GLOBALS['TL_LANG']['tl_email']['defaultContents']['emailChangedUserPasswordContent'] = '<p>Hallo {{accountmail::name|refresh}},</p>
<p>Sie haben ein neues Passwort für Ihren Benutzer Account "{{accountmail::username|refresh}}" erhalten.</p>
<p>Es lautet:<br>{{accountmail::password|refresh}}</p>
<p>Gesendet mit der Contao CMS <a href="http://www.craffft.de/accountmail.html">Accountmail</a> Erweiterung.</p>';
