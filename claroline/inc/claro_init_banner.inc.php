<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );

ob_start();

?>

<!-- - - - - - - - - - -   Claroline Banner  - - - - - - - - - -  -->

<div id="topBanner">

<!-- - - - - - - - - - -   Claroline platform Banner - - - - - - - - - -  -->
<div id="platformBanner">
<?php

//CAMPUS BANNER LEFT DOCK declaration

$campusBannerLeftDock = new Dock('campusBannerLeft');
$siteNameOutput   = '<span id="siteName"><a href="'.$urlAppend.'/index.php" target="_top">'.$siteName.'</a></span>';
$campusBannerLeftDock->addOutput($siteNameOutput);

echo $campusBannerLeftDock->render();

//CAMPUS BANNER RIGHT DOCK declaration

$campusBannerRightDock = new Dock('campusBannerRight');
$institutionNameOutput = '';

if( !empty($institution_name) )
{
    if( !empty($institution_url) )
        $institutionNameOutput .= '<a href="'
            .$institution_url.'" target="_top">'
            .$institution_name.'</a>'
            ;
    else
        $institutionNameOutput .= $institution_name;
}

/* --- External Link Section --- */
if( !empty($_course['extLinkName']) )
{
    $institutionNameOutput .= !empty($institution_url)
        ? ' / ' 
        : ' '
        ;
        
    if( !empty($_course['extLinkUurl']) )
    {
        $institutionNameOutput .= '<a href="' 
            . $_course['extLinkUrl'] . '" target="_top">'
            ;
    }

    $institutionNameOutput .= $_course['extLinkName'];

    if( !empty($_course['extLinkUrl']) )
    {
        $institutionNameOutput .= '</a>';
    }
}

$institutionNameOutput = '<span id="institution">' 
    . $institutionNameOutput 
    . '</span>'."\n"
    ;

$campusBannerRightDock->addOutput($institutionNameOutput);

echo $campusBannerRightDock->render();

?>
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
<?php

$userToolUrlList = array();
//USER BANNER LEFT DOCK declaration

$userBannerLeftDock = new Dock('userBannerLeft');

$userNameOutput = '<span id="userName">'. $_user ['firstName'] . ' ' . $_user ['lastName'] .' : </span>';
$userBannerLeftDock->addOutput($userNameOutput);

$userToolUrlList[]= '<a href="'. $urlAppend.'/index.php" target="_top">'. get_lang('My course list').'</a>';
$userToolList = claro_get_user_tool_list();
foreach ($userToolList as $userTool)
{
    $userToolUrlList[] = '<a href="'. get_module_url('CLCAL') . '/' . $userTool['entry'] . '" target="_top">'. get_lang('My calendar').'</a>';
}

$userToolUrlList[]  = '<a href="'. $clarolineRepositoryWeb. 'auth/profile.php" target="_top">'. get_lang('My User Account').'</a>';

if($is_platformAdmin)
{
    $userToolUrlList[] = '<a href="'. $clarolineRepositoryWeb.'admin/" target="_top">'. get_lang('Platform Administration'). '</a>';
}

$userToolUrlList[] = '<a href="'. $urlAppend.'/index.php?logout=true" target="_top">'. get_lang('Logout').'</a>';

$userBannerLeftDock->addOutput(claro_html_menu_horizontal($userToolUrlList));
echo $userBannerLeftDock->render();

//USER BANNER RIGHT DOCK declaration

$userBannerRightDock = new Dock('userBannerRight');

echo $userBannerRightDock->render();

?>

<div class="spacer"></div>
</div>

<?php

} // end if _uid

/******************************************************************************
                              COURSE SECTION
 ******************************************************************************/

