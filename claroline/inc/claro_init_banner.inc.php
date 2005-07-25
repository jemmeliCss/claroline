<?php // $Id$
if ((bool) stristr($_SERVER['PHP_SELF'], basename(__FILE__))) die('---');

ob_start();

?>

<!-- - - - - - - - - - -   Claroline Banner  - - - - - - - - - -  -->

<div id="topBanner">

<div id="platformBanner">

<span id="siteName"><a href="<?php echo $rootWeb?>index.php" target="_top"><?php echo $siteName ?></a></span>
<span id="institution">
<a href="<?php echo $institution_url ?>" target="_top"><?php echo $institution_name ?></a>
<?php

if ($_course['extLink']['name'] != '')    /* --- External Link Section --- */
{
    echo ' / ';
    if ($_course['extLink']['url'] != '')
    {
        echo '<a href="' . $_course['extLink']['url'] . '" target="_top">';
    }

    echo $_course['extLink']['name'];
    
    if ($_course['extLink']['url'] != '')
    {
            echo '</a>'                                     ."\n";
    }
}
?>
</span>

<div class="spacer"></div>
</div>



<?php
/******************************************************************************
                                  USER SECTION
 ******************************************************************************/


if($_uid)
{
?>

<div id="userBanner">
<span id="userName"><?php echo $_user ['firstName'] . ' ' . $_user ['lastName'] ?> : </span>
<a href="<?php echo $rootWeb?>index.php" target="_top"><?php echo $langMyCourses; ?></a> | 
<a href="<?php echo $clarolineRepositoryWeb ?>calendar/myagenda.php" target="_top"><?php echo $langMyAgenda; ?></a> | 
<?php 
if($is_platformAdmin)
{
?>
<a href="<?php echo $clarolineRepositoryWeb ?>admin/" target="_top"><?php echo $langPlatformAdministration ?></a> | 
<?php 
} 
?>
<a href="<?php echo $clarolineRepositoryWeb ?>auth/profile.php" target="_top"><?php echo $langModifyProfile; ?></a> | 
<a href="<?php echo $rootWeb?>index.php?logout=true" target="_top"><?php echo $langLogout; ?></a>
<div class="spacer"></div>
</div>

<?php
} // end if _uid

/******************************************************************************
                              COURSE SECTION
 ******************************************************************************/

if (isset($_cid))
{
    /*------------------------------------------------------------------------
                         COURSE TITLE, CODE & TITULARS
      ------------------------------------------------------------------------*/
?>

<div id="courseBanner">


<div id="course">
<h2 id="courseName"><a href="<?php echo $coursesRepositoryWeb . $_course['path'] ?>/index.php" target="_top"><?php echo $_course['name'] ?></a></h2>
<span id="courseCode"><?php echo $_course['officialCode'] . ' - ' . $_course['titular']; ?></span>
</div>

<div id="courseToolList">
<?php

    /*------------------------------------------------------------------------
                             COURSE TOOLS SELECTOR
      ------------------------------------------------------------------------*/

/*
 * Language initialisation of the tool names
 */
if (is_array($_courseToolList) && $is_courseAllowed)
{
    $toolNameList = claro_get_tool_name_list();
    
    foreach($_courseToolList as $_courseToolKey => $_courseToolDatas)
    {
        if (is_null($_courseToolDatas['name']))
            $_courseToolList[ $_courseToolKey ] [ 'name' ] = $toolNameList[ $_courseToolDatas['label'] ];
    
        // now recheck to be sure the value is really filled before going further
        if ($_courseToolList[ $_courseToolKey ] [ 'name' ] =='')
            $_courseToolList[ $_courseToolKey ] [ 'name' ] = 'No Name';
    
    }

?>

<form action="<?php echo $clarolineRepositoryWeb ?>redirector.php" 
      name="redirector" method="POST">

<select name="url" size="1" 
        onchange="top.location=redirector.url.options[selectedIndex].value" >

<option value="<?php echo $coursesRepositoryWeb . $_course['path'] ?>/index.php">
<?php echo $langCourseHome; ?>
</option>
<?php 
    if (is_array($_courseToolList))
    {
        foreach($_courseToolList as $_courseToolKey => $_courseToolData)
        {
            echo '<option value="'.$_courseToolData['url'].'" '
            .    ( $_courseToolData['id'] == $_tid ? 'selected="selected"' : '') . '>'
            .    $_courseToolData['name']
            .    '</option>'."\n"
            ;
        }
    } // end if is_array _courseToolList
?>
</select>

<noscript>
<input type="submit" name="gotool" validationmsg="ok" value="go">
</noscript>

</form>
<?php 
    } // end if is_array($courseTooList) && $isCouseAllowed
?>
</div>
<div class="spacer"></div>
</div>



<?php
} // end if _cid
?>

