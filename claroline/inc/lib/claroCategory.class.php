<?php

if ( count( get_included_files() ) == 1 ) die( '---' );

/**
 * CLAROLINE
 *
 * Category Class
 *
 * @version 1.9 $Revision: 11894 $
 *
 * @copyright 2001-2008 Universite catholique de Louvain (UCL)
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package Kernel
 * @author Claro Team <cvs@claroline.net>
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @author Sebastien Piraux <piraux@cerdecam.be>
 */



/**
 * 
 * Development notes
 * 
 * Adaptation of the previous table `faculte` (becoming `category`)
 * ----------------------------------------------------------------
 * 
 * RENAME TABLE `db_name`.`prefix_faculte`  TO `db_name`.`prefix_category` ;
 * 
 * ALTER TABLE `cl_faculte` CHANGE `code_P` `idParent` VARCHAR( 12 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ; 
 * ALTER TABLE `cl_faculte` CHANGE `treePos` `rank` INT( 10 ) UNSIGNED NULL DEFAULT NULL ;
 * /!\ Conversion of 'TRUE'/'FALSE' values into '1'/'0' !  Then: 
 * ALTER TABLE `cl_course_category` CHANGE `canHaveCoursesChild` `canHaveCoursesChild` TINYINT( 1 ) NOT NULL DEFAULT '1' ;
 * ALTER TABLE `cl_course_category` CHANGE `canHaveCatChild` `canHaveCatChild` TINYINT( 1 ) NOT NULL DEFAULT '1' ;
 * ALTER TABLE `cl_course_category` DROP INDEX `code_P` ;
 * ALTER TABLE `cl_course_category` DROP INDEX `treePos` ;
 * ALTER TABLE `cl_course_category` DROP INDEX `code` ;
 * ALTER TABLE `cl_course_category` ADD UNIQUE (`code`) ;
 * /!\ Conversion of CODES into IDS !  Then: 
 * ALTER TABLE `cl_course_category` CHANGE `idParent` `idParent` INT( 11 ) NOT NULL DEFAULT '0' ;
 * ALTER TABLE `cl_category` DROP `nb_childs` ;
 *  
 *  
 * 
 * Creation of the join table `rel_course_category`
 * ------------------------------------------------
 * 
 * CREATE TABLE `db_name`.`prefix_rel_course_category` (
 * `idCourse` INT NOT NULL ,
 * `idCategory` INT NOT NULL ,
 * `rootCourse` BOOL NOT NULL DEFAULT '0'
 * ) ENGINE = MYISAM ;
 * ALTER TABLE `db_name`.`prefix_rel_course_category` ADD PRIMARY KEY ( `idCourse` , `idCategory` ) ;
 * 
 * 
 * Adaptation of the previous table `cours`
 * ----------------------------------------
 * 
 * ALTER TABLE `db_name`.`prefix_cours` DROP `faculte` ;
 * 
 * 
 * 
 * Datas for testing purpose
 * -------------------------
 * 
 * INSERT INTO `db_name`.`prefix_category` (`id`, `name`, `code`, `idParent`, `rank`, `visible`, `canHaveCoursesChild`, `canHaveCatChild`) VALUES
 * (1, 'Sciences', 'SC', 0, 1, 1, 1, 1),
 * (2, 'Economics', 'ECO', 0, 2, 1, 1, 1),
 * (3, 'Humanities', 'HUMA', 0, 3, 1, 1, 1),
 * (4, 'Informatique', 'INFO', 0, 4, 1, 1, 1),
 * (5, 'Physique', 'PHY', 1, 3, 1, 1, 1),
 * (6, 'Chimie', 'CHIM', 1, 1, 1, 1, 1),
 * (7, 'G�ologie', 'GEO', 1, 2, 1, 1, 1),
 * (8, 'Macro Economie', 'MACROECO', 2, 1, 1, 1, 1) ;
 * 
 * INSERT INTO `db_name`.`prefix_rel_course_category` (`courseId`, `categoryId`, `rootCourse`) VALUES
 * (1, 1, 0),
 * (2, 6, 0),
 * (3, 6, 0) ;
 * 
 * 
 * TODO: stuff to fix before definitive update
 * -------------------------------------------
 * 
 * * Delete inc/lib/faculty.inc.lib.php
 * * Modify inc/lib/sql.lib.php claro_sql_get_main_tbl() (table names have to be fixed)
 * 
 * 
 * TODO: questions
 * ---------------
 * 
 * * When hiding a category, should all its children categories also get hidden ? (seems logic)
 * * What's the best way managing and displaying: (1) the number of courses in a category and 
 *   (2) the number of categories in a category ?
 *
 */