if (isset($_cid))
{

//COURSE BANNER LEFT DOCK declaration

    /*------------------------------------------------------------------------
                         COURSE TITLE, CODE & TITULARS
      ------------------------------------------------------------------------*/

$courseBannerLeftDock = new Dock('courseBannerLeft');

?>
<div id="courseBanner">
<?php
    $courseName = '<div id="course"><h2 id="courseName"><a href="'. $clarolineRepositoryWeb . 'course/index.php?cid=' . htmlspecialchars($_cid) . '" target="_top">'.$_course['name'] .'</a></h2>';
    $courseBannerLeftDock->addOutput($courseName);

    $courseCodeDisplay = '<span id="courseCode">'. $_course['officialCode'] . ' - ' . $_course['titular'] . '</span>
        </div>
    <div id="courseToolList">';

    $courseBannerLeftDock->addOutput($courseCodeDisplay);

    echo $courseBannerLeftDock->render();


    //COURSE BANNER LEFT DOCK declaration

    $courseBannerRightDock = new Dock('courseBannerRight');

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


        if (isset($_courseToolDatas['name']) && !is_null($_courseToolDatas['name']) && isset($_courseToolDatas['label']))
            $_courseToolList[ $_courseToolKey ] [ 'name' ] = $toolNameList[ $_courseToolDatas['label'] ];
        else
            $_courseToolList[ $_courseToolKey ] [ 'name' ] = get_lang($_courseToolList[ $_courseToolKey ] [ 'external_name' ]);
        // now recheck to be sure the value is really filled before going further
        if ($_courseToolList[ $_courseToolKey ] [ 'name' ] =='')
            $_courseToolList[ $_courseToolKey ] [ 'name' ] = 'No Name';
    }

    $courseToolSelector = '<form action="'.$clarolineRepositoryWeb.'redirector.php"
      name="redirector" method="POST">
    <select name="url" size="1"
        onchange="top.location=redirector.url.options[selectedIndex].value" >';

    $courseToolSelector .= '<option value="' . $clarolineRepositoryWeb . 'course/index.php?cid=' . htmlspecialchars($_cid) .'" style="padding-left:22px;background:url('.$imgRepositoryWeb.'course.gif) no-repeat">' . get_lang('Course Home') . '</option>' . "\n";

    if (is_array($_courseToolList))
    {
        foreach($_courseToolList as $_courseToolKey => $_courseToolData)
        {
            //find correct url to access current tool

            if (isset($_courseToolData['url']))
            {
                if (!empty($_courseToolData['label']))
                $_courseToolData['url'] = get_module_url($_courseToolData['label']) . '/' . $_courseToolData['url'];
                // reset group to access course tool

                if (isset($_gid) && !$_courseToolData['external'])
                $_toolDataUrl = strpos($_courseToolData['url'], '?') !== false
                    ? $_courseToolData['url'] . '&amp;gidReset=1'
                    : $_courseToolData['url'] . '?gidReset=1'
                    ;
                else $_toolDataUrl = $_courseToolData['url'];

            }

            //find correct url for icon of the tool

            if (isset($_courseToolData['icon']))
            {
                $_toolIconUrl = $imgRepositoryWeb.$_courseToolData['icon'];
            }

            // select "groups" in group context instead of tool
            if ( isset( $_gid ) && $_gid )
            {
                $toolSelected = $_courseToolData['label'] == 'CLGRP___' ? 'selected="selected"' : '';
            }
            else
            {
                $toolSelected = $_courseToolData['id'] == $_tid ? 'selected="selected"' : '';
            }

            $courseToolSelector .= '<option value="' . $_toolDataUrl . '" '
            .   $toolSelected
            .   'style="padding-left:22px;background:url('.$_toolIconUrl.') no-repeat">'
            .    get_lang($_courseToolData['name'])
            .    '</option>'."\n"
            ;
        }
    } // end if is_array _courseToolList
    $courseToolSelector .='</select>
                            <noscript>
                            <input type="submit" name="gotool" value="go">
                            </noscript>
                            </form>';
    $courseBannerRightDock->addOutput($courseToolSelector);

} // end if is_array($courseTooList) && $isCourseAllowed

echo $courseBannerRightDock->render();

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

        $breadcrumbUrlList[]  = $urlAppend . '/index.php';
        $breadcrumbNameList[] = $siteName;

        if ( isset($_cid) )
        {
            $breadcrumbUrlList[]  = $clarolineRepositoryWeb . 'course/index.php?cid=' . htmlspecialchars($_cid);
            $breadcrumbNameList[] = $_course['officialCode'];
        }

        if ( isset($_gid) )
        {
            $breadcrumbUrlList[]  = $clarolineRepositoryWeb . 'group/index.php?cidReq=' . htmlspecialchars($_cid);
            $breadcrumbNameList[] = get_lang('Groups');
            $breadcrumbUrlList[]  = $clarolineRepositoryWeb . 'group/group_space.php?cidReq=' . htmlspecialchars($_cid).'&gidReq=' . (int) $_gid;
            $breadcrumbNameList[] = $_group['name'];
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

        echo claro_html_breadcrumbtrail($breadcrumbNameList, $breadcrumbUrlList,
                                        ' &gt; ', $imgRepositoryWeb . 'home.gif');

    if ( is_null($_uid) )
    {
        echo "\n".'<div id="toolViewOption" style="padding-right:10px">'
            .'<a href="'.$clarolineRepositoryWeb.'auth/login.php'
            .'?sourceUrl='.urlencode( (isset( $_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on'||$_SERVER['HTTPS']==1) ? 'https://' : 'http://'). $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']). '" target="_top">'
            .get_lang('Login')
            .'</a>'
            .'</div>'."\n";
    }
    elseif ($_cid && ! $is_courseMember && $_course['registrationAllowed'] && ! $is_platformAdmin )
    {
        echo '<div id="toolViewOption">'
        .    '<a href="' . $clarolineRepositoryWeb . 'auth/courses.php?cmd=exReg&course='.$_cid.'">'
        .     '<img src="' . $imgRepositoryWeb . 'enroll.gif" alt=""> '
        .    '<b>'.get_lang('Enrolment').'</b>'
        .    '</a>'
        .    '</div>' . "\n";
    }
    elseif ( claro_is_display_mode_available() )
    {
        echo "\n".'<div id="toolViewOption">'                    ."\n";

        if ( isset($_REQUEST['View mode']) )
        {
            echo claro_disp_tool_view_option($_REQUEST['View mode']);
        }
        else
        {
            echo claro_disp_tool_view_option();
        }
        
        if ( $is_platformAdmin && ! $is_courseMember )
        {
            echo ' | <a href="' . $clarolineRepositoryWeb . 'auth/courses.php?cmd=exReg&course='.$_cid.'">'
            .     '<img src="' . $imgRepositoryWeb . 'enroll.gif" alt=""> '
            .    '<b>'.get_lang('Enrolment').'</b>'
            .    '</a>'
            ;
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
if ( get_conf('claro_brailleViewMode',false))
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
