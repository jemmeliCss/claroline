<?php
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.0 $Revision$                             |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$              |
      |   German translation                                                 |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      |                                                                      |
      |   This program is distributed in the hope that it will be useful,    |
      |   but WITHOUT ANY WARRANTY; without even the implied warranty of     |
      |   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
      |   GNU General Public License for more details.                       |
      |                                                                      |
      |   You should have received a copy of the GNU General Public License  |
      |   along with this program; if not, write to the Free Software        |
      |   Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA          |
      |   02111-1307, USA. The GNU GPL license is also available through     |
      |   the world-wide-web at http://www.gnu.org/copyleft/gpl.html         |
      +----------------------------------------------------------------------+
      | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>                    |
      +----------------------------------------------------------------------+
      | Translator:                                                          |
      +----------------------------------------------------------------------+
 */
 $langStats="Statistiken";
 $msgAdminPanel = "Administratorformular";
 $msgStats = "Statistiken";
 $msgStatsBy = "Statistiken von";
 $msgHours = "Stunden";
 $msgDay = "Tag";
 $msgWeek = "Woche";
 $msgMonth = "Monat";
 $msgYear = "Jahr";
 $msgFrom = "von ";
 $msgTo = "bis ";
 $msgPreviousDay = "vorheriger Tag";
 $msgNextDay = "n�chster Tag";
 $msgPreviousWeek = "vorherige Woche";
 $msgNextWeek = "n�chste Woche";
 $msgCalendar = "Kalender";
 $msgShowRowLogs = "Logzeilen anzeigen";
 $msgRowLogs = "Logzeilen";
 $msgRecords = "Datens�tze";
 $msgDaySort = "Tagessortierung";
 $msgMonthSort = "Monatssortierung";
 $msgCountrySort = "L�ndersortierung";
 $msgOsSort = "Betriebssystemsortierung";
 $msgBrowserSort = "Browsersortierung";
 $msgProviderSort = "Providersortierung";
 $msgTotal = "Gesamt";
 $msgBaseConnectImpossible = "SQL Quelle nicht erreichbar";
 $msgSqlConnectImpossible = "SQL Server Verbindung nicht m�glich";
 $msgSqlQuerryError = "SQL Query nicht m�glich";
 $msgBaseCreateError = "Beim Versuch ezboo Base zu erstellen ist ein Fehler aufgetreten";
 $msgMonthsArray = array("Januar","Februar","M�rz","April","Mai","Junie","Juli","August","September","Oktober","November","Dezember");
 $msgDaysArray = array("Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag");
 $msgDaysShortArray=array("S","M","D","M","D","F","S");
 $msgToday = "Heute";
 $msgOther = "Anderer";
 $msgUnknown = "Unbekannt";
 $msgServerInfo = "php Server info";
 $msgStatBy = "Statistiken von";
 $msgVersion = "Webstats 1.30";
 $msgCreateCook = "<b>Administrator:</b> Ein Cookie wurde auf Ihrem Computer angelegt,<BR>
     Sie werden in Ihren Logs nicht mehr gef�hrt.<br><br><br><br>";
 $msgCreateCookError = "<b>Administrator:</b> Der Cookie konnte nicht auf Ihrem Computer gespeichert werden.<br>
     �berpr�fen Sie die Einstellungen Ihres Browsers und aktualisieren Sie die Seite.<br><br><br><br>";
 $msgInstalComments = "<p>Die automatische Installationsprozedur wird versuchen</p>
       <ul>
         <li>eine Tabelle namens <b>liste_domaines</b> in Ihrer SQL Datenbank anzulegen.<br>
           </b>Diese Tabelle wird automatisch mit L�ndernamen und den entsprechenden InterNIC codes gef�llt</li>
         <li>Eine Tabelle namens <b>logezboo</b> anlegen<br>
           Diese Tabelle wird Ihre Logdaten speichern</li>
       </ul>
       <font color=\"#FF3333\">Sie m�ssen die folgenden Punkte von Hand anpassen:<ul><li><b>config_sql.php3</b> Datei mit Ihrem <b>Login</b>, <b>Passwort</b> und <b>Datenbankname</b> f�r die SQL-Sever Verbindung.</li><br><li>Die Datei <b>config.inc.php3</b> muss angepasst werden, um die entsprechende Sprache einzustellen.</font></li></ul><br>Um das umzusetzen k�nnen sie einen beliebigen Texteditor verwenden (z.B. Notepad).";
 $msgInstallAbort = "SETUP ABGEBROCHEN";
 $msgInstall1 = "Erscheint keine Fehlermeldung, war die Installation erfolgreich.";
 $msgInstall2 = "2 Tabellen wurden in Ihrer SQL Datenbank angelegt";
 $msgInstall3 = "Sie k�nnen nun das Hauptinterface aufrufen";
 $msgInstall4 = "Um Ihre Tabelle zu f�llen, wenn Seiten geladen werden, m�ssen Sie einen Tag in die zu �berwachenden Seiten einf�gen.";

 $msgUpgradeComments ="Diese neue Version von ezBOO WebStats benutzt die gleiche Tabelle <b>logezboo</b> wie vorhergehende Versionen.<br>
                                                  Wenn L�nder nicht in Englisch geschrieben sind, m�ssen sie die Tabelle <b>liste_domaine</b>
                                                  l�schen und die Installation neu durchf�hren.<br>
                                                  Das wird keine Auswirkungen auf die Tabelle <b>logezboo</b> haben.<br>
                                                  Die Fehlermeldung ist normal. :-)";

?>