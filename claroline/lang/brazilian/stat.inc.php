<?
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.0 $Revision$                        	 |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$         	 |
      |   Brazillian Translation (portugese)                                 |
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
      | Translator :                                                         |
      |           Marcello R. Minholi, <minholi@unipar.be>                   |
	  |									from Universidade Paranaense         |
      +----------------------------------------------------------------------+
 */
 $msgAdminPanel = "Painel do Administrador";
 $msgStats = "Estat�sticas";
 $msgStatsBy = "Estat�sticas por";
 $msgHours = "horas";
 $msgDay = "dia";
 $msgWeek = "semana";
 $msgMonth = "m�s";
 $msgYear = "ano";
 $msgFrom = "de ";
 $msgTo = "para ";
 $msgPreviousDay = "dia anterior";
 $msgNextDay = "pr�ximo dia";
 $msgPreviousWeek = "semana anterior";
 $msgNextWeek = "pr�xima semana";
 $msgCalendar = "calend�rio";
 $msgShowRowLogs = "mostrar os logs";
 $msgRowLogs = "os logs";
 $msgRecords = "registros";
 $msgDaySort = "Classificado por Dia";
 $msgMonthSort = "Classificado por M�s";
 $msgCountrySort = "Classificado por Pa�s";
 $msgOsSort = "Classificado por S.O.";
 $msgBrowserSort = "Classificado por Browser";
 $msgProviderSort = "Classificado por Provedor";
 $msgTotal = "Total";
 $msgBaseConnectImpossible = "Imposs�vel selecionar Base SQL";
 $msgSqlConnectImpossible = "Conex�o com o Servidor SQL imposs�vel";
 $msgSqlQuerryError = "Consulta SQL imposs�vel";
 $msgBaseCreateError = "Ocorreu um erro ao criar a base ezboo";
 $msgMonthsArray = array("janeiro","fevereiro","mar�o","abril","maio","junho","julho","agosto","setembro","outubro","novembro","dezembro");
 $msgDaysArray = array("Domingo","Segunda-feira","Ter�a-feira","Quarta-feira","Quinta-feira","Sexta-feira","S�bado");
 $msgDaysShortArray=array("D","S","T","Q","Q","S","S");
 $msgToday = "Hoje";
 $msgOther = "Outro";
 $msgUnknown = "Desconhecido";
 $msgServerInfo = "Informa��o do Servidor php";
 $msgStatBy = "Estat�sticas por";
 $msgVersion = "Webstats 1.30";
 $msgCreateCook = "<b>Administrador:</b> Um cookie foi criado em seu computador,<BR>
     Voc� n�o ir� mais aparecer em seus logs.<br><br><br><br>";
 $msgCreateCookError = "<b>Administrador:</b> cookie n�o pode ser salva no seu computador.<br>
     Verifique as configura��es do seu browser e recarregue a p�gina.<br><br><br><br>";
 $msgInstalComments = "<p>O procedimento de instala��o autom�tica ir� tentar:</p>
       <ul>
         <li>criar as tabelas chamadas <b>lista de dom�nios</b> na sua base SQL<br>
           </b>Esta tabela ser� preenchida automaticamente com os nomes de pa�s do InterNIC
           </li>
         <li>criar tablela chamada <b>logezboo</b><br>
           Essa tabela ir� armazenar seus logs.</li>
       </ul>
       <font color=\"#FF3333\">Voc� ter� que modificar manualmente:<ul><li><b>config_sql.php3</b> com o seu <b>login</b>, <b>password</b> e <b>base name</b> para conex�o com o servidor SQL.</li><br><li>The file <b>config.inc.php3</b> must have been modified to select apropriate language.</font></li></ul><br>To do so, you can you anykind of text editor (such as Notepad).";
 $msgInstallAbort = "SETUP ABORTED";
 $msgInstall1 = "If there is no error message above, installation is successfull.";
 $msgInstall2 = "2 tables have been created in your SQL base";
 $msgInstall3 = "You can now open the main interface";
 $msgInstall4 = "In order to fill your table when pages are loaded, you must put a tag in monitored pages.";

 $msgUpgradeComments ="This new version of ezBOO WebStats uses the same table <b>logezboo</b> as previous 
  						versions.<br>
  						If countries are not written in english, you must erase table <b>liste_domaine</b> 
  						et launch setup.<br>
  						This will have no effect on the table <b>logezboo</b> .<br>
  						Error message is normal. :-)";


$langStats="Statistics";
?>