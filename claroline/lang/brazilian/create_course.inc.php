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
// create_course.php
$langLn="Lingua";


$langCreateSite="Criar um website de curso";
$langFieldsRequ="Todos os campos requeridos";
$langTitle="T�tulo do curso";
$langEx="Ex.: <i>Hist�ria da Literatura</i>";
$langFac="Categoria";
$langTargetFac="Esta � a faculdade, departamento ou escola onde o curso ser� disponibilizado"; 
$langCode="C�digo do curso";
$langMax="max. 12 caracteres, Ex.: <i>ROM2121</i>";
$langDoubt="Se voc� tem d�vids quanto ao c�digo do seu curso, consulte, ";
$langProgram="Programa do Curso</a>. Se seu curso n�o possui c�digo, seja qual for a raz�o, invente um. Por exemplo <i>INOVACAO</i> se o curso � sobre Gerenciamento de Invova��es";
$langProfessors="Professor(es)";
$langExplanation="Quado voc� clicar em OK, um site com F�rum, Agenda, Gerenciador de documentos, etc. ser� criado. Seu login, como criador do site, permitir� a voc� modific�-lo de acordo com suas necessidade.";
$langEmpty="Voc� deixou alguns campos vazios.<br>Use o bot�o Voltar e tente novamente.<br>Se voc� ignorar o c�digo do seu curso, veja o Programa do Curso";
$langCodeTaken="Este c�digo de curso j� est� sendo usado.<br>Use o bot�o Voltar e tente novamente";


// tables MySQL
$langFormula="Sinceramente, seu professor";
$langForumLanguage="portuguese_brazil";	// other possibilities are english, spanish (this uses phpbb language functions)
$langTestForum="F�rum de Teste";
$langDelAdmin="Remova isto atrav�s da ferramenta de administra��o de f�rum";
$langMessage="Quando voc� remover o f�rum de testes, isto ir� remover esta mensagem tamb�m.";
$langExMessage="Mensagem de exemplo";
$langAnonymous="An�nimo";
$langExerciceEx="Exerc�cio de exemplo";
$langAntique="Hist�ria da Filosofia Antiga";
$langSocraticIrony="Ironia Socr�tica �...";
$langManyAnswers="(mais de uma resposta pode ser verdadeira)";
$langRidiculise="Ridicularizar seu interlocutor de maneira a obter o seu concentimento quanto a estar errado.";
$langNoPsychology="N�o. Ironia Socr�tica n�o � um tipo de psicologia, ela consiste em argumenta��o.";
$langAdmitError="Admitir seus pr�prios erros para fazer com que o interlocutor fa�a o mesmo.";
$langNoSeduction="N�o. Ironia Socr�tica n�o � uma estrat�gia de sedu��o ou um m�todo baseado no exemplo.";
$langForce="Compelir seu interlocutor, por uma s�rie de quest�es e sub-quest�es, para que ele admita n�o saber o que acha que sabe.";
$langIndeed="Certamente. A Ironia Socr�tica � um m�todo interrogativo. O grego \"eirotao\" significa \"fa�a perguntas\"";
$langContradiction="Usar o princ�pio da n�o contradi��o para for�ar seu interlocutor a entrar em um beco sem sa�da.";
$langNotFalse="Essa resposta n�o � falsa. � verdade que a revela��o da ignor�ncia do interlocutor consiste em mostar conclus�es contradit�rias onde deveria conduzir as suas premissas";

// Home Page MySQL Table "accueil"
$langAgenda="Agenda";
$langLinks="Links";
$langDoc="Documentos";
$langVideo="V�deo";
$langWorks="Trabalhos do estudante";
$langCourseProgram="Programa do curso";
$langAnnouncements="An�ncios";
$langUsers="Usu�rios";
$langForums="F�runs";
$langExercices="Exerc�cios";
$langStatistics="Estat�sticas";
$langAddPageHome="Enviar p�gina e link para a Home Page";
$langLinkSite="Adicionar link na Home Page";
$langModifyInfo="Modificar informa��es do curso";

// Other SQL tables
$langAgendaTitle="Ter�a-feira, 11 de Dezembro - Primeira li��o : Newton 18";
$langAgendaText="Introdu��o geral aos princ�pios da filosofia e metodologia";
$langMicro="Entrevistas de rua";
$langVideoText="Este � um exemplo de um arquivo RealVideo. Voc� pode enviar qualquer tipo de arquivo de audio e v�deo (.mov, .rm, .mpeg...), por�m seus estudantes ter�o que possuir o plug-in correspondente para ve-lo";
$langGoogle="Mecanismo de busca r�pido e poderoso";
$langIntroductionText="Este � o texto introdut�rio do seu curso. Para substitu�-lo pelo seu pr�prio texto, clique abaixo em <b>modificar</b>.";
$langIntroductionTwo="Esta p�gina permite a qualquer estudante ou grupo enviar documentos para o website do curso. Envie arquivos HTML apenas se eles n�o contiverem imagens.";
$langCourseDescription="Escreva aqui a descri��o que ir� aparecer na lista de cursos.";
$langProfessor="Professor";
$langAnnouncementEx="Este � um exemplo de an�ncio. Apenas o professor e outros administradores do curso podem publicar an�ncios.";
$langJustCreated="Voc� acabou de criar o website do curso";
$langEnter="Entre";
$langMillikan="Experimento de Millikan";
?>