<?php
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.0 $Revision$                            |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$         |
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
 */

 $msgAdminPanel = "Console d'administration";
 $msgStats = "Statistiques";
 $msgStatsBy = "Statistiques par";
 $msgHours = "heures";
 $msgDay = "jour";
 $msgWeek = "semaine";
 $msgMonth = "mois";
 $msgYear = "ann�e";
 $msgFrom = "du ";
 $msgTo = "au ";
 $msgPreviousDay = "jour pr�c�dent";
 $msgNextDay = "jour suivant";
 $msgPreviousWeek = "semaine pr�c�dent";
 $msgNextWeek = "semaine suivant";
 $msgCalendar = "calendrier";
 $msgShowRowLogs = "voir les logs bruts";
 $msgRowLogs = "logs bruts";
 $msgRecords = "enregistrements";
 $msgDaySort = "Classement journalier";
 $msgMonthSort = "Classement mensuel";
 $msgCountrySort = "Classement par pays";
 $msgOsSort = "Classement par OS";
 $msgBrowserSort = "Classement par navigateur";
 $msgProviderSort = "Classement par provider";
 $msgTotal = "Total";
 $msgBaseConnectImpossible = "Impossible de selectionner la base SQL";
 $msgSqlConnectImpossible = "Impossible de se connecter au serveur SQL";
 $msgSqlQuerryError = "Requ�te SQL impossible";
 $msgBaseCreateError = "Erreur lors de la cr�ation de la base";
 $msgMonthsArray = array("janvier","f�vrier","mars","avril","mai","juin","juillet","ao�t","septembre","octobre","novembre","d�cembre");
 $msgDaysArray=array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
 $msgDaysShortArray=array("D","L","M","M","J","V","S");
 $msgToday = "Aujourd'hui";
 $msgOther = "Autre";
 $msgUnknown = "Inconnu";
 $msgServerInfo = "php Server info";
 $msgStatBy = "Statistics by";
 $msgVersion = "Webstats 1.30";
 $msgCreateCook = "<b>Adiministrateur:</b> Un cookie a �t� stock� sur votre ordinateur,<BR>
     Vous ne serez plus comptabilis� dans les logs.<BR><BR><BR><BR>";
 $msgCreateCookError = "<b>Administrateur:</b> le cookie n'a pas pus �tre stock� sur votre ordinateur.<br>
     V�rifier que votre navigateur les accepte, et rafraichissez la page.<br><br><br><br>";
 $msgInstalComments = "<p>La procedure d'installation automatique va tenter de:</p>
       <ul>
         <li>cr�er une table nomm�e <b>liste_domaines</b> dans votre base SQL<br>
           </b>Cette table sera automatiquement rempli avec le nom des pays et le code InterNIC associ�
           </li>
         <li>cr�er une table nomm�e <b>logezboo</b><br>
           Cette table contiendra vos logs</li>
       </ul>
       <font color=\"#FF3333\">Vous devez pr�alablement avoir modifi� manuellement:<ul><li>le fichier <b>config_sql.php3</b> avec votre <b>login</b>, <b>mot de passe</b> et <b>nom de base</b> pour la connection au serveur SQL.</li><br><li>Le fichier <b>config.inc.php3</b> doit �tre modifi� pour selectionner la langue appropri�e.</font></li></ul><br>Pour ce faire, vous pouvez utiliser un �diteur texte comme Notepad.";
 $msgInstallAbort = "INSTALLATION INTERROMPUE";
 $msgInstall1 = "S'il n' apparait pas d'erreur au dessus, l'installation s'est correctement d�roul�e.";
 $msgInstall2 = "2 tables ont �t� cr��es dans votre base SQL";
 $msgInstall3 = "Vous pouvez maintenant ouvrir l'interface principale";
 $msgInstall4 = "Afin de remplir la table de logs, vous devez mettre un tab dans vos pages � surveiller.";

 $msgUpgradeComments ="La nouvelle version de ezBOO WebStats utilise la m&ecirc;me table <b>logezboo</b> 
					   	que les versions pr&eacute;c&eacute;dentes.<br>
  						Si les pays n'apparaissent pas en fran&ccedil;ais, vous devez supprimer la table 
  						<b>liste_domaines</b> et relancer l'installation.<br>
  						Cela n'aura aucun effet sur la table <b>logezboo</b> .<br>
  						Le message d'erreur est normal :-)";


$langStats="Statistiques";

?>