<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Framework configuration page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */

//
// Config
//
$LANG['config_activateMessages'] = 'Message Center aktivieren';
$LANG['config_activateMessages_comment'] = 'Mit diesem Schalter kann das Message Center aktiviert werden. Nutzer können damit anderen Nutzern und Gruppen
 Nachrichten oder E-Mails schicken. Ein Eintrag im Optionen Menu wird hinzugefügt.';
$LANG['config_adminApproval'] = 'Administrator Freischaltung erforderlich';
$LANG['config_adminApproval_comment'] = 'Der Administrator erhält eine E-Mail bei einer Neuregistrierung. Er muss den Account manuell freischalten.';
$LANG['config_alertAutocloseDanger'] = 'Fehlermeldungen automatisch schließen';
$LANG['config_alertAutocloseDanger_comment'] = 'Hier kann eingestellt werden das Fehlermeldungen nach der unten eingegebenen Anzahl von Millisekunden automatisch geschlossen werden.';
$LANG['config_alertAutocloseDelay'] = 'Zeit für automatisches Schließen';
$LANG['config_alertAutocloseDelay_comment'] = 'Die Zeit in Millisekunden nach der die oben ausgewählten Meldungen automatisch geschlossen werden (z.B. 4000 = 4 Sekunden).';
$LANG['config_alertAutocloseSuccess'] = 'Erfolgsmeldungen automatisch schließen';
$LANG['config_alertAutocloseSuccess_comment'] = 'Hier kann eingestellt werden das Erfolgsmeldungen nach der unten eingegebenen Anzahl von Millisekunden automatisch geschlossen werden.';
$LANG['config_alertAutocloseWarning'] = 'Warnmeldungen automatisch schließen';
$LANG['config_alertAutocloseWarning_comment'] = 'Hier kann eingestellt werden das Warnmeldungen nach der unten eingegebenen Anzahl von Millisekunden automatisch geschlossen werden.';
$LANG['config_alert_edit_success'] = 'Die Konfiguration wurde gespeichert. Damit alle Änderungen wirksam werden, muss die Seite eventuell neu geladen werden.';
$LANG['config_alert_failed'] = 'Die Konfiguration konnte nicht gespeichert werden. Bitte überprüfe die Eingaben.';
$LANG['config_allowRegistration'] = 'User Selbst-Registration erlauben';
$LANG['config_allowRegistration_comment'] = 'Erlaubt die Registrierung durch den User. Ein zusätzlicher Menüeintrag erscheint im Menü.';
$LANG['config_appDescription'] = 'HTML Beschreibung';
$LANG['config_appDescription_comment'] = 'Hier kann eine Applikations-Beschreibung eingetragen werden. Sie wird im HTML Header benutzt und von Suchmaschinen gelesen.';
$LANG['config_appKeywords'] = 'HTML Schlüsselwörter';
$LANG['config_appKeywords_comment'] = 'Hier können Schlüsselwörter eingetragen werden. Sie wird im HTML Header benutzt und von Suchmaschinen gelesen.';
$LANG['config_appTitle'] = 'Applikationsname';
$LANG['config_appTitle_comment'] = 'Hier kann ein Applikations-Title eingetragen werden. Er wird an mehreren Stellen benutzt, z.B. im HTML Header, Menu und auf anderen Seiten.';
$LANG['config_appURL'] = 'Applikations-URL';
$LANG['config_appURL_comment'] = 'Gib die volle Applikations-URL hier ein. Sie wird z.B. in Benachrichtiguns-E-Mails benutzt.';
$LANG['config_badLogins'] = 'Ungültige Logins';
$LANG['config_badLogins_comment'] = 'Anzahl der ungültigen Login Versuche bevor der User Status auf \'LOCKED\' gesetzt wird. Der User muss danach solange
 warten wie in der Schonfrist angegeben, bevor er sich erneut einloggen kann. Wenn dieser Wert auf 0 gesetzt wird, ist diese Funktion deaktiviert.';