require_once dirname(__FILE__) . '/backlog.class.php';
require_once dirname(__FILE__) . '/category.lib.inc.php'; // Contains all MySQL requests for this class
require_once dirname(__FILE__) . '/../../messaging/lib/message/messagetosend.lib.php';


$jsLoader = JavascriptLoader::getInstance();
$jsLoader->load( 'claroline.ui' );

class ClaroCategory
{
    // Identifier
    public $id;

    // Name
    public $name;

    // Code
    public $code;

    // Identifier of the parent category
    public $idParent;

    // Position in the tree's level
    public $rank;
    
    // Visibility
    public $visible;

    // Allowed to possess children (true = yes, false = no)
    public $canHaveCoursesChild;

    // Allowed to have sub categories (true = yes, false = no)
    public $canHaveCatChild;
    
    // List of GET or POST parameters
    public $htmlParamList = array();    
    

    /**
     * Constructor
     */
    function ClaroCategory ($id, $name, $code, $idParent, $rank = null, $visible = 1, $canHaveCoursesChild = 1, $canHaveCatChild = 1)
    {
        $this->id                   = $id;
        $this->name                 = $name;
        $this->code                 = $code;
        $this->idParent             = $idParent;
        $this->rank                 = $rank;
        $this->visible              = $visible;
        $this->canHaveCoursesChild  = $canHaveCoursesChild;
        $this->canHaveCatChild      = $canHaveCatChild;
    }
    

    /**
     * Load category data from database in the current object
     *
     * @param $id int category identifier
     * @return boolean success
     */
    public function load ($id)
    {
        $data = claro_get_cat_datas($id);

        if ( !$data ) 
        {
            claro_failure::set_failure('category_not_found');
            return false;
        }
        else
        {            
	        $this->id                   = $id;
	        $this->name                 = $data['name'];
	        $this->code                 = $data['code'];
	        $this->idParent             = $data['idParent'];
	        $this->rank                 = $data['rank'];
	        $this->visible              = $data['visible'];
	        $this->canHaveCoursesChild  = $data['canHaveCoursesChild'];
	        $this->canHaveCatChild      = $data['canHaveCatChild'];
	        
	        return true;
        }
    }
    

    /**
     * Insert or update current category data
     *
     * @return boolean success
     */
    public function save ()
    {		
        if ( empty($this->id) )
        {
            // No id: it's a new category -> insert
            
            if( claro_insert_cat_datas($this->name, $this->code, $this->idParent, $this->rank, $this->visible, $this->canHaveCoursesChild, $this->canHaveCatChild) ) 
                return true;
            else 
                return false;
            
        }
        else
        {
            // No id: it's a new category -> update
            
            if( claro_update_cat_datas($this->id, $this->name, $this->code, $this->idParent, $this->rank, $this->visible, $this->canHaveCoursesChild, $this->canHaveCatChild) ) 
                return true;
            else 
                return false;
        }
    }
    

    /**
     * Delete current category data and content
     *
     * @return boolean success
     */
    public function delete ()
    {
        //TODO handle cases where the category has subcategories
        if ( claro_delete_cat_datas($this->id) ) 
            return true;
        else 
            return false;
    }
    
    
    /**
     * Select all categories in database from a certain point
     *
	 * @param $start_node the parent from wich we want to get the categories tree (default: 0)
	 * @param $start_level the level where we start (default: 0)
     * @return array containing all the categories organized hierarchically and ordered by rank
     */
    public static function fetchAllCategories ( $start_node = 0, $start_level = 0 )
    {
        return claro_get_all_categories($start_node, $start_level);
    }
    
    
    /**
     * Count the number of courses in the current category (DOESN'T include courses 
     * in sub categories).
     * 
     * @return integer number of courses
     */
    public function countCategoryCourses ()
    {
        return claro_count_category_courses($this->id);
    }    
    
    
    /**
     * Swap the visibility value of a category (from TRUE to FALSE or from FALSE to TRUE) 
     * and save it into the database
     * 
     * @return boolean success
     */
    public function swapVisibility () 
    {
        $this->visible = !$this->visible;
        
        if ( claro_set_cat_visibility($this->id, $this->visible) ) 
            return true;
        else 
            return false;
    }
    

