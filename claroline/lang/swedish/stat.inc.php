<?php
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.4.0 $Revision$                             |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$                |
      |   Swedish translation                                                |
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
      | Translator: Jan Olsson <jano@artedi.nordmaling.se>                   |
      +----------------------------------------------------------------------+
 */
$langStats="Statistik";
$msgAdminPanel="Administrat�rspanel";
$msgStats="Statistik";
$msgStatsBy="Statistik �ver";
$msgHours="timmar";
$msgDay="dag";
$msgWeek="vecka";
$msgMonth="m�nad";
$msgYear="�r";
$msgFrom="fr�n ";
$msgTo="till ";
$msgPreviousDay="f�reg�ende dag";
$msgNextDay="n�sta dag";
$msgPreviousWeek="f�reg�ende vecka";
$msgNextWeek="n�sta vecka";
$msgCalendar="kalender";
$msgShowRowLogs="visa radlogg";
$msgRowLogs="radloggar";
$msgRecords="poster";
$msgDaySort="Dagsorterad";
$msgMonthSort="M�nadssorterad";
$msgCountrySort="Landssorterad";
$msgOsSort="OS-sorterad";
$msgBrowserSort="Webbl�sarsorterad";
$msgProviderSort="ISP-sorterad";
$msgTotal="Totalt";
$msgBaseConnectImpossible="om�jligt att v�lja SQL-databas";
$msgSqlConnectImpossible="SQL-serveranslutning om�jlig";
$msgSqlQuerryError="SQL-fr�ga om�jlig";
$msgBaseCreateError="Ett fel uppstod vid f�rs�k att skapa ezboo-databas";
$msgMonthsArray=array("januari","februari","mars","april","maj","juni","juli","augusti","september","oktober","november","december");
$msgDaysArray=array("S�ndag","M�ndag","Tisdag","Onsdag","Torsdag","Fredag","L�rdag");
$msgDaysShortArray=array("S","M","T","O","T","F","L");
$msgToday="Idag";
$msgOther="Annan";
$msgUnknown="Ok�nd";
$msgServerInfo="php-serverinformation";
$msgStatBy="Statistik �ver";
$msgVersion="Webstats 1.30";
$msgCreateCook="<b>Administrat�r:</b> En cookie har skapats p� din dator,<BR>
    Du kommer inte att dyka upp i loggarna l�ngre.<br><br><br><br>";
$msgCreateCookError = "<b>Administrat�r:</b> cookie kunde inte sparas p� din dator.<br>
    Kontrollera dina webbl�sarinst�llningar och fr�scha upp sidan.<br><br><br><br>";
$msgInstalComments = "<p>Den automatiska installationsproceduren kommer att f�rs�ka att:/p>
       <ul>
         <li>skapa en tabell med namnet <b>liste_domaines</b> i din SQL-databas<br>
           </b>Denna tabell kommer automatiskt att fyllas med landsnamn med InterNIC
           koder</li>
         <li>skapa en tabell med namnet <b>logezboo</b><br>
           Denna tabell kommer att spara dina loggar</li>
       </ul>
       <font color=\"#FF3333\">Du m�ste ha modifierat filen:<ul><li><b>config_sql.php3</b> manuellt med ditt <b>login</b>, <b>l�senord</b> och <b>databasnamne</b> f�r SQL-serveranslutning.</li><br><li>Filen <b>config.inc.php3</b> m�ste modifieras f�r att kunna v�lja r�tt spr�k.</font></li></ul><br>F�r att g�ra det kan du v�lja valfri textredigerare (t.ex. Notepad).";
$msgInstallAbort = "Installation avbruten";
$msgInstall1 = "Om det inte finns n�got felmeddelande ovan, s� var installationen lyckad.";
$msgInstall2 = "2 tabeller har skapats i din databas";
$msgInstall3 = "Du kan nu �ppna huvudgr�nssnittet";
$msgInstall4 = "F�r att kunna fylla dina tabeller n�r sidor �ppnas, s� m�ste du l�gga till en tagg i 'monitored pages'.";

$msgUpgradeComments ="Denna nya version av ezBOO WebStats anv�nder samma tabell <b>logezboo</b> som tidigare versioner.<br>
  						Om l�nder inte skrivs p� engelska m�ste du radera tabellen <b>liste_domaine</b> 
  						och starta installationen.<br>
  						Detta kommer inte att p�verka tabellen <b>logezboo</b> .<br>
  						Error meddelandet �r normalt. :-)";


?>