$LANG['config_cookieConsent'] = 'Cookie Zustimmung';
$LANG['config_cookieConsentCDN'] = 'Cookie Consent CDN';
$LANG['config_cookieConsentCDN_comment'] = 'CDNs (Content Distributed Network) können einen Performance-Vorteil bieten dadurch dass populäre Web Module von Servern rund
 um den Globus geladen werden. Cookie Consent ist so ein Modul. Wenn es von einem CDN Server geladen wird, von dem das gleiche Modul
 für den Nutzer schon durch eine andere Anwendung geladen wurde, ist es bereits im Cache des Nutzers und muss nicht nochmal heruntergeladen werden.<br>Schalte
 diese Option aus, wenn du TeamCal Neo in einer Umgebung ohne Internetverbindung betreibst.';
$LANG['config_cookieConsent_comment'] = 'Mit dieser Option wird am unteren Bildschirmrand ein Popup für die Zustimmung zu Cookienutzung angezeigt.
 Dies ist legale Pflicht in der EU. Dieses Feature erfordert eine Internetverbindung.';
$LANG['config_cookieLifetime'] = 'Cookie Lebensdauer';
$LANG['config_cookieLifetime_comment'] = 'Bei erfolgreichem Einloggen wird ein Cookie auf dem lokalen Rechner des Users abgelegt. Dieser Cookie hat eine
 bestimmte Lebensdauer, nach dem er nicht mehr anerkannt wird. Ein erneutes Login ist notwendig. Die Lebensdauer kann hier in Sekunden angegeben werden (0-999999).';
$LANG['config_defaultHomepage'] = 'Standard Startseite';
$LANG['config_defaultHomepage_calendarview'] = 'Kalender';
$LANG['config_defaultHomepage_comment'] = 'Diese Option bestimmt die standard Startseite. Sie wird anonymen Benutzern angezeigt und wenn das Applikationsicon oben links
 angeklickt wird. Achtung, wenn hier "Kalender" gewählt wird, sollte "Public" auch View-Rechte für den Kalender haben.';
$LANG['config_defaultHomepage_home'] = 'Willkommen Seite';
$LANG['config_defaultLanguage'] = 'Standard Sprache';
$LANG['config_defaultLanguage_comment'] = 'TeamCal Neo enthält die Sprachen Englisch und Deutsch. Der Administrator hat eventuell weitere Sprachen installiert.
 Hier kann die Standard Sprache eingestellt werden.';
$LANG['config_defaultMenu'] = 'Menü Position';
$LANG['config_defaultMenu_comment'] = 'Das TeamCal Neo Menü kann entweder als horizontales Menü oben oder als vertikales Menü links angezeigt werden.
 Das vertikale Menü links eignet sich nur für breite Bildschirme, das horizontale Menü passt sich auch an schmale Bildschirme an (responsive). Nutzer können
 diese Einstellung in ihrem Profile auch individuell einstellen.';
$LANG['config_defaultMenu_navbar'] = 'Horizontal Oben';
$LANG['config_defaultMenu_sidebar'] = 'Vertikal Links';
$LANG['config_disableTfa'] = 'Zwei Faktor Authentifizierung Deaktivieren';
$LANG['config_disableTfa_comment'] = 'Deaktiviert die Zwei Faktor Authentifizierung und dessen Einrichtung durch Benutzer. Es werden alle bereits eingerichteten Benutzer 2FA Secrets gelöscht.';
$LANG['config_emailConfirmation'] = 'E-Mail Bestätigung erforderlich';
$LANG['config_emailConfirmation_comment'] = 'Durch die Registrierung erhält der User eine E-Mail an die von ihm angegebene Adresse. Sie enthält einen
 Aktivierungslink, dem er folgen muss, um seine Angaben zu bestätigen.';
$LANG['config_emailNotifications'] = 'E-Mail Benachrichtigungen';
$LANG['config_emailNotifications_comment'] = 'Aktivierung/Deaktivierung von E-Mail Benachrichtigungen. Wenn diese Option ausgeschaltet ist, werden keine automatischen
 Benachrichtigungen per E-Mails verschickt. Dies trifft aber nicht auf Selbst-Registrierungsmails und auf manuell gesendete Mails im Message Center und im Viewprofile Dialog zu.';