</div>

<?php

/******************************************************************************
                                BREADCRUMB LINE
 ******************************************************************************/

?>
<div id="breadcrumbLine">
<?php
if( isset($_cid) || isset($nameTools) || ( isset($interbredcrump) && is_array($interbredcrump) ) )
    {
        echo '<hr />' . "\n";
            //'<img src="' . $imgRepositoryWeb . 'home.gif" alt="">'

        $breadcrumbUrlList = array();
        $breadcrumbNameList = array();

        $breadcrumbUrlList[]  = $rootWeb . 'index.php';
        $breadcrumbNameList[] = $siteName;

        if ( isset($_cid) )
        {
            $breadcrumbUrlList[]  = $coursesRepositoryWeb . $_course['path'] . '/index.php';
            $breadcrumbNameList[] = $_course['officialCode'];
        }

        if (isset($interbredcrump) && is_array($interbredcrump) )
        {
            while ( (list(,$bredcrumpStep) = each($interbredcrump)) )
            {
                $breadcrumbUrlList[] = $bredcrumpStep['url'];
                $breadcrumbNameList[] = $bredcrumpStep['name'];
            }
        }

        if (isset($nameTools) && !(isset($course_homepage) && $course_homepage == TRUE))
        {
            $breadcrumbNameList[] = $nameTools;

            if (isset($noPHP_SELF) && $noPHP_SELF)
            {
                $breadcrumbUrlList[] = null;
            }
            elseif ( isset($noQUERY_STRING) && $noQUERY_STRING) 
            {
                $breadcrumbUrlList[] = $_SERVER['PHP_SELF'];
            }
            else
            {
                // set Query string to empty if not exists
                if (!isset($_SERVER['QUERY_STRING'])) $_SERVER['QUERY_STRING'] = ''; 
                $breadcrumbUrlList[] = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
            }
        }
        
        echo claro_disp_breadcrumbtrail($breadcrumbNameList, $breadcrumbUrlList,
                                        ' &gt; ', $imgRepositoryWeb . 'home.gif');

    if ( claro_is_display_mode_available() )
    {
          echo '<div id="toolViewOption">'                    ."\n";

        if ( isset($_REQUEST['viewMode']) )
        {
            claro_disp_tool_view_option($_REQUEST['viewMode']);
        }
        else
        {
            claro_disp_tool_view_option();
        }
        echo "\n".'</div>'                                       ."\n";
    }

    echo '<div class="spacer"></div>'                       ."\n"
    .    '<hr />'                                           ."\n";

} // end if isset($_cid) isset($nameTools) && is_array($interbredcrump)
else
{
    // echo '<div style="height:1em"></div>';
}
?>

</div>

<?php
if ( isset($claro_brailleViewMode) && $claro_brailleViewMode )
{
    $claro_banner = ob_get_contents();
    ob_clean();
}
else
{
    ob_end_flush();
    $claro_banner = false;
}
?>


<!-- - - - - - - - - - -  End of Claroline Banner  - - - - - - - - - - -->