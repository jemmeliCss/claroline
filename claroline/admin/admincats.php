<?php // $Id$
/**
 * CLAROLINE 
 *
 * This tool can edit category tree
 *
 * @version 1.7
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @see http://www.claroline.net/wiki/index.php/CLTREE
 *
 * @package CLCOURSES
 *
 * @author Claro Team <cvs@claroline.net>
 *
 */
define ('DISP_FORM_CREATE', __LINE__);
define ('DISP_FORM_EDIT', __LINE__);
define ('DISP_FORM_MOVE', __LINE__);

$cidReset = TRUE;
$gidReset = TRUE;
$tidReset = TRUE;

// include claro main global
require '../inc/claro_init_global.inc.php';

// check if user is logged as administrator
if ( ! $_uid ) claro_disp_auth_form();
if ( ! $is_platformAdmin ) claro_die($langNotAllowed);

include_once ($includePath . '/lib/debug.lib.inc.php');
include_once ($includePath . '/lib/course.lib.inc.php');
include_once ($includePath . '/lib/faculty.lib.inc.php');

// build bredcrump
$nameTools        = $langCategories;
$interbredcrump[] = array ('url' => $rootAdminWeb, 'name' => $langAdministration);

// get table name
$tbl_mdb_names   = claro_sql_get_main_tbl();
$tbl_course      = $tbl_mdb_names['course'  ];
$tbl_course_node = $tbl_mdb_names['category'];

$controlMsg = array();

// Display variables

$display_form = null;
//Get Parameters from URL or post

$cmd = (isset($_REQUEST['cmd'])? $_REQUEST['cmd'] : '');

/**
 * Show or hide sub categories
 */