$LANG['config_faCDN'] = 'Fontawesome CDN';
$LANG['config_faCDN_comment'] = 'CDNs (Content Distributed Network) können einen Performance-Vorteil bieten dadurch dass populäre Web Module von Servern rund
 um den Globus geladen werden. Fontawesome ist so ein Modul. Wenn es von einem CDN Server geladen wird, von dem das gleiche Modul
 für den Nutzer schon durch eine andere Anwendung geladen wurde, ist es bereits im Cache des Nutzers und muss nicht nochmal heruntergeladen werden.<br>Diese Option
 funktioniert natürlich nur mit Internetverbindung. Schalte diese Option aus, wenn du TeamCal Neo in einer Umgebung ohne Internetverbindung betreibst.';
$LANG['config_font'] = 'Schriftart';
$LANG['config_font_comment'] = 'Wähle eine Schriftart aus. Optionen sind:<ul>
      <li>Default <i>(lädt keine extra Schriftart und nutzt die standard sans-serif Schriftart des Browsers)</i></li>
      <li>... <i>(lädt die ausgewählte Google Schriftart von den TeamCal Neo Installationsdateien (nicht von Google))</i></li>
      </ul>';
$LANG['config_footerCopyright'] = 'Fußzeilen Copyright Name';
$LANG['config_footerCopyrightUrl'] = 'Fußzeilen Copyright URL';
$LANG['config_footerCopyrightUrl_comment'] = 'Gib die URL ein, zu der der Copyright Name verlinken soll. Wenn keine URL angegeben wird, wird nur der Name angezeigt.';
$LANG['config_footerCopyright_comment'] = 'Wird in der Fußzeile oben links angezeigt. Gib nur den Namen ein, das (aktuelle) Jahr wird automatisch angezeigt.';
$LANG['config_footerSocialLinks'] = 'Links zu Sozialen Netzwerken';
$LANG['config_footerSocialLinks_comment'] = 'Gib alle URLs zu sozialen Netzwerken ein, zu denen du von TeamCal Neo\'s Fußzeile verlinken möchtest. Die URLs müssen durch ein Semikolon getrennt sein.
 TeamCal Neo wird die Netzwerke identifizieren und die entsprechende Icons in der Fußzeile anzeigen.';
$LANG['config_footerViewport'] = 'Viewport-Größe anzeigen';
$LANG['config_footerViewport_comment'] = 'Mit dieser Option wird im Footer die Viewport-Größ angezeigt.';
$LANG['config_forceTfa'] = 'Zwei Faktor Authentifizierung Pflicht';
$LANG['config_forceTfa_comment'] = 'Verlangt, dass alle Nutzer eine Zwei Faktor Authentifizierung einrichten, z.B. mit Google oder Microsoft Authenticator. Wenn ein Nutzer noch keine 2FA eingerichtet hat,
 wird er nach dem regulären Login auf die entsprechende Seite umgeleitet.';
$LANG['config_gdprController'] = 'Verantwortlicher';
$LANG['config_gdprController_comment'] = 'Verantwortlicher im Sinne der Datenschutz-Grundverordnung, sonstiger in den Mitgliedstaaten der Europäischen Union geltenden Datenschutzgesetze und anderer Bestimmungen mit datenschutzrechtlichem Charakter.';
$LANG['config_gdprOfficer'] = 'Datenschutzbeauftragter';
$LANG['config_gdprOfficer_comment'] = 'Kontaktdaten des Datenschutzbeauftragten des Verantwortlichen.';
$LANG['config_gdprOrganization'] = 'Organisation';
$LANG['config_gdprOrganization_comment'] = 'Name der Organisation oder Firma, die diese TeamCal Neo Instanz bereitstellt.';
$LANG['config_gdprPlatforms'] = 'Plattformen';
$LANG['config_gdprPlatforms_comment'] = 'Wähle die Plattformen, die in der Datenschutzerklärung enthalten sein sollen.';
$LANG['config_gdprPolicyPage'] = 'Datenschutzerklärung';
$LANG['config_gdprPolicyPage_comment'] = 'Mit dieser Option wird eine Seite mit der Datenschutzerklärung im Hilfe Menü hinzugefügt.<br>Dazu müssen die Felder
 "Organisation", "Verantwortlicher" und "Datenschutzbeauftragter" unten ausgefüllt werden.<br>Weiterhin können darunter optional Erklärungen zu bestimmten sozialen Plattformen mit
 eingebunden werden, wenn sie beispielsweise im Fußbereich verlinkt sind.';
