<?php // $Id$ 

unset($titreBloc);
unset($titreBlocNotEditable);
unset($questionPlan);
unset($info2Say);

$titreBloc[] ="Description";
$titreBlocNotEditable[] = FALSE;
$questionPlan[] = "Quelle est la place et la sp�cificit� du cours dans le programme&nbsp;?
Existe-t-il des cours pr�-requis&nbsp;?
Quels sont les liens avec les autres cours&nbsp;?";
$info2Say[] = "
Information permettant d'identifier le cours 
(sigle, titre, nombre d'heure de cours, de TP, ...) 
et l'enseignant (nom, pr�nom, bureau, t�l, e-mail, disponibilit�s �ventuelles).
<br>
Pr�sentation g�n�rale du cours dans le programme.";


$titreBloc[] ="Comp�tences et Objectifs";
$titreBlocNotEditable[] = TRUE;
$info2Say[] = "Pr�sentation des objectifs g�n�raux et sp�cifiques du cours, des comp�tences auxquelles la ma�trise de tels objectifs pourrait conduire."; // JCC 
$questionPlan[] = "Quels sont les apprentissages vis�s par l'enseignement&nbsp;?
<br>
Au terme du cours, quelles sont les comp�tences, les capacit�s et les connaissances que les �tudiants seront en mesure de ma�triser, de mobiliser&nbsp;?";



$titreBloc[] ="Contenu du cours";
$titreBlocNotEditable[] = TRUE;
$questionPlan[] = "Quelle est l'importance des diff�rents contenus � traiter dans le cadre du cours&nbsp;?
Quel est le niveau de difficult� de ces contenus&nbsp;? 
Comment structurer l'ensemble de la mati�re&nbsp;?  
Quelle sera la s�quence des contenus&nbsp;? 
Quelle sera la progression dans les contenus&nbsp;?";
$info2Say[] = "Pr�sentation de la table des mati�res du cours, de la structuration du 
contenu, de la progression et du calendrier";



$titreBloc[] ="Activit�s d'enseignement-apprentissage";
$titreBlocNotEditable[] = TRUE;
$questionPlan[] = "Quelles m�thodes et quelles activit�s vont-elles favoriser l'atteinte des 
objectifs d�finis pour le cours&nbsp;?
Quel est le calendrier des activit�s&nbsp;?";
$info2Say[] = "Pr�sentation des activit�s pr�vues 
(expos�s magistraux, participation attendue des �tudiants, travaux pratiques, 
s�ances de laboratoire, visites, recueil d'informations sur le terrain, 
...).";

$titreBloc[] ="Supports";
$titreBlocNotEditable[] = TRUE;
$questionPlan[] = "Existe-t-il un support de cours ? Quel type de support vais-je privil�gier ? 
Ouvert ? Ferm� ?"; // JCC 
$info2Say[] = "Pr�sentation du ou des supports de 
cours. Pr�sentation de la bibliographie, du portefeuille de documents ou 
d'une bibliographie compl�mentaire.";


$titreBloc[] ="Ressources humaines et physiques";
$titreBlocNotEditable[] = TRUE;
$questionPlan[] = "
Quelles sont les ressources humaines et physiques disponibles&nbsp;?
Quelle sera la nature de l'encadrement&nbsp;? 
Que peuvent attendre les �tudiants de l'�quipe d'encadrement ou de l'encadrement de l'enseignant&nbsp;?";
$info2Say[] = "Pr�sentation des autres 
enseignants qui vont encadrer le cours (assistants, chercheurs, 
�tudiants-moniteurs,...), des disponibilit�s des personnes, des locaux et des 
�quipements ou mat�riel informatique disponibles.";

$titreBloc[] ="Modalit�s d'�valuation";
$titreBlocNotEditable[] = TRUE;
$questionPlan[] = "Quelles modalit�s d'�valuation choisir afin d'�valuer l'atteinte des objectifs d�finis au d�but du cours&nbsp;?  
Quelles sont les strat�gies d'�valuation mises en place afin de permettre � l'�tudiant d'identifier d'�ventuelles lacunes avant la session d'examens&nbsp;?";
$info2Say[] = "Pr�cisions quant aux moyens d'�valuation (examens �crits, oraux, projets, 
travaux � remettre, ...), quant au(x) moment(s) d'�valuation formative pr�vu(s), 
�ch�ances pour la remise des travaux, aux crit�res d'�valuation, �ventuellement 
la pond�ration des crit�res ou des cat�gories de crit�res.";


?>