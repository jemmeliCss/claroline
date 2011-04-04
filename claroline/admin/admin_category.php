<?php //$Id: admin_class_cours.php 12608 2010-09-15 11:20:46Z abourguignon $

/**
 * CLAROLINE
 *
 * Management tools for categories' tree.
 *
 * @version     $Revision: 11767 $
 * @copyright   (c) 2001-2011, Universite catholique de Louvain (UCL)
 * @license     http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @author      Claro Team <cvs@claroline.net>
 * @author      Antonin Bourguignon <antonin.bourguignon@claroline.net>
 * @since       1.10
 */

// Reset session variables
$cidReset = true; // course id
$gidReset = true; // group id
$tidReset = true; // tool id

// Load Claroline kernel
require_once dirname(__FILE__) . '/../inc/claro_init_global.inc.php';

// Security check: is the user logged as administrator ?
if ( ! claro_is_user_authenticated() ) claro_disp_auth_form();
if ( ! claro_is_platform_admin() ) claro_die(get_lang('Not allowed'));

// Initialisation of global variables and used classes and libraries
require_once get_path('incRepositorySys') . '/lib/clarocategory.class.php';
include claro_get_conf_repository() . 'CLHOME.conf.php';

// Instanciate dialog box
$dialogBox = new DialogBox();

// Build the breadcrumb
ClaroBreadCrumbs::getInstance()->prepend( get_lang('Administration'), get_path('rootAdminWeb') );
$nameTools = get_lang('Categories');

// Get the cmd and id arguments
$cmd   = isset($_REQUEST['cmd'])?$_REQUEST['cmd']:null;
$id    = isset($_REQUEST['categoryId'])?$_REQUEST['categoryId']:null;

