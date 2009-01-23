<?php

class CLLNP_MergeUser implements Module_MergeUser
{
    public function mergeCourseUsers( $uidToRemove, $uidToKeep, $courseId )
    {
        $tblList[] = 'lp_module';
        $tblList[] = 'lp_learnPath';
        $tblList[] = 'lp_rel_learnPath_module';
        $tblList[] = 'lp_asset';
        $tblList[] = 'lp_user_module_progress';
        
        $moduleCourseTbl = get_module_course_tbl( $tblList, $courseId );
        print_r($moduleCourseTbl);
        // Update lp_user_module_progress
        $sql = "UPDATE `{$moduleCourseTbl['lp_user_module_progress']}`
                SET   user_id = ".(int)$uidToKeep."
                WHERE user_id = ".(int)$uidToRemove;

        if ( ! claro_sql_query($sql) )
        {
            throw new Exception("Cannot update lp_user_module_progress in {$thisCourseCode}");
        }
        
    }
    
    public function mergeUsers( $uidToRemove, $uidToKeep )
    {
        // empty
    }
}