$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalyticsID'] = 'Google Analytics ID (GA4)';
$LANG['config_googleAnalyticsID_comment'] = 'Wenn du die Google Analytics Funktion aktiviert hast, trage hier deine Google Analytics GA4 ID im Format G-... ein.';
$LANG['config_googleAnalytics_comment'] = 'TeamCal Neo unterstützt Google Analytics. Wenn du deine Instanz im Internet betreibst und den Zugriff
 von Google Analytics tracken lassen willst, ticke die Checkbox hier und trage deine Google Analytics ID ein. Der entsprechende Javascript Code wird dann eingefügt.';
$LANG['config_gracePeriod'] = 'Schonfrist';
$LANG['config_gracePeriod_comment'] = 'Zeit in Sekunden, die ein User warten muss, bevor er sich nach zu vielen fehlgeschlagenen Versuchen wieder einloggen kann.';
$LANG['config_homepage'] = 'Benutzer Startseite';
$LANG['config_homepage_calendarview'] = 'Kalender';
$LANG['config_homepage_comment'] = 'Diese Option bestimmt, welche Seite registrierten Benutzern nach dem Login angezeigt wird.';
$LANG['config_homepage_home'] = 'Willkommen Seite';
$LANG['config_homepage_messages'] = 'Nachrichten Seite';
$LANG['config_jQueryCDN'] = 'jQuery CDN';
$LANG['config_jQueryCDN_comment'] = 'CDNs (Content Distributed Network) können einen Performance-Vorteil bieten dadurch dass populäre Web Module von Servern rund
 um den Globus geladen werden. jQuery ist so ein Modul. Wenn es von einem CDN Server geladen wird, von dem das gleiche Modul
 für den Nutzer schon durch eine andere Anwendung geladen wurde, ist es bereits im Cache des Nutzers und muss nicht nochmal heruntergeladen werden.<br>Schalte diese Option
 aus, wenn du TeamCal Neo in einer Umgebung ohne Internetverbindung betreibst.';
$LANG['config_jqtheme'] = 'jQuery UI Theme';
$LANG['config_jqthemeSample'] = 'jQuery UI Theme Beispiel';
$LANG['config_jqthemeSample_comment'] = 'Probier den Datumspicker hier aus um das aktuelle jQuery Theme zu sehen. Das Datum wird nicht gespeichert.';
$LANG['config_jqtheme_comment'] = 'TeamCal Neo nutzt jQuery UI, eine populäre Sammlung von Javascript Tools. jQuery UI bietet auch verschiedene Themes, die die Anzeige
 der Reiterdialoge u.a. Objekten bestimmen. Das Standard Theme ist "smoothness", ein neutrales Schema mit Grautönen. Versuche andere aus der Liste, manche sind
 recht fabenfroh. Diese Einstellung wirkt global. Nutzer können kein eigenes jQuery UI Theme wählen.';
$LANG['config_licActivate'] = 'Lizenz Aktivieren';
$LANG['config_licActivate_comment'] = 'Deine Lizenz ist noch nicht aktiv. Bitte aktiviere sie.';
$LANG['config_licDeregister'] = 'Domain De-Registrieren';
$LANG['config_licDeregister_comment'] = 'Du kannst diese TeamCal Neo Domain von der Lizenz deregistrieren, z.B. wenn du auf eine neuen Domain umziehen willst. Deregistriere diese Domain hier und registriere die neue von dort.';
$LANG['config_licExpiryWarning'] = 'Lizenz-Ablaufwarnung';
$LANG['config_licExpiryWarning_comment'] = 'Gib die Anzahl der Tage bis zum Ablaufen der Lizenz ein, ab dem TeamCal Neo eine entsprechende Warnung anzeigen soll. Setze den Wert auf 0 für keine Warnung.';
$LANG['config_licKey'] = 'Lizenzschlüssel';
$LANG['config_licKey_comment'] = 'Gib hier den Lizenzschlüssel ein, den du bei der Registrierung von TeamCal Neo erhalten hast.<br><i class=\'bi-exclamation-triangle text-warning me-2\'></i>
<i>Ein neuer Lizenzschlüssel muss zunächst mit [Speichern] gespeichert werden bevor eine Aktivierung, Registrierung oder De-Registrierung angestoßen werden kann.</i>';
$LANG['config_licRegister'] = 'Domain Registrieren';
$LANG['config_licRegister_comment'] = 'Diese TeamCal Neo Domain ist nicht für den angegebenen Lizenzschlüssel registriert.<br>Wenn der Lizenzschlüssel gültig ist und mehr als eine Domain erlaubt, klicke "Registrieren", um diese Domain hinzuzufügen. Andernfalls gib bitte einen neuen gültigen Lizenzschlüssel ein und klicke "Anwenden".';
$LANG['config_logLanguage'] = 'Logbuchsprache';
$LANG['config_logLanguage_comment'] = 'Diese Einstellung bestimmt die Sprache der Logbucheinträge.';
$LANG['config_mailFrom'] = 'Mail Von';
$LANG['config_mailFrom_comment'] = 'Gibt den Absender Namen von Benachrichtigungs E-Mails an.';
$LANG['config_mailReply'] = 'Mail Antwort';
$LANG['config_mailReply_comment'] = 'Gibt die Rückantwort Adresse von Benachrichtigungs E-Mails an. Dieses Feld muss eine gültige E-Mail Adresse enthalten. Wenn das nicht der Fall ist, wird eine Dummy Adresse gespeichert.';
$LANG['config_mailSMTP'] = 'Externen SMTP Server benutzen';
$LANG['config_mailSMTPAnonymous'] = 'Anonyme SMTP Anmeldung';
$LANG['config_mailSMTPAnonymous_comment'] = 'Verwendung der SMTP Verbindung ohne Authentifizierung.';
$LANG['config_mailSMTPSSL'] = 'SMTP TLS/SSL Protokoll';
$LANG['config_mailSMTPSSL_comment'] = 'TLS/SSL Protokoll für die SMTP Verbindung benutzen.';
$LANG['config_mailSMTPDebug'] = 'SMTP Debugging';
$LANG['config_mailSMTPDebug_comment'] = 'Aktiviert detaillierte SMTP Kommunikationsprotokolle im PHP Error Log. Nützlich zur Fehlersuche.';
$LANG['config_mailSMTP_comment'] = 'Mit diesm Schalter wird ein externer SMTP Server zum Versenden von E-Mails benutzt anstatt der PHP mail() Funktion.
 Diese Feature erfordert das PEAR Mail Paket auf dem TcNeo Server. Viele Hoster installieren dieses Paket als Standard. Ausserdem ist es erforderlich, dass sich
 der Tcro Server per SMTP oder TLS/SSL protocol mit den gebrächlichen SMTP port 25, 465 und 587 mit dem SMTP Server verbinden kann. Bei einigen Hostern
 ist dies durch Firewalleinstellungen nicht möglich. Es erscheint dann eie Fehlermeldung.';
$LANG['config_mailSMTPhost'] = 'SMTP Host';
$LANG['config_mailSMTPhost_comment'] = 'Gib den SMTP Host Namen an.';
$LANG['config_mailSMTPpassword'] = 'SMTP Passwort';
$LANG['config_mailSMTPpassword_comment'] = 'Gib das SMTP Passwort an.';
$LANG['config_mailSMTPport'] = 'SMTP Port';
$LANG['config_mailSMTPport_comment'] = 'Gib den SMTP Host Port an.';
$LANG['config_mailSMTPusername'] = 'SMTP Benutzername';
$LANG['config_mailSMTPusername_comment'] = 'Gib den SMTP Benutzernamen an.';
$LANG['config_matomoAnalytics'] = 'Matomo Analytics';
$LANG['config_matomoAnalytics_comment'] = 'TeamCal Neo unterstützt Matomo Analytics. Wenn du die Zugriffe auf deine Instanz
 von Matomo Analytics tracken lassen willst, ticke die Checkbox hier und trage deine Matomo URL und Matomo SiteId ein. Der entsprechende Javascript Code wird dann eingefügt.';
