<?php // $Id$
/**
 * @version  CLAROLINE version 1.6
 *
 * @copyright (c) 2001, 2005 Universite catholique de Louvain (UCL)
 *
 * @license GENERAL PUBLIC LICENSE
 *
 * @author Piraux S�bastien <pir@cerdecam.be>
 * @author Lederer Guillaume <led@cerdecam.be>
 *
 * @package CLLNP
 */

  if(isset($cmd) && $cmd = "raw")
  {
              // change raw if value is a number between 0 and 100
              if (isset($_POST['newRaw']) && is_num($_POST['newRaw']) && $_POST['newRaw'] <= 100 && $_POST['newRaw'] >= 0 )
              {
                        $sql = "UPDATE `".$TABLELEARNPATHMODULE."`
                                   SET `raw_to_pass` = ". (int)$_POST['newRaw']."
                                 WHERE `module_id` = ". (int)$_SESSION['module_id']."
                                   AND `learnPath_id` = ". (int)$_SESSION['path_id'];
                        claro_sql_query($sql);

                        $dialogBox .= $langRawHasBeenChanged;
              }
  }


  echo "<hr noshade=\"noshade\" size=\"1\" />";

   //####################################################################################\\
   //############################### DIALOG BOX SECTION #################################\\
   //####################################################################################\\
   if (isset($dialogBox)&& $dialogBox!="")
   {
      echo claro_disp_message_box($dialogBox);
   }

  // form to change raw needed to pass the exercise
  $sql = "SELECT *
            FROM `".$TABLELEARNPATHMODULE."` AS LPM
           WHERE LPM.`module_id` = ". (int)$_SESSION['module_id']."
             AND LPM.`learnPath_id` = ". (int)$_SESSION['path_id'];

  $query = claro_sql_query($sql);
  $learningPath_module = mysql_fetch_array($query);

  if ($learningPath_module['lock'] == 'CLOSE') // this module blocks the user if he doesn't complete
  {
       //echo "<p>".$langModuleHelpExercise."</p>";
       echo "<form method=\"POST\" action=\"".$_SERVER['PHP_SELF']."\"><label for=\"newRaw\">";
       echo $langChangeRaw;
       echo "</label><input type=\"text\" value=\"".$learningPath_module['raw_to_pass']."\" name=\"newRaw\" id=\"newRaw\" size=\"3\" maxlength=\"3\" /> % ";
       echo "<input type=\"hidden\" name=\"cmd\" value=\"raw\" />";
       echo "<input type=\"submit\" value=\"$langOk\" />";
       echo "</form>";

  }

  // display list and form to change the exercise in module
  //display_my_exercises($dialogBox);

  // display current exercise info and change comment link
  $sql = "SELECT *, M.`comment` AS Mcomment, A.`comment` AS Acomment
            FROM `".$TABLEMODULE."` AS M,
                 `".$TABLEASSET."`  AS A,
                 `".$TABLEQUIZTEST."` AS E
           WHERE A.`module_id` = M.`module_id`
             AND M.`module_id` = ". (int)$_SESSION['module_id']."
             AND E.`id` = A.`path`";
   //echo $sql;
   $query = claro_sql_query($sql);
   $module = mysql_fetch_array($query);

   echo "<h4>".$langExerciseInModule." :</h4><p>"
          . htmlspecialchars($module['titre'])
          ."<a href=\"../exercice/admin.php?exerciseId=".$module['id']."\"><img src=\"".$imgRepositoryWeb."edit.gif\" border=\"0\" alt=\"$langModify\" /></a></p>";



?>