// Javascript confirm pop up declaration for header
$htmlHeadXtra[] =
'<script>
function confirmation (name)
{
    if (confirm("' . clean_str_for_javascript(get_lang('Are you sure to delete')) . '"+\' \'+ name + "? "))
        {return true;}
    else
        {return false;}
}
</script>'; // TODO error in the display of questions marks in this string

// Initialize output
$out = '';

// Display page title
$out .= claro_html_tool_title($nameTools);

switch ( $cmd )
{
    // Display form to create a new category
    case 'rqAdd' :
        $category = new claroCategory();
        $dialogBox->form( $category->displayForm() );
    break;
    
    // Create a new category
    case 'exAdd' :
        $category = new claroCategory();
        $category->handleForm();
        
        if ( $category->validate() )
        {
            $category->save();
            $dialogBox->success( get_lang('Category created') );
        }
        else
        {
            if ( claro_failure::get_last_failure() == 'category_duplicate_code')
            {
                $dialogBox->error( get_lang('This code already exists') );
            }
            elseif ( claro_failure::get_last_failure() == 'category_missing_field')
            {
                $dialogBox->error( get_lang('Some fields are missing') );
            }
            
            $dialogBox->form( $category->displayForm() );
        }
    break;
    
    // Display form to edit a category
    case 'rqEdit' :
        $category = new claroCategory();
        if ($category->load($id))
            $dialogBox->form( $category->displayForm() );
        else
            $dialogBox->error( get_lang('Category not found') );
    break;
    
    // Edit a new category
    case 'exEdit' :
        $category = new claroCategory();
        $category->handleForm();
        
        if ( $category->validate() )
        {
            $category->save();
            $dialogBox->success( get_lang('Category modified') );
        }
        else
        {
            if ( claro_failure::get_last_failure() == 'category_duplicate_code' )
            {
                $dialogBox->error( get_lang('This code already exists') );
            }
            elseif ( claro_failure::get_last_failure() == 'category_self_linked' )
            {
                $dialogBox->error( get_lang('Category can\'t be its own parent') );
            }
            elseif ( claro_failure::get_last_failure() == 'category_child_linked' )
            {
                $dialogBox->error( get_lang('Category can\'t be linked to one of its own children') );
            }
            elseif ( claro_failure::get_last_failure() == 'category_missing_field' )
            {
                $dialogBox->error( get_lang('Some fields are missing') );
            }
            
            $dialogBox->form( $category->displayForm() );
        }
    break;
    
    // Delete an existing category
    case 'exDelete' :
        $category = new claroCategory();
        if ($category->load($id))
            if ( ClaroCategory::countSubCategories($category->id) > 0 )
            {
                $dialogBox->error( get_lang('You cannot delete a category having sub categories') );
            }
            else
            {
                $category->delete();
                $dialogBox->success( get_lang('Category deleted.  Courses linked to this category have been linked to the root category.') );
            }
        else
            $dialogBox->error( get_lang('Category not found') );
    break;
    
    // Shift or displace category (up)
    case 'exMoveUp' :
        $category = new claroCategory();
        if ($category->load($id))
        {
            $category->decreaseRank();
            
            if ( claro_failure::get_last_failure() == 'category_no_predecessor')
            {
                $dialogBox->error( get_lang('This category can\'t be moved up') );
            }
            else
            {
                $dialogBox->success( get_lang('Category moved up') );
            }
        }
        else
            $dialogBox->error( get_lang('Category not found') );
    break;
    
    // Shift or displace category (down)
    case 'exMoveDown' :
        $category = new claroCategory();
        $category = new claroCategory();
        if ($category->load($id))
        {
            $category->increaseRank();
            
            if ( claro_failure::get_last_failure() == 'category_no_successor')
            {
                $dialogBox->error( get_lang('This category can\'t be moved down') );
            }
            else
            {
                $dialogBox->success( get_lang('Category moved down') );
            }
        }
        else
            $dialogBox->error( get_lang('Category not found') );
    break;
    
    // Change the visibility of a category
    case 'exVisibility' :
        $category = new claroCategory(null, null, null, null, null, null, null, null);
        if ($category->load($id))
        {
            if( $category->swapVisibility())
            {
                $dialogBox->success( get_lang('Category\'s visibility modified') );
            }
            else
            {
                switch ( claro_failure::get_last_failure() )
                {
                    case 'category_not_found' :
                        $dialogBox->error( get_lang('Error : Category not found') );
                        break;
                }
            }
        }
        else
            $dialogBox->error( get_lang('Category not found') );
    break;
}

// Display dialog box
$out .= $dialogBox->render();

// Display categories array
$categories = claroCategory::getAllCategories();

    // "Create category" link
$out .=
     '<p>'
.    '<a class="claroCmd" href="' . $_SERVER['PHP_SELF'] . '?cmd=rqAdd">'
.    '<img src="' . get_icon_url('category') . '" />' . get_lang('Create a category')
.    '</a>'
.    '</p>' . "\n";

if ((get_conf('categories_order_by') != 'rank'))
    $out .=
            '<p>'.get_block('blockCategoriesOrderInfo').'</p>';
    // Array header
$out .=
     '<table class="claroTable emphaseLine" width="100%" border="0" cellspacing="2">' . "\n"
.    '<thead>' . "\n"
.    '<tr class="headerX">' . "\n"
     // Array titles
.    '<th>' . get_lang('Category label') . '</th>' . "\n"
.    '<th>' . get_lang('Dedicated course') . '</th>' . "\n"
.    '<th>' . get_lang('Courses') . '</th>' . "\n"
.    '<th>' . get_lang('Visibility') . '</th>' . "\n"
.    '<th>' . get_lang('Edit') . '</th>' . "\n"
.    '<th>' . get_lang('Delete') . '</th>' . "\n"
.    ((get_conf('categories_order_by') == 'rank')?
        ('<th colspan="2">' . get_lang('Order') . '</th>'."\n"):
        (''))
.    '</tr>' . "\n"
.    '</thead>' . "\n";

    // Array body
$out .=
    '<tbody>' . "\n";

if (count($categories) == 0)
{
    $out .= '<tr><td colspan="7">'
        .get_lang('There are no cateogries right now.  Use the link above to add some.')
        .'</td></tr>';
}
else
{
    //TODO: hide uparrows/downarrows when they are useless/ineffective (get_icon_url('move_up/down'))
    foreach ($categories as $elmt)
    {
        $out .=
            '<tr>'
        .   '<td>' . str_repeat('&nbsp;', 4*$elmt['level']) . $elmt['name'] . ' (' . $elmt['code'] . ')</td>'
        .   '<td>' . (!is_null($elmt['dedicatedCourse']) ? ($elmt['dedicatedCourse'] . ' (' . $elmt['dedicatedCourseCode'] . ')') : ('')) . '</td>'
        .   '<td align="center">' . $elmt['nbCourses'] . '</td>'
        .   '<td align="center">'
        .       '<a href="' . htmlspecialchars(URL::Contextualize('?cmd=exVisibility&amp;categoryId=' . $elmt['id'])) . '">' . "\n"
        .       '<img src="' . get_icon_url($elmt['visible']?'visible':'invisible') . '" alt="Change visibility" />' . "\n"
        .       '</a>'
        .   '</td>'
        .   '<td align="center">'
        .       '<a href="' . htmlspecialchars(URL::Contextualize('?cmd=rqEdit&amp;categoryId=' . $elmt['id'])) . '">' . "\n"
        .       '<img src="' . get_icon_url('edit') . '" alt="Edit category" />' . "\n"
        .       '</a>'
        .   '</td>'
        .   '<td align="center">'
        .       '<a href="' . htmlspecialchars(URL::Contextualize('?cmd=exDelete&amp;categoryId=' . $elmt['id'])) . '"'
        .        ' onclick="return confirmation(\'' . clean_str_for_javascript($elmt['name']) . '\');">' . "\n"
        .       '<img src="' . get_icon_url('delete') . '" alt="Delete category" />' . "\n"
        .       '</a>'
        .   '</td>'
        .    ((get_conf('categories_order_by') == 'rank')?
                ('<td align="center">'
        .       '<a href="' . htmlspecialchars(URL::Contextualize('?cmd=exMoveUp&amp;categoryId=' . $elmt['id'])) . '">' . "\n"
        .       '<img src="' . get_icon_url('move_up') . '" alt="Move up category" />' . "\n"
        .       '</a>'
        .   '</td>'
        .   '<td align="center">'
        .       '<a href="' . htmlspecialchars(URL::Contextualize('?cmd=exMoveDown&amp;categoryId=' . $elmt['id'])) . '">' . "\n"
        .       '<img src="' . get_icon_url('move_down') . '" alt="Move down category" />' . "\n"
        .       '</a>'
        .   '</td>'):
                (''))
        .   '</tr>';
    }
}

$out .=
    '</tbody>'
.   '</table>' . "\n";

// Append output
$claroline->display->body->appendContent($out);

// Generate output
echo $claroline->display->render();