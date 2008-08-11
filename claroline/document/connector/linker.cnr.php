<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Resource Resolver for the Document tool
 *
 * @version 1.9 $Revision$
 * @copyright (c) 2001-2008 Universite catholique de Louvain (UCL)
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @author claroline Team <cvs@claroline.net>
 * @package CLDOC
 *
 */

FromKernel::uses('fileManage.lib', 'file.lib');

class CLDOC_Resolver implements ModuleResourceResolver
{
    public function resolve ( ResourceLocator $locator )
    {
        if ( $locator->hasResourceId() )
        {
            $context = Claro_Context::getCurrentContext();
            $context[CLARO_CONTEXT_COURSE] = $locator->getCourseId();
            
            if ( $locator->inGroup() )
            {
                $context[CLARO_CONTEXT_GROUP] = $locator->getGroupId();
            }
                
            $path = get_path('coursesRepositorySys') . claro_get_course_path( $locator->getCourseId() );
            
            // in a group
            if ( $locator->inGroup() )
            {
               $groupData = claro_get_group_data ( $context );
                
                $path .= '/group/' . $groupData['directory'];
                $groupId = $locator->getGroupId();
            }
            else
            {
                $path .= '/document';
            }
            
            $path .= '/' . ltrim( $locator->getResourceId(), '/' );
            $resourcePath = '/' . ltrim( $locator->getResourceId(), '/' );
            
            $path = secure_file_path( $path );
            
            if ( !file_exists($path) )
            {
                throw new Exception("Resource not found {$path}");
            }
            elseif ( is_dir( $path ) )
            {
                $url = new Url( get_module_entry_url('CLDOC') );
                $url->addParam('cmd', 'exChDir' );
                $url->addParam( 'file', base64_encode( $resourcePath ) );
                
                return $url->toUrl();
            }
            else
            {
                
                
                return claro_get_file_download_url( $resourcePath, Claro_Context::getUrlContext($context) );
            }
        }
        else
        {
            return get_module_entry_url('CLDOC');
        }
    }

    public function getResourceName( ResourceLocator $locator)
    {
        $path = $locator->getResourceId();
        
        return str_replace( '/', ' > ', $path );
    }
}

/**
 * Class Document Navigator
 *
 * @package CLDOC
 * @subpackage CLLINKER
 *
 * @author Fallier Renaud <renaud.claroline@gmail.com>
 */
class CLDOC_Navigator implements ResourceNavigator
{
    public function getResourceList( ResourceLocator $locator )
    {
        // in a course
        
        $path = get_path('coursesRepositorySys') . claro_get_course_path( $locator->getCourseId() );
        
        $groupId = null;
        
        // in a group
        if ( $locator->inGroup() )
        {
            $groupData = claro_get_group_data ( array(
                CLARO_CONTEXT_COURSE => $locator->getCourseId(),
                CLARO_CONTEXT_GROUP => $locator->getGroupId()
            ));
            
            $path .= '/group/' . $groupData['directory'];
            $groupId = $locator->getGroupId();
        }
        else
        {
            $path .= '/document';
        }
        
        if ( $locator->hasResourceId() )
        {
            $path .= '/' . ltrim( $locator->getResourceId(), '/' );
        }
        
        $path = secure_file_path( $path );
        
        if ( !file_exists($path) && ! is_dir( $path ) )
        {
            throw new Exception("{$path} does not exists or is not a directory");
        }
        else
        {
            $tbl = get_module_course_tbl( array('document'), $locator->getCourseId() );
            
            $fileProperties = array();
            
            if ( ! $locator->inGroup() )
            {
                $sql = "SELECT `path`, `visibility`, `comment`\n"
                    . "FROM `{$tbl['document']}`\n"
                    . "WHERE 1"
                    ;
                    
                $res = Claroline::getDatabase()->query( $sql );
                
                foreach ( $res as $row )
                {
                    $fileProperties[$row['path']] = $row;
                }
            }
            
            $it = new DirectoryIterator( $path );
            
            $resourceList = new LinkerResourceIterator;
            
            foreach ( $it as $file )
            {
                if ( $file->isDir() && $file->isDot() )
                {
                    continue;
                }
                
                $relativePath = str_replace( '\\', '/', str_replace( $file->getPath(), '', $file->getPathname() ) );
                
                if ( $locator->hasResourceId() )
                {
                    $relativePath = '/'
                        . ltrim( $locator->getResourceId(), '/' )
                        . '/' . ltrim( $relativePath, '/' )
                        ;
                }
                
                $isVisible = true;
                
                if ( array_key_exists( $relativePath, $fileProperties ) )
                {
                    $isVisible = $fileProperties[$relativePath]['visibility'] != 'i' ? true : false;
                }
                
                $fileLoc = new ClarolineResourceLocator(
                    $locator->getCourseId(),
                    'CLDOC',
                    $relativePath,
                    $groupId
                );
                
                $fileResource = new LinkerResource(
                    $file->getFilename(),
                    $fileLoc,
                    true,
                    $isVisible,
                    $file->isDir()
                );
                
                $resourceList->addResource( $fileResource );
            }
            
            return $resourceList;
        }
    }
}