    /**
     * Retrieve category data from form and fill current category with it
     */
    public function handleForm ()
    {
        if ( isset($_REQUEST['name']) )                 $this->title = trim(strip_tags($_REQUEST['name']));

        if ( isset($_REQUEST['code']) ) // Only capital letters and numbers
        {
            $this->code = trim(strip_tags($_REQUEST['code']));
            $this->code = preg_replace('/[^A-Za-z0-9_]/', '', $this->code);
            $this->code = strtoupper($this->code);
        }

        if ( isset($_REQUEST['idParent']) )             $this->idParent = trim(strip_tags($_REQUEST['idParent']));
        if ( isset($_REQUEST['rank']) )                 $this->rank = trim(strip_tags($_REQUEST['rank']));
        if ( isset($_REQUEST['visible']) )              $this->visible = trim(strip_tags($_REQUEST['visible']));
        if ( isset($_REQUEST['canHaveCoursesChild']) )  $this->canHaveCoursesChild = trim(strip_tags($_REQUEST['canHaveCoursesChild']));
        if ( isset($_REQUEST['canHaveCatChild']) )      $this->canHaveCatChild = trim(strip_tags($_REQUEST['canHaveCatChild']));
    }
    

    /**
     * Validate data from current object.  Error handling with a backlog object.
     *
     * @return boolean success
     */
    public function validate ()
    {
        //TODO don't get how this function actually works
        
        $success = true ;

        /**
         * Configuration array , define here which field can be left empty or not
         */

        //TODO make it more accurate using function get_conf('human_label_needed');
        $fieldRequiredStateList['name']                 = true;
        $fieldRequiredStateList['code']                 = true;
        $fieldRequiredStateList['idParent']             = true;
        $fieldRequiredStateList['rank']                 = false;
        $fieldRequiredStateList['visible ']             = true;
        $fieldRequiredStateList['canHaveCoursesChild']  = true;
        $fieldRequiredStateList['canHaveCatChild']      = true;

        // Validate category name
        if ( empty($this->name) && $fieldRequiredStateList['name'] )
        {
            $this->backlog->failure(get_lang('Category name needed'));
            $success = false ;
        }

        // Validate category code
        if ( empty($this->code) && $fieldRequiredStateList['code'] )
        {
            $this->backlog->failure(get_lang('Category code needed'));
            $success = false ;
        }
        
        // Check authorisation to possess courses
        if ( empty($this->visible) && $fieldRequiredStateList['visible'] )
        {
            $this->backlog->failure(get_lang('Visibility of the category must be set'));
            $success = false;
        }
        
        // Check authorisation to possess courses
        if ( empty($this->canHaveCoursesChild) && $fieldRequiredStateList['canHaveCoursesChild'] )
        {
            $this->backlog->failure(get_lang('Category must be authorized or not to have courses children'));
            $success = false;
        }

        // Check authorisation to have categories
        if ( empty($this->canHaveCatChild) && $fieldRequiredStateList['canHaveCatChild'] )
        {
            $this->backlog->failure(get_lang('Category must be authorized or not to have categories children'));
            $success = false;
        }

        return $success;
    }
    
    
    /**
     * Put the current category's datas into a string format
     * 
     * @return string
     */
    public function toString ()
    {
        $str = 
        	 'id = ' . $this->id . "\n"
        .    'name = ' . $this->name . "\n"
        .    'code = ' . $this->code . "\n"
        .    'idParent = ' . $this->idParent . "\n"
        .    'rank = ' . $this->rank . "\n"
        .    'visible = ' . $this->visible . "\n"
        .    'canHaveCoursesChild = ' . $this->canHaveCoursesChild . "\n"
        .    'canHaveCatChild = ' . $this->canHaveCatChild;
        
        return $str;
    }
    

    /**
     * Display form
     *
     * @param $cancelUrl string url of the cancel button
     * @return string html output of form
     */
    public function displayForm ($cancelUrl=null)
    {

        $languageList = claro_get_lang_flat_list();
        $categoryList = claro_get_cat_list();

        // TODO cancelUrl cannot be null
        if ( is_null($cancelUrl) )
            $cancelUrl = get_path('clarolineRepositoryWeb') . 'course/index.php?cid=' . htmlspecialchars($this->id);

        $html = '';

        $html .= '<form method="post" id="courseSettings" action="' . $_SERVER['PHP_SELF'] . '" >' . "\n"
            . claro_form_relay_context()
            . '<input type="hidden" name="cmd" value="'.(empty($this->id)?'rqProgress':'exEdit').'" />' . "\n"
            . '<input type="hidden" name="claroFormId" value="' . uniqid('') . '" />' . "\n"

            . $this->getHtmlParamList('POST');

        $html .= '<fieldset>' . "\n"
        .   '<dl>' . "\n";

        // Category name
        $html .= '<dt>'
            . '<label for="category_name">'
            . get_lang('Category name')
            . (get_conf('human_label_needed') ? '<span class="required">*</span> ':'') 
            .'</label>&nbsp;:</dt>'
            . '<dd>'
            . '<input type="text" name="category_name" id="category_name" value="' . htmlspecialchars($this->name) . '" size="60" />'
            . (empty($this->id) ? '<br /><small>'.get_lang('e.g. <em>Sciences of Economics</em>').'</small>':'')
            . '</dd>' . "\n" ;

        // Category code
        $html .= '<dt>'
            . '<label for="category_code">'
            . get_lang('Category code')
            . '<span class="required">*</span> '
            . '</label>&nbsp;:</dt>'
            . '<dd><input type="text" id="category_code" name="category_code" value="' . htmlspecialchars($this->code) . '" size="60" maxlength="12" />'
            . (empty($this->id) ? '<br /><small>'.get_lang('max. 12 characters, e.g. <em>ROM2121</em>').'</small>':'')
            . '</dd>' . "\n" ;

        // Category's parent
        $html .= '<dt>'
            . '<label for="category_parent">' 
            . get_lang('Category\'s parent') 
            . '</label>&nbsp;:</dt>'
            . '<dd><input type="text"  id="category_parent" name="category_parent" value="' . htmlspecialchars($this->idParent) . '" size="60" />'
            . '</dd>' . "\n" ;

        // Category's rank
        $html .= '<dt>'
            . '<label for="category_rank">'
            . get_lang('Category\'s rank')
            . '</label>'
            . '&nbsp;:'
            . '</dt>'
            . '<dd>'
            . '<input type="text" id="category_rank" name="category_rank" value="' . htmlspecialchars($this->rank) . '" size="60" />'
            . '</dd>' . "\n";

        // Category's visibility
        $html .= '<dt>'
            . get_lang('Category is visible') 
            . '<span class="required">*</span> '
            . ' :'
            . '</dt>'
            . '<dd>'
            . '<input type="radio" id="visible" name="visibility" value="1" ' . ($this->visible == 1 ? 'checked="checked"':'') . ' />'
            . '&nbsp;'
            . '<label for="visible">' . get_lang('Visible') . '</label><br/>'
            . '<input type="radio" id="hidden" name="visibility" value="0" ' . ($this->visible == 0 ? 'checked="checked"':'') . ' />'
            . '&nbsp;'
            . '<label for="chidden">' . get_lang('Hidden') . '</label>'
            . '<small>'.get_lang('Authorize the category to possess courses or not (opened or closed category)').'</small>'
            . '</dd>' . "\n" ;

        // Category's right to possess courses
        $html .= '<dt>'
            . get_lang('Category can possess courses') 
            . '<span class="required">*</span> '
            . ' :'
            . '</dt>'
            . '<dd>'
            . '<input type="radio" id="can_have_courses" name="can_have_courses" value="true" ' . ($this->canHaveCoursesChild == 'true' ? 'checked="checked"':'') . ' />'
            . '&nbsp;'
            . '<label for="can_have_courses">' . get_lang('Allowed to have courses') . '</label><br/>'
            . '<input type="radio" id="cant_have_courses" name="can_have_courses" value="false" ' . ($this->canHaveCoursesChild == 'false' ? 'checked="checked"':'') . ' />'
            . '&nbsp;'
            . '<label for="cant_have_courses">' . get_lang('Not allowed to have courses') . '</label>'
            . '<small>'.get_lang('Authorize the category to possess courses or not (opened or closed category)').'</small>'
            . '</dd>' . "\n" ;
            
        // Category's right to possess sub categories
        $html .= '<dt>'
            . get_lang('Category can have sub categories') 
            . '<span class="required">*</span> '
            . ' :'
            . '</dt>'
            . '<dd>'
            . '<input type="radio" id="can_have_categories" name="can_have_categories" value="true" ' . ($this->canHaveCatChild == 'true' ? 'checked="checked"':'') . ' />'
            . '&nbsp;'
            . '<label for="can_have_categories">' . get_lang('Allowed to have sub categories') . '</label><br/>'
            . '<input type="radio" id="cant_have_categories" name="can_have_categories" value="false" ' . ($this->canHaveCatChild == 'false' ? 'checked="checked"':'') . ' />'
            . '&nbsp;'
            . '<label for="cant_have_categories">' . get_lang('Not allowed to have sub categories') . '</label>'
            . '<small>'.get_lang('Authorize the category to have sub categories').'</small>'
            . '</dd>' . "\n" ;

        return $html;
    }

