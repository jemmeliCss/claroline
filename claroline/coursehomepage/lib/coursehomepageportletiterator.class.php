<?php // $Id$

/**
 * CLAROLINE
 *
 * @version     $Revision$
 * @copyright   (c) 2001-2010, Universite catholique de Louvain (UCL)
 * @license     http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @author      Claro Team <cvs@claroline.net>
 * @author      Antonin Bourguignon <antonin.bourguignon@claroline.net>
 * @since       1.10
 */

class CourseHomePagePortletIterator implements Iterator
{
    private     $courseId;
    private     $portlets = array();
    protected   $n = 0;
    
    public function __construct($courseId)
    {
        $this->courseId = $courseId;
        
        $tbl_mdb_names      = claro_sql_get_main_tbl();
        $tbl_chp_portlet    = $tbl_mdb_names['coursehomepage_portlet'];
        
        $sql = "SELECT courseId, rank, label, name, visible
                FROM `{$tbl_chp_portlet}`
                WHERE courseId = {$this->courseId}
                ORDER BY rank ASC";
        
        $result = Claroline::getDatabase()->query($sql);
        
        foreach($result as $portletInfos)
        {
            $portletPath = get_module_path( $portletInfos['label'] )
            . '/connector/coursehomepage.cnr.php';
            
            $portletName = $portletInfos['label'] . '_Portlet';
            
            if ( file_exists($portletPath) )
            {
                require_once $portletPath;
            }
            else
            {
                echo "Le fichier {$portletPath} est introuvable<br/>";
            }
            
            if ( class_exists($portletName) )
            {
                $portlet = new $portletName();
                $this->portlets[] = $portlet;
            }
            
            #TODO debug
            else
            {
                echo "La classe {$portletName} est introuvable<br/>";
            }
        }
    }
    
    public function __toString()
    {
        $out = '';
        
        foreach ($this->portlets as $portlet)
        {
            $out .= var_dump($portlet);
        }
        
        return $out;
    }
    
    public function rewind()
    {
        $this->n = 0;
    }
    
    public function next()
    {
        $this->n++;
    }
    
    public function key()
    {
        return 'increment '.$this->n+1;
    }
    
    public function current()
    {
        return $this->portlets[$this->n];
    }
    
    public function valid()
    {
        return $this->n < count($this->portlets);
    }
}