$LANG['config_matomoSiteId'] = 'Matomo SiteId';
$LANG['config_matomoSiteId_comment'] = 'Trage hier die Matomo SiteId ein, unter der du diese TeamCal Neo Instanz im Matomo Server eingerichtet hast.';
$LANG['config_matomoUrl'] = 'Matomo URL';
$LANG['config_matomoUrl_comment'] = 'Wenn du Matomo Analytics aktiviert hast, trage hier die URL zu deinem Matomo Server ein, e.g. \'mysite.com/matomo\'.';
$LANG['config_noCaching'] = 'Kein Caching';
$LANG['config_noCaching_comment'] = 'In manchen Server-Client Umgebungen kann es zu unerwuenschten Caching Effekten kommen. Mit diesem Schalter schickt TeamCal Neo im HTML header No-caching Anweisungen zum Web Server, die hier eventuell Abhilfe schaffen.';
$LANG['config_noIndex'] = 'Keine Suchmaschinen-Indizierung';
$LANG['config_noIndex_comment'] = 'Mit diesem Schalter werden Suchmaschinen angewiesen, diese TeamCal Neo Website nicht zu indizieren. Es werden ausserdem keine SEO Einträge im Header erzeugt.';
$LANG['config_pageHelp'] = 'Seitenhilfe';
$LANG['config_pageHelp_comment'] = 'Mit diesem Schalter wird rechts in der Titelleiste einer Page ein Hilfe Icon angezeigt, dass zur Dokumentation dieser Seite verlinkt ist.';
$LANG['config_permissionScheme'] = 'Berechtigungsschema';
$LANG['config_permissionScheme_comment'] = 'Hiermit wird das aktive Berechtigungsschema ausgewählt. Das Schema kann auf der Berechtigungsschema Seite bearbeitet werden.';
$LANG['config_pwdStrength'] = 'Passwort Sicherheit';
$LANG['config_pwdStrength_comment'] = 'Die Passwort Sicherheit bestimmt, welchen Anforderungen das User Passwort genügen muss. Erlaubt sind immer Groß- und Kleinbuchstaben, Zahlen und die Sonderzeichen: !@#$%^&amp;*().<br><br>
      - <strong>Niedrig:</strong> Mindestens 4 Zeichen<br>
      - <strong>Mittel:</strong> Mindestens 6 Zeichen, mindestens ein Großbuchstabe, ein Kleinbuchstabe und eine Zahl<br>
      - <strong>Hoch:</strong> Mindestens 8 Zeichen, mindestens ein Großbuchstabe, ein Kleinbuchstabe, eine Zahl und ein Sonderzeichen<br>';