if ( isset($_REQUEST['id'])
&& empty($cmd)   )
{
    $id = $_REQUEST['id'];
    $categories = $_SESSION['categories'];

    // Change the parameter 'visible'

    if(!is_null($categories))
    {
        foreach($categories as $key=>$category)
        {
            if($category['id'] == $id)
            {
                if($categories[$key]['visible'])
                $categories[$key]['visible'] = FALSE;
                else
                $categories[$key]['visible'] = TRUE;
            }
        }
    }

    // Save in session
    $_SESSION['categories'] = $categories;
}
else
{
    // Get value from session variables
    if ( isset($_SESSION['categories']) )
    {
        $categories = $_SESSION['categories'];
    }
    else
    {
        $categories = array();
    }

    /**
     * Create a category
     */

    if($cmd == 'exCreate' )
    {
        $noQUERY_STRING=true;
        // If the new category have a name, a code and she can have child (categories or courses)
        if( !empty($_REQUEST['nameCat']) && !empty($_REQUEST['codeCat']) )
        {

            // If a category with the same code already exists we only display an error message
            $cat_data = get_cat_data(get_cat_id_from_code(addslashes($_REQUEST['nameCat'])));
            if (isset($cat_data['code']))
            {
                // Error message for attempt to create a duplicate
                $controlMsg['info'][] = $lang_faculty_CreateNotOk;
            }
            else
            {
                $nameCat   = $_REQUEST['nameCat'];
                $codeCat   = $_REQUEST['codeCat'];
                $fatherCat = $_REQUEST['fatherCat'];
                $canHaveCoursesChild = ($_REQUEST['canHaveCoursesChild'] == 1 ? 'TRUE' : 'FALSE');

                
                
                // If the category don't have as parent NULL (root), all parent of this category have a child more
                $fatherChangeChild = ($fatherCat == 'NULL') ? NULL : $fatherCat;

                addNbChildFather($fatherChangeChild,1);

                // If the parent of the new category isn't root
                if(strcmp($fatherCat, 'NULL'))
                {
                    $cat_data = get_cat_data(get_cat_id_from_code(addslashes($fatherCat)));

                    // The treePos from the new category (treePos from this father + nb_childs from this father)
                    $treePosCat = $cat_data['treePos'] + $cat_data['nb_childs'];

                    // Add 1 to all category who have treePos >= of the treePos of the new category
                    $sql_ChangeTree=" UPDATE `" . $tbl_course_node . "`
                                      SET treePos = treePos+1 
                                      WHERE treePos >= '" . $treePosCat . "'";

                    claro_sql_query($sql_ChangeTree);
                }
                else    // The parent of the new category is root
                {
                    // Search the maximum treePos
                    $treePosCat = search_max_tree_pos() + 1;
                }

                // Insert the new category to the table

                $sql_InsertCat=" INSERT INTO `". $tbl_course_node ."`
                                 (name, code, bc , nb_childs, canHaveCoursesChild, canHaveCatChild,
                                  treePos ,code_P )
                                 VALUES ('". addslashes($nameCat)."','".addslashes($codeCat)."',NULL,'0','".$canHaveCoursesChild."','TRUE',
                                  '".(int)$treePosCat."'";
                if ($fatherCat == "NULL")
                {
                    $sql_InsertCat .= ",NULL)";
                }
                else
                {
                    $sql_InsertCat .= ",'".addslashes($fatherCat)."')";
                }

                claro_sql_query($sql_InsertCat);

                // Confirm creating
                $controlMsg['info'][]=$lang_faculty_CreateOk;

            }
        }
        else // if the new category don't have a name or a code or she can't have child (categories or courses)
        {
            if(empty($_REQUEST['nameCat']))
            $controlMsg['error'][] = $lang_faculty_NameEmpty;

            if(empty($_REQUEST['codeCat']))
            $controlMsg['error'][] = $lang_faculty_CodeEmpty;
        }
    }

    /**
     * If you move the category in the same father of the bom
     */

    if($cmd == 'exUp' || $cmd == 'exDown')
    {
        $noQUERY_STRING=true;

        $extremesTreePos =  get_extremesTreePos();
        $treePosMin=$extremesTreePos['minimum'];
        $treePosMax=$extremesTreePos['maximum'];

        // Search the category who move in the bom
        $i=0;
        while( $i < count($categories) && $categories[$i]['id'] != $_REQUEST['id'])
        $i++;

        /**
         * If Up the category and the treePos of this category isn't the first category
         */

        if($cmd=='exUp' && $i >= $treePosMin )
        {
            // Search the previous brother of this category
            $j=$i-1;
            while($j>0 && ($categories[$j]['code_P']!=$categories[$i]['code_P'])) $j--;

            // If they are a brother
            if($categories[$j]['code_P'] == $categories[$i]['code_P']) 
            {
                // change the brother and his children
                for($k = 0; $k <= $categories[$j]['nb_childs']; $k++)
                {
                    $searchId = $categories[$j + $k]['id'];
                    $newTree = $categories[$j]['treePos'] + $categories[$i]['nb_childs'] + 1 + $k;

                    $sql_Update = " UPDATE `" . $tbl_course_node . "`
                                    SET treePos='" . (int) $newTree . "' 
                                    WHERE id='". (int) $searchId."'";
                    claro_sql_query($sql_Update) ;
                }

                // change the choose category and his childeren
                for($k=0; $k <= $categories[$i]['nb_childs']; $k++)
                {
                    $searchId = $categories[$i+$k]['id'];
                    $newTree  = $categories[$i]['treePos'] - $categories[$j]['nb_childs'] - 1 + $k;

                    $sql_Update = " UPDATE `" . $tbl_course_node . "`
                                    SET treePos='". (int) $newTree . "' 
                                    WHERE id='". (int) $searchId . "'";
                    claro_sql_query($sql_Update) ;
                }

                // Confirm move
                $controlMsg['info'][] = $lang_faculty_MoveOk;
            }
        }

        /**
         * If Up the category and the treePos of this category isn't the last category
         */

        if ($cmd=='exDown' && $i < $treePosMax-1 )
        {
            // Search the next brother
            $j = $i+1;
            while($j<=count($categories) && ($categories[$j]['code_P'] != $categories[$i]['code_P']))
            $j++;

            // If they are a brother
            if($categories[$j]['code_P'] == $categories[$i]['code_P'])
            {
                // change the brother and his children
                for($k=0; $k <= $categories[$j]['nb_childs']; $k++)
                {
                    $searchId = $categories[$j+$k]['id'];
                    $newTree  = $categories[$j]['treePos'] - $categories[$i]['nb_childs'] - 1 + $k;

                    $sql_Update = " UPDATE `" .  $tbl_course_node . "`
                                    SET treePos='" . (int) $newTree . "' 
                                    WHERE id='" . (int) $searchId."'";
                    claro_sql_query($sql_Update);
                }

                // change the choose category and his childeren
                for($k = 0; $k <= $categories[$i]['nb_childs']; $k++)
                {
                    $searchId=$categories[$i+$k]['id'];
                    $newTree=$categories[$i]['treePos'] + $categories[$j]['nb_childs'] + 1 + $k;

                    $sql_Update = " UPDATE `" . $tbl_course_node . "`
                                    SET treePos='" . (int) $newTree . "' 
                                    WHERE id='" . (int) $searchId . "'";
                    claro_sql_query($sql_Update) ;
                }

                //Confirm move
                $controlMsg['info'][] = $lang_faculty_MoveOk;
            }
        }

    }

    /**
     * If you delete a category
     */

    if($cmd == 'exDelete')
    {
        $noQUERY_STRING=true;

        // Search information about category
        $cat_data = get_cat_data($_REQUEST['id']);
        if ($cat_data)
        {
            // we delete if we do not encounter any problem...default is that there is no problem, then we check
            $delok = TRUE;

            $code_cat     = $cat_data['code'];
            $code_parent  = $cat_data['code_P'];
            $nb_childs    = $cat_data['nb_childs'];
            $treePos      = $cat_data['treePos'];

            // Look if there isn't any subcategory in this category first
            if($nb_childs > 0)
            {
                $controlMsg['error'][]=$lang_faculty_CatHaveCat;
                $delok = FALSE;
            }

            // Look if they aren't courses in this category
            $sql_courseQty= "SELECT count(cours_id) num
                                 FROM `" . $tbl_course . "` 
                                 WHERE faculte='" . addslashes($code_cat) . "'";
            $courseQty= claro_sql_query_get_single_value($sql_courseQty);

            if ($courseQty > 0)
            {
                $controlMsg['error'][]=$lang_faculty_CatHaveCourses;
                $delok = FALSE;
            }

            if ($delok == TRUE)
            {
                if (delete_node( $_REQUEST['id'] )) $controlMsg['info'][] = $lang_faculty_DeleteOk;
                else                                $controlMsg['info'][] = $langUnableDeleteCategory;
            }
        }

    }

    /**
     * Create a category : display form
     */

    if($cmd == 'rqCreate')
    {
        $display_form = DISP_FORM_CREATE;
                
        // try to retrieve previsiously posted parameters for the new category

        $editedCat_Name = isset($_REQUEST['nameCat']) ? $_REQUEST['nameCat'] : '';
        $editedCat_Code = isset($_REQUEST['codeCat']) ? $_REQUEST['codeCat'] : '';
        $canHaveCoursesChild = isset($_REQUEST['canHaveCoursesChild']) ? $_REQUEST['canHaveCoursesChild'] : '';

    }

    /**
     * Edit a category : display form
     */

    if($cmd == 'rqEdit' && isset($_REQUEST['id']))
    {


        // Search information of the category edit
        $editedCat_data = get_cat_data( $_REQUEST['id'] );

        if ($editedCat_data)
        {
            $display_form = DISP_FORM_EDIT;
            $editedCat_Id                  = $editedCat_data['id'];
            $editedCat_Name                = $editedCat_data['name'];
            $editedCat_Code                = $editedCat_data['code'];
            $editFather                    = $editedCat_data['code_P'];
            $editedCat_CanHaveCatChild     = $editedCat_data['canHaveCatChild'];
            $editedCat_CanHaveCoursesChild = $editedCat_data['canHaveCoursesChild'];

            unset ($editedCat_data);
        }
    }

    /**
     * Move a category : display form
     */

    if($cmd == 'rqMove')
    {
        // Search information of the category edit
        $editedCat_data = get_cat_data( $_REQUEST['id'] );

        if ($editedCat_data)
        {
            $display_form = DISP_FORM_MOVE;
            $editedCat_Id                  = $editedCat_data['id'];
            $editedCat_Name                = $editedCat_data['name'];
            $editedCat_Code                = $editedCat_data['code'];
            $editFather                    = $editedCat_data['code_P'];
            $editedCat_CanHaveCatChild     = $editedCat_data['canHaveCatChild'];
            $editedCat_CanHaveCoursesChild = $editedCat_data['canHaveCoursesChild'];

            unset ($editedCat_data);
        }
    }

    /**
     * Change information of category : do change in db
     */

    if( $cmd == 'exChange' )
    {
        $noQUERY_STRING = true;

        // Search information
        if ($facultyEdit = get_cat_data($_REQUEST['id']))
        {
            $doChange = true;

            // See if we try to set the categorie as a cat that can not have course
            // and that the cat already contain courses
            if (isset($_REQUEST['canHaveCoursesChild']) && $_REQUEST['canHaveCoursesChild'] == 0)
            {
                $sql_SearchCourses= " SELECT count(cours_id) num
                                      FROM `" . $tbl_course . "` 
                                      WHERE faculte='" . addslashes($facultyEdit['code']) . "'";
                $res_SearchCourses = claro_sql_query_get_single_value($sql_SearchCourses);
        
                if($res_SearchCourses > 0)
                {
                    $controlMsg['warning'][] = $lang_faculty_HaveCourses;
                    $doChange = false;
                }
            }
        }
        else 
        {
            $controlMsg['warning'][] = $lang_faculty_NoCat;
            $doChange = false;
        }
        
        // Edit a category (don't move the category)
        if(!isset($_REQUEST['fatherCat']) && $doChange)
        {
            $canHaveCoursesChild=($_REQUEST['canHaveCoursesChild'] == 1 ? 'TRUE' : 'FALSE');

            // If nothing is different
            if(($facultyEdit['name'] != $_REQUEST['nameCat']) && ($facultyEdit['code'] != $_REQUEST['codeCat'])
            && ($facultyEdit['canHaveCoursesChild'] != $canHaveCoursesChild) )
            {
                $controlMsg['warning'][] = $lang_faculty_NoChange;
            }
            else
            {
                // If the category can't have course child, look if they haven't already
                if($canHaveCoursesChild == 'FALSE' )
                {
                    $sql_SearchCourses = " SELECT count(cours_id) num
                                           FROM `" . $tbl_course . "` 
                                           WHERE faculte='" . addslashes( $facultyEdit['code']) . "'";
                    
                    $array=claro_sql_query_fetch_all($sql_SearchCourses);

                    if($array[0]['num'] > 0)
                    {
                        $controlMsg['warning'][] = $lang_faculty_HaveCourses;
                        $canHaveCoursesChild="TRUE";
                    }
                    else
                    {
                        $sql_ChangeInfoFaculty= " UPDATE `" . $tbl_course_node . "`
                                                  SET name='" . addslashes($_REQUEST['nameCat']) . "',
                                                      code='" . addslashes($_REQUEST['codeCat']) . "',
                                                      canHaveCoursesChild='" . $canHaveCoursesChild . "' 
                                                  WHERE id='" . (int) $_REQUEST['id'] . "'";
                        claro_sql_query($sql_ChangeInfoFaculty);
                        $controlMsg['warning'][]=$lang_faculty_EditOk;
                    }
                }
                else
                {
                    $sql_ChangeInfoFaculty= " UPDATE `" . $tbl_course_node . "`
                                              SET name='". addslashes($_REQUEST["nameCat"]) ."',
                                                  code='". addslashes($_REQUEST["codeCat"]) ."',
                                                  canHaveCoursesChild='".$canHaveCoursesChild."' 
                                                  WHERE id='". (int)$_REQUEST["id"]."'";
                    claro_sql_query($sql_ChangeInfoFaculty);

                    // Change code_P for his childeren
                    if($_REQUEST['codeCat'] != $facultyEdit['code'])
                    {
                        $sql_ChangeCodeParent= " UPDATE `" . $tbl_course_node . "`
                                                 SET code_P='" . addslashes($_REQUEST['codeCat']) . "' 
                                                 WHERE code_P='" . addslashes($facultyEdit['code']) . "'";
                        claro_sql_query($sql_ChangeCodeParent);
                    }

                    // Confirm edition
                    $controlMsg['info'][] = $lang_faculty_EditOk;
                }

                //Change the code of the faculte in the table cours
                if($facultyEdit['code'] != $_REQUEST['codeCat'])
                {
                    $sql_ChangeInfoFaculty=" UPDATE `" . $tbl_course . "`
                                             SET faculte='" . addslashes($_REQUEST['codeCat']) . "'
                                             WHERE faculte='" . addslashes($facultyEdit['code']) . "'";

                    claro_sql_query($sql_ChangeInfoFaculty);
                }
            }
        }
        elseif(!strcmp($facultyEdit['code_P'],$_REQUEST['fatherCat']) ||
              ($_REQUEST["fatherCat"] == 'NULL' && $facultyEdit['code_P']==NULL))
        {
            $controlMsg['warning'][]=$lang_faculty_NoChange;
        }
        else
        {
            //Move the category
            //($_REQUEST["MoveChild"]==1)
            //For the table
            $fatherCat = (!strcmp($_REQUEST['fatherCat'],'NULL') ? '' : $_REQUEST['fatherCat']);

            //Check all children to look if the new parent of this category isn't his child
            //The first and last treePos of his child
            $treeFirst = $facultyEdit['treePos'];
            $treeLast  = $facultyEdit['treePos'] + $facultyEdit['nb_childs'];

            $error=0;
            for($i=$treeFirst; $i<= $treeLast; $i++)
            {
                $sql_SearchChild = " SELECT code FROM `" . $tbl_course_node . "`
                                     WHERE treePos=" . (int) $i;
                $code = claro_sql_query_get_single_value($sql_SearchChild);

                if($_REQUEST['fatherCat'] == $code)
                $error=1;
            }

            if($error)
            {
                $controlMsg['error'][] = $lang_faculty_NoMove_1 . $facultyEdit['code'] . $lang_faculty_NoMove_2;
            }
            else
            {
                // The treePos afther his childeren
                $treePosLastChild = $facultyEdit['treePos']+$facultyEdit['nb_childs'];

                // The treePos max
                $maxTree=search_max_tree_pos();

                // The treePos of her and his childeren = max(treePos)+i
                $i=1;
                while($i <= $facultyEdit['nb_childs']+1)
                {
                    $sql_TempTree=" UPDATE `" . $tbl_course_node . "`
                                    SET treePos=" . $maxTree . "+" . $i . "
                                    WHERE treePos = " . (int) $facultyEdit['treePos'] . "+" . $i . " - 1";

                    claro_sql_query($sql_TempTree);
                    $i++;
                }

                // Change treePos of the faculty they have a treePos > treePos of the last child
                $sql_ChangeTree= " UPDATE `" . $tbl_course_node . "`
                                   SET treePos = treePos - " . (int) $facultyEdit['nb_childs'] . "-1 
                                   WHERE treePos > " . (int) $treePosLastChild . " AND treePos <= " . (int) $maxTree;

                claro_sql_query($sql_ChangeTree);

                // if the father isn't root
                if($_REQUEST['fatherCat'] != 'NULL')
                {
                    // Search treePos of the new father
                    $newFather = get_cat_data(get_cat_id_from_code($_REQUEST['fatherCat']));
                    
                    //Ajoute a tous les treePos apres le nouveau pere le nombre d enfant + 1 de celui qu on deplace
                    $sql_ChangeTree=" UPDATE `" . $tbl_course_node . "`
                                      SET treePos=treePos + " . (int)$facultyEdit['nb_childs'] . " + 1 
                                      WHERE treePos > " . (int) $newFather['treePos'] . " and treePos <= " . (int) $maxTree;

                    claro_sql_query($sql_ChangeTree);

                    // the new treePos is the treePos of the new father+1
                    $newTree = $newFather['treePos'] + 1;
                }
                else
                {
                    // The new treePos is the last treePos exist
                    $newTree = $maxTree;
                }

                // Change the treePos of her and his childeren
                $i=0;
                while($i <= $facultyEdit['nb_childs'])
                {
                    $sql_ChangeTree= " UPDATE `" . $tbl_course_node . "`
                                       SET treePos=" . $newTree . "+" . $i . " 
                                       WHERE treePos=" . $maxTree . "+" . $i . "+1";

                    claro_sql_query($sql_ChangeTree);
                    $i++;
                }

                // Change the category edit
                $sql_ChangeInfoFaculty= " UPDATE `" . $tbl_course_node . "`";
                if ($_REQUEST['fatherCat'] == 'NULL' )
                {
                    $sql_ChangeInfoFaculty .= "SET code_P IS NULL ";
                }
                else
                {
                    $sql_ChangeInfoFaculty .= "SET code_P = '" . addslashes($_REQUEST['fatherCat']) . "' ";
                }
                
                $sql_ChangeInfoFaculty .= " WHERE id='" . (int) $_REQUEST['id'] . "'";

                claro_sql_query($sql_ChangeInfoFaculty);

                $newNbChild = $facultyEdit['nb_childs'] + 1;

                // Change the number of childeren of the father category and his parent
                $fatherChangeChild=$facultyEdit['code_P'];
                
                delete_qty_child_father($fatherChangeChild, $newNbChild);

                // Change the number of childeren of the new father and his parent
                $fatherChangeChild=$_REQUEST['fatherCat'];
                addNbChildFather($fatherChangeChild,$newNbChild);

                // Search nb_childs of the new father
                $nbChildFather = get_node_descendance_count(($_REQUEST['fatherCat'] == 'NULL') ? null : $_REQUEST['fatherCat']);
                // Si le nouveau pere avait des enfants replace celui que l on vient de deplacer comme dernier enfant
                if($nbChildFather>$facultyEdit['nb_childs'] + 1)
                {
                    // Met des treePos temporaire pour celui qu on vient de deplacer et ses enfants
                    $i=1;
                    while( $i <= $facultyEdit['nb_childs'] + 1 ) 
                    {
                        $sql_TempTree = " UPDATE `" . $tbl_course_node . "`
                                          SET treePos=" . $maxTree . "+" . $i . "
                                          WHERE treePos=" . $newTree . "+" . $i . "-1";

                        claro_sql_query($sql_TempTree);
                        $i++;
                    }

                    // Deplace les enfants restant du pere
                    $i=1;
                    while($i<= ( $nbChildFather - $facultyEdit['nb_childs'] - 1 ) )
                    {
                        $sql_MoveTree= " UPDATE `" . $tbl_course_node . "`
                                         SET treePos=" . $newTree . " + " . $i . "-1
                                         WHERE treePos=" . $newTree . " + " . $facultyEdit['nb_childs'] . "+" . $i;
                        claro_sql_query($sql_MoveTree);
                        $i++;
                    }

                    // Remet les treePos de celui qu on a deplacé et de ses enfants
                    $i=1;
                    while($i <= $facultyEdit['nb_childs'] + 1)
                    {
                        $sql_TempTree= " UPDATE  `" . $tbl_course_node . "`
                                        SET
                            treePos=" . (int) $newTree . "+" . (int) $nbChildFather . '-' . (int) $facultyEdit['nb_childs'] . "-2+" . $i . "
                            WHERE treePos=".(int)$maxTree."+".$i;

                        claro_sql_query($sql_TempTree);
                        $i++;
                    }

                    // Confirm move
                    $controlMsg['info'][]=$lang_faculty_MoveOk;
                }
            }
        }
    }

    /**
     * search informations from the table
     */

    $sql_searchfaculty = " SELECT *
                           FROM `" . $tbl_course_node . "` 
                           ORDER BY treePos";
    $catList=claro_sql_query_fetch_all($sql_searchfaculty);

    $tempCategories=$categories;
    unset($categories);

    // Build the array of categories
    if ($catList)
    {
        $i=0;
        for($i=0;$i<count($catList);$i++)
        {
            $catList[$i]['visible']=TRUE;
            $categories[]=$catList[$i];
        }

        // Pour remettre a visible ou non comme prédédement
        for($i=0;$i<count($categories);$i++)
        {
            $searchId=$categories[$i]["id"];
            $j=0;
            while($j<count($tempCategories) && strcmp($tempCategories[$j]['id'],$categories[$i]['id']))
            $j++;

            if($j<count($tempCategories))
            {
                $categories[$i]['visible']=$tempCategories[$j]['visible'];
            }
        }

        $_SESSION['categories'] = $categories;

    }
    else
    {
        $controlMsg['warning'][] = $lang_faculty_NoCat;

        $categories=NULL;
        $_SESSION['categories'] = $categories;

    }
}


/**
 * prepare display
 */

$category_array = claro_get_cat_flat_list();
// If there is no current $category, add a fake option
// to prevent auto select the first in list
// to prevent auto select the first in list
if ( isset($category['id']) && is_array($category_array)
&& array_key_exists($category['id'] ,$category_array))
{
    $cat_preselect = $category['id'];
}
else
{
    $cat_preselect = 'choose_one';
    $category_array = array_merge(array('choose_one'=>'--'),$category_array);
}


/**
 * Display
 */

// display claroline header
include($includePath . '/claro_init_header.inc.php');

/**
  * Information edit for create or edit a category
  */

switch ($display_form)
{
    case DISP_FORM_CREATE :
    {
        echo claro_disp_tool_title(array( 'mainTitle' => $nameTools,'subTitle' => $langSubTitleCreate));
        if ( isset($controlMsg) && count($controlMsg) > 0 )
        {
            claro_disp_msg_arr($controlMsg);
        }

        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">' . "\n"
        .    '<input type="hidden" name="cmd" value="exCreate" >' . "\n"
        .    '<table border="0">' . "\n"
        .    '<tr>' . "\n"
        .    '<td >' . "\n"
        .    '<label for="nameCat"> ' .  $lang_faculty_NameCat . ' </label >' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="texte" name="nameCat" id="nameCat" value="' .  htmlspecialchars($editedCat_Name) . '" size="20" maxlength="100">' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td >' . "\n"
        .    '<label for="codeCat"> ' . $lang_faculty_CodeCat . ' </label >' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="texte" name="codeCat" id="codeCat" value="' . htmlspecialchars($editedCat_Code) . '" size="20" maxlength="40">' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '<label for="canHaveCoursesChild">' .  $lang_faculty_CanHaveCatCourse . '</label>' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="radio" name="canHaveCoursesChild" id="canHaveCoursesChild_1" '
        .    (isset($editedCat_CanHaveCoursesChild)
             ?    (!strcmp($editedCat_CanHaveCoursesChild,"TRUE")?'checked':'')
             :    'checked'
             )
        .    ' value="1">'
        .    '<label for="canHaveCoursesChild_1">' .  $langYes . '</label>' . "\n"
        .    '<input type="radio" name="canHaveCoursesChild" id="canHaveCoursesChild_0" '
        ;
        
        if(isset($editedCat_CanHaveCoursesChild))
            echo (!strcmp($editedCat_CanHaveCoursesChild,"FALSE")?"checked":"");
            
        echo ' value="0">' . "\n"
        .    ' ' . "\n"
        .    '<label for="canHaveCoursesChild_0">' .  $langNo . '</label>' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '<label for="fatherCat"> ' .  $lang_faculty_Father . '</label >' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<select name="fatherCat" id="fatherCat">' . "\n"
        .    '<option value="NULL" > &nbsp;&nbsp;&nbsp;' .  $siteName . '</option>'
        ;
        
        // Display each category in the select
        build_select_faculty($categories,NULL,$editFather,'');

        echo '</select>' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td><br>' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="submit" value="Ok">' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '</table>' . "\n"
        .    '</form>' . "\n"
        ;
    }
    break;
    case DISP_FORM_EDIT :
    {

        /**
         * Display information to edit a category and the bom of categories
         */

        echo claro_disp_tool_title(array('mainTitle'=>$nameTools,'subTitle'=>$langSubTitleEdit));

        if ( isset($controlMsg) && count($controlMsg) > 0 )
        {
            claro_disp_msg_arr($controlMsg);
        }
        echo '<form action="' .  $_SERVER['PHP_SELF'] . '" method="POST">' . "\n"
        .    '<input type="hidden" name="cmd" value="exChange" />' . "\n"
        .    '<table border="0">' . "\n"
        .    '<tr>' . "\n"
        .    '<td >' . "\n"
        .    '<label for="nameCat"> ' .  $lang_faculty_NameCat . '</label >' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="texte" name="nameCat" id="nameCat" value="' .  htmlspecialchars($editedCat_Name) . '" size="20" maxlength="100">' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td >' . "\n"
        .    '<label for="codeCat"> ' .  $lang_faculty_CodeCat . ' </label >' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="texte" name="codeCat" id="codeCat" value="' .  htmlspecialchars($editedCat_Code) . '" size="20" maxlength="40">' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '<label for="canHaveCoursesChild"> ' .  $lang_faculty_CanHaveCatCourse . ' </label>' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="radio" name="canHaveCoursesChild" id="canHaveCoursesChild_1"' . "\n"
        ;
        
        if(isset($editedCat_CanHaveCoursesChild))
            echo (!strcmp($editedCat_CanHaveCoursesChild,'TRUE') ? 'checked' : '');
        else
            echo "checked";
        
        echo ' value="1">' . "\n"
        .    ' ' . "\n"
        .    '<label for="canHaveCoursesChild_1">' .  $langYes . '</label>' . "\n"
        .    '' . "\n"
        .    '<input type="radio" name="canHaveCoursesChild" id="canHaveCoursesChild_0" '
        ;
        if(isset($editedCat_CanHaveCoursesChild))
            echo (!strcmp($editedCat_CanHaveCoursesChild, 'FALSE') ? 'checked' : '');

        echo ' value="0">' . "\n"
        .    '<label for="canHaveCoursesChild_0">' .  $langNo . '</label>' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td><br>' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<input type="hidden" name="id" value="' .  $editedCat_Id .'">' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="submit" value="Ok">' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '</table>' . "\n"
        .    '</form>' . "\n"
        .    '<br>' . "\n"
        ;
    }
    break;
    case  DISP_FORM_MOVE :
    {
        /**
     * Display information to change root of the category
     */

        echo claro_disp_tool_title(array('mainTitle'=>$nameTools,'subTitle'=>$langSubTitleChangeParent . $editedCat_Code));
        if ( isset($controlMsg) && count($controlMsg) > 0 )
        {
            claro_disp_msg_arr($controlMsg);
        }
        echo '<form action=" ' .  $_SERVER['PHP_SELF'] . '" method="POST">' . "\n"
        .    '<input type="hidden" name="cmd" value="exChange" />' . "\n"
        .    '<table border="0">' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '<label for="fatherCat"> ' .  $lang_faculty_Father . ' </label >' . "\n"
        .    '</td>' . "\n"
        .    '<td align="RIGHT">' . "\n"
        .    '<select name="fatherCat">' . "\n"
        .    '<option value="NULL" > &nbsp;&nbsp;&nbsp;' .  $siteName . ' </option>' . "\n"
        ;
        //Display each category in the select
        build_select_faculty($categories,NULL,$editFather, '');
        echo '</select>' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '<br>' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '</td>' . "\n"
        .    '<td>' . "\n"
        .    '<input type="hidden" name="id" value="' .  $editedCat_Id . '">' . "\n"
        .    '<input type="submit" value="Ok">' . "\n"
        .    '</td>' . "\n"
        .    '</tr>' . "\n"
        .    '</table>' . "\n"
        .    '</form>' . "\n"
        .    '<br>' . "\n"
        ;
    }
    break;
    default :
    {
        echo claro_disp_tool_title(array( 'mainTitle'=>$nameTools,'subTitle'=>$langManageCourseCategories));

        if ( isset($controlMsg) && count($controlMsg) > 0 )
        {
            claro_disp_msg_arr($controlMsg);
        }
    }
}

/**
 * Display the bom of categories and the button to create a new category
 */

echo '<p>' . "\n"
.    '<a class="claroCmd" href="' . $_SERVER['PHP_SELF'] . '?cmd=rqCreate">'
.    $langSubTitleCreate
.    '</a>' . "\n"
.    '</p>' . "\n"
.    '<table class="claroTable emphaseLine" width="100%" border="0" cellspacing="2">' . "\n"
.    '<thead>' . "\n"
.    '<tr class="headerX" align="center" valign="top">' . "\n"
// Add titles for the table
.    '<th>' . $lang_faculty_CodeCat . '</th>' . "\n"
.    '<th>' . $langCourses . '</th>'."\n"
.    '<th>' . $langEdit . '</th>'."\n"
.    '<th>' . $langMove . '</th>'."\n"
.    '<th>' . $langDelete . '</th>'."\n"
.    '<th colspan="2">' . $langOrder . '</th>'."\n"
.    '</tr>' . "\n"
.    '</thead>' . "\n"
.    '<tbody>' . "\n"
;

claro_disp_tree($categories,NULL,'');

echo '</tbody>' . "\n"
.    '</table>' . "\n"
;

include($includePath . '/claro_init_footer.inc.php');

?>
