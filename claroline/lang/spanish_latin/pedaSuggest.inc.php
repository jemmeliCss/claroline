<?php // $Id$ 
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.4                                                |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004, 2003 Universite catholique de Louvain (UCL)|
      +----------------------------------------------------------------------+
      |   Este programa es software libre; usted puede redistribuirlo y/o    | 
      |   modificarlo bajo los t�rminos de la Licencia P�blica General (GNU) | 
      |   como fu� publicada por la Fundaci�n de Sofware Libre; desde la     |
      |   versi�n 2 de esta Licencia o (a su opci�n) cualquier versi�n       |
      |   posterior.                                                         |
      |   Este programa es distribu�do con la esperanza de que sea �til,     |
      |   pero SIN NINGUNA GARANTIA; sin ninguna garant�a impl�cita de       |
      |   MERCATIBILILIDAD o ADECUACI�N PARA PROPOSITOS PARTICULARES.        |
      |   Vea la Licencia P�blica General GNU por m�s detalles.              |
      |   Usted pudo haber recibido una copia de la Licencia P�blica         |
      |   General GNU junto con este programa; sino, escriba a la Fundaci�n  |
      |   de Sofware Libre : Free Software Foundation, Inc., 59 Temple Place |
      |   - Suite 330, Boston, MA 02111-1307, USA. La licencia GNU GPL       |
      |   tambi�n est� disponible a trav�s de la world-wide-web en la        |
      |   direcci�n  http://www.gnu.org/copyleft/gpl.html                    |
      +----------------------------------------------------------------------+
      | Autores: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>                    |
      +----------------------------------------------------------------------+
      | Traducci�n :                                                         |
      |          Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Andrew Lynn       <Andrew.Lynn@strath.ac.uk>                |
      +----------------------------------------------------------------------+
      | Basado en la traducci�n al castellano de                             |
      |          Xavier Casassas Canals <xcc@ics.co.at>                      |
      | Adaptado al espa�ol latinoamericano en Agosto-2003 por               |
      |          Carlos Brys       <brys@fce.unam.edu.ar>                    |
      +----------------------------------------------------------------------+
*/

unset($titreBloc);
unset($titreBlocNotEditable);
unset($questionPlan);
unset($info2Say);

$titreBloc[] = "Descripci�n";  
$titreBlocNotEditable[] = FALSE;  
$questionPlan[] = "� Cual es el lugar espec�fico del curso en el programa o carrera ?  � Existen cursos previos requeridos ? � Cuales on los vinculos con otros cursos ?";  
$info2Say[] = "Informaci�n que permite identificar el curso (iniciales, t�tulo, cantidad de horas, ayudas ...) y docentes  (apellido, nombre, tel�fono, oficina, e-mail, disponibilidad).  Presentaci�n general del curso en el programa.";  
$titreBloc[] = "Calificaci�n y Objetivos";  
$titreBlocNotEditable[] = TRUE;  
$info2Say[] = "Presentaci�n de los objetivos generales y espec�ficos del curso, de las calificaciones requeridas para alcanzar los objetivos.";  
$questionPlan[] = "Cuales son las habilidades pretendidas por la asignatura ? Al finalizar el curso, � que calificaciones, capacidades y conocimientos tendr�n los estudiantes ?";  
$titreBloc[] = "Contenido del Curso";  
$titreBlocNotEditable[] = TRUE;  
$questionPlan[] = "� Cual es la importancia de los distintos contenidos a ser tratados en el marco del curso? � Cual es el nivel de dificultad de esos contenidos ?   � Como est� estructurada la materia ? � Cual ser� la secuencia de los contenidos ?  � Cual ser� la progresi�n de los contenidos ? ";  
$info2Say[] = "Presentaci�n de los contenidos del curso, la estructura de los contenidos, la pregresi�n y el calendario";  
$titreBloc[] = "Actividades de entrenamiento";  
$titreBlocNotEditable[] = TRUE;  
$questionPlan[] = "� Qu� m�todos y qu� actividades se usar�n para lograr los objetivos del curso?  � Cual es el calendario de esas actividades ?";  
$info2Say[] = "Presentaci�n de las actividades con correcci�n  (revisiones, participaci�n esperada de los estudiantes, trabajos pr�cticos, reuniones de laboratorio, visitas, recolecci�n de informaci�n de campo...)."; 
$titreBloc[] =" Soportes ";  
$titreBlocNotEditable[] = TRUE;  
$questionPlan[] = "� Existe un soporte en el curso ? Que tipo se sopote se v� a privilegiar ? Abierto? Cerrado ? ";  
$info2Say[] = "Presentaci�n del soporte del curso.  Presentaci�n de la bibliograf�a, el conjunto de documentos o bibliograf�a complementaria.";  
$titreBloc[] = "Recursos F�sicos y Humanos";  
$titreBlocNotEditable[] = TRUE;  
$questionPlan[] = "� Cuales son los recursos f�sicos y humanos disponibles ?   � Cual ser� la naturaleza de la infraestructura ?  � Que pueden esperar los estudiantes del equipo o la infraestructura del docente ? ";  
$info2Say[] = "Presentaci�n de los otros docentes que componen el curso  (asistentes, investigadotes, tutores ...), de la disponibilidad de personal, de los recursos, aulas, equipamiento, computadoras disponibles.";  
$titreBloc[] = "M�todos de evaluaci�n";  
$titreBlocNotEditable[] = TRUE;  
$questionPlan[] = "� Qu� m�todos de evaluaci�n se eligieron para lograr los objetivos definidos al inicio del curso ? � Cuales son las estrategias de realizaci�n de las evaluaciones a efectos que los estudiantes puedan identificar los posibles espacios de tiempo antes de los ex�menes ?";  
$info2Say[] = "Detalles precisos acerca de las formas de evaluaci�n  (ex�menes escritos, orales, proyectos, entrega de trabajos ...).";  

?>