    /**
     * Display question of delete confirmation
     *
     * @param $cancelUrl string url of the cancel button
     * @return string html output of form
     */
    public function displayDeleteConfirmation ()
    {
        //TODO Give and warn if subcategories exist (in this case, can't delete the current category)        
        
        $paramString = $this->getHtmlParamList('GET');

        $deleteUrl = './settings.php?cmd=exDelete&amp;'.$paramString;
        $cancelUrl = './settings.php?'.$paramString ;

        $html = '';

        $html .= '<p>'
        . '<font color="#CC0000">'
        . get_lang('Are you sure to delete the category "%ccategory_name" ( %category_code ) ?', array('%category_name' => $this->name,
                                                                                                       '%category_code' => $this->code ))
        . '</font>'
        . '</p>'
        . '<p>'
        . '<font color="#CC0000">'
        . '<a href="'.$deleteUrl.'">'.get_lang('Yes').'</a>'
        . '&nbsp;|&nbsp;'
        . '<a href="'.$cancelUrl.'">'.get_lang('No').'</a>'
        . '</font>'
        . '</p>';

        return $html;
    }
    

    /**
     * Add html parameter to list
     *
     * @param $name string input name
     * @param $value string input value
     */
    public function addHtmlParam($name, $value)
    {
        $this->htmlParamList[$name] = $value;
    }
    

    /**
     * Get html representing parameter list depending on method (POST for form, GET for URL's')
     *
     * @param $method string GET OR POST (default: GET)
     * @return string html output of params for $method method
     */
    public function getHtmlParamList($method = 'GET')
    {
        if ( empty($this->htmlParamList) ) return '';

        $html = '';

        if ( $method == 'POST' )
        {
            foreach ( $this->htmlParamList as $name => $value )
            {
                $html .= '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />' . "\n" ;
            }
        }
        else // GET
        {
            $params = array();
            foreach ( $this->htmlParamList as $name => $value )
            {
                $params[] = rawurlencode($name) . '=' . rawurlencode($value);
            }

            $html = implode('&amp;', $params );
        }

        return $html;
    }
    

    /**
     * Build progress param url
     *
     * @return string url
     */
    public function buildProgressUrl ()
    {
        $url = $_SERVER['PHP_SELF'] . '?cmd=exEdit';

        $paramList = array();

        $paramList['category_name']                = $this->name;
        $paramList['category_code']                = $this->code;
        $paramList['category_idParent']            = $this->idParent;
        $paramList['category_rank']                = $this->rank;
        $paramList['category_visible']             = $this->visible;
        $paramList['category_canHavecoursesChild'] = $this->canHavecoursesChild;
        $paramList['category_canHaveCatChild']     = $this->canHaveCatChild;

        $paramList = array_merge($paramList, $this->htmlParamList);

        foreach ($paramList as $key => $value)
        {
            $url .= '&amp;' . rawurlencode($key) . '=' . rawurlencode($value);
        }

        return $url;
    }
}

?>