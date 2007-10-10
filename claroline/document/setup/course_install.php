<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }

    ############################### DOCUMENT  #################################

    // WARNING. Do not forget to adapt queries in fill_Db_course()
    // if something changed here
    
    if ( ! isset ( $courseDirectory) )
    {
        $courseDirectory = claro_get_current_course_data( 'path' );
    }

    if ( get_conf('fill_course_example',true) )
    {
        $exampleSrcPath = get_module_path('CLDOC') . '/Example_document.pdf';
        
        $exampleDestPath = get_path('coursesRepositorySys')
            . $courseDirectory . '/document/Example_document.pdf';
                    
        if ( ! file_exists( $exampleDestPath ) )
        {
            return copy( $exampleSrcPath, $exampleDestPath );
        }
    }
?>