$LANG['config_pwdStrength_high'] = 'Hoch';
$LANG['config_pwdStrength_low'] = 'Niedrig';
$LANG['config_pwdStrength_medium'] = 'Mittel';
$LANG['config_showAlerts'] = 'Erfolgs- und Fehlermeldungen';
$LANG['config_showAlerts_all'] = 'Alle (inkl. Erfolgsnachrichten)';
$LANG['config_showAlerts_comment'] = 'Mit dieser Option kann ausgewählt werden, welche Ergebnisnachrichten angezeigt werden.';
$LANG['config_showAlerts_none'] = 'Keine';
$LANG['config_showAlerts_warnings'] = 'Nur Warnungen und Fehler';
$LANG['config_tab_email'] = 'E-mail';
$LANG['config_tab_footer'] = 'Fußzeile';
$LANG['config_tab_gdpr'] = 'DSGVO';
$LANG['config_tab_homepage'] = 'Startseite';
$LANG['config_tab_images'] = 'Bilder';
$LANG['config_tab_license'] = 'Lizenz';
$LANG['config_tab_login'] = 'Login';
$LANG['config_tab_registration'] = 'Registrierung';
$LANG['config_tab_system'] = 'System';
$LANG['config_tab_theme'] = 'Theme';
$LANG['config_tab_user'] = 'Nutzer';
$LANG['config_theme'] = 'Theme';
$LANG['config_theme_comment'] = 'Seit Version 4.0.0 unterstützt TeamCal Neo keine unterschiedlichen Themes mehr. Auch wenn einige von ihnen einen schönen Kick hatten,
  war ihre Wartung und Aktualisierung zu viel Aufwand im Vergleich zu ihrem Nutzen. Stattdessen nutzt TeamCal Neo jetzt das neueste Bootstrap Theming Framework,
  das einen hellen und einen dunklen Modus bietet. Du kannst zwischen ihnen über den Theme-Selektor im oberen Menü wechseln.<br>
  Du kannst jedoch immer noch das jQuery-Theme und die globale Schriftart unten wählen.';
$LANG['config_timeZone'] = 'Zeitzone';
$LANG['config_timeZone_comment'] = 'Wenn der Webserver in einer anderen Zeitzone steht als die Nutzer, kann hier die Zeitzone angepasst werden.';
$LANG['config_title'] = 'Framework Konfiguration';
$LANG['config_underMaintenance'] = 'Website in Wartung';
$LANG['config_underMaintenance_comment'] = 'Mit diesem Schalter kann die Website unter Wartung gestellt werden. Nur fuer den Admin Nuzter sind die Seiten verfügbar.
      Normale Nutzer bekommen eine "Unter Wartung" Nachricht.';
$LANG['config_userCustom1'] = 'Profilfeld 1 Titel';
$LANG['config_userCustom1_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom2'] = 'Profilfeld 2 Titel';
$LANG['config_userCustom2_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom3'] = 'Profilfeld 3 Titel';
$LANG['config_userCustom3_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom4'] = 'Profilfeld 4 Titel';
$LANG['config_userCustom4_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom5'] = 'Profilfeld 5 Titel';
$LANG['config_userCustom5_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_useCaptcha'] = 'Captcha benutzen';
$LANG['config_useCaptcha_comment'] = 'Wähle, ob ein Captcha Bild für das Message Center benutzt werden soll.';
$LANG['config_userManual'] = 'Nutzerhandbuch';
$LANG['config_userManual_comment'] = 'TeamCal Neo\'s Nutzerhandbuch ist in Englisch verfügbar auf der <a href="https://lewe.gitbook.io/teamcal-neo/" target="_blank">Lewe Gitbook site</a>.
      Solltest du ein eigenes Handbuch geschrieben haben, kannst du den Link hier angeben. Der Link wird im Hilfe Menu angezeigt. Wenn dieses Feld leer ist, wird kein Eintrag im Hilfe Menu angezeigt.';
$LANG['config_versionCompare'] = 'Versionsvergleich';
$LANG['config_versionCompare_comment'] = 'Mit dieser Option überprüft TeamCal Neo auf der "Über TeamCal Neo" Seite die laufende Version und vergleicht sie mit der neusten verfügbaren. Dazu benötigt
      TeamCal Neo Internetzugriff. Wenn du TeamCal Neo in einer Umgebung ohne Internetzugriff betreibst, schalte diese Option aus. Bei abweichenden Versionen wird dies hinter der Versionsnummer angeziegt.';
$LANG['config_welcomeText'] = 'Willkommen Seite Text';
$LANG['config_welcomeText_comment'] = 'Hier kann ein Text für die Startseite eingegeben werden. Die folgenden HTML Tags sind erlaubt:<br>
&lt;a&gt;, &lt;b&gt;, &lt;br&gt;, &lt;em&gt;, &lt;h1&gt; bis &lt;h4&gt;, &lt;hr&gt;, &lt;i&gt;, &lt;img&gt;, &lt;li&gt;, &lt;ol&gt;, &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;.';
