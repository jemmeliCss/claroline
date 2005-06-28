<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    if( strtolower( basename( $_SERVER['PHP_SELF'] ) )
        == strtolower( basename( __FILE__ ) ) )
    {
        die("This file cannot be accessed directly! Include it in your script instead!");
    }
    
    /**
     * @version CLAROLINE 1.7
     *
     * @copyright 2001-2005 Universite catholique de Louvain (UCL)
     *
     * @license GENERAL PUBLIC LICENSE (GPL)
     * This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
     * as published by the FREE SOFTWARE FOUNDATION. The GPL is available
     * through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
     *
     * @author Frederic Minne <zefredz@gmail.com>
     *
     * @package Wiki2xhtmlArea
     */
     
    require_once dirname(__FILE__) . "/lib.javascript.php";
    
    class Wiki2xhtmlArea
    {
        var $content;
        var $attributeList;
        
        /**
         * Constructor
         * @param string content of the area
         * @param string name name of the area
         * @param int cols number of cols
         * @param int rows number of rows
         * @param array extraAttributes extra html attributes for the area
         */
        function Wiki2xhtmlArea(
            $content = ''
            , $name = 'content'
            , $cols = 80
            , $rows = 30
            , $extraAttributes = null )
        {
            $this->setContent( $content );
            
            $attributeList = array();
            $attributeList['name'] = $name;
            $attributeList['id'] = $name;
            $attributeList['cols'] = $cols;
            $attributeList['rows'] = $rows;
            
            $this->attributeList = ( is_array( $extraAttributes ) )
                ? array_merge( $attributeList, $extraAttributes )
                : $attributeList
                ;
        }
        
        /**
         * Set area content
         * @param string content
         */
        function setContent( $content )
        {
            $this->content = $content;
        }
        
        /**
         * Get area content
         * @return string area content
         */
        function getContent()
        {
            return $this->content;
        }
        
        /**
         * Get area wiki syntax toolbar
         * @return string toolbar javascript code
         */
        function getToolbar()
        {
            $toolbar = '';
            

            $toolbar .= '<script type="text/javascript" src="'
                .document_web_path().'./lib/javascript/toolbar.js"></script>'
                . "\n"
                ;
            $toolbar .= "<script type=\"text/javascript\">if (document.getElementById) {
		var tb = new dcToolBar(document.getElementById('".$this->attributeList['id']."'),
		'wiki','".document_web_path()."/toolbar/');

		tb.btStrong('Forte emphase');
		tb.btEm('Emphase');
		tb.btIns('Ins�r�');
		tb.btDel('Supprim�');
		tb.btQ('Citation en ligne');
		tb.btCode('Code');
		tb.addSpace(10);
		tb.btBr('Saut de ligne');
		tb.addSpace(10);
		tb.btBquote('Bloc de citation');
		tb.btPre('Texte pr�format�');
		tb.btList('Liste non ordonn�e','ul');
		tb.btList('Liste ordonn�e','ol');
		tb.addSpace(10);
		tb.btLink('Lien',
			'URL ?',
			'Langue ?',
			'fr');
		tb.btImgLink('Image externe',
			'URL ?');
		tb.draw('');
	}
	</script>\n";
            
            return $toolbar;
        }
        
        /**
         * paint (ie echo) area
         */
        function paint()
        {
            echo $this->toHTML();
        }
        
        /**
         * get area html code for string inclusion
         * @return string area html code
         */
        function toHTML()
        {
            $wikiarea = '';

            $attr = '';

            foreach( $this->attributeList as $attribute => $value )
            {
                $attr .= ' ' . $attribute . '="' . $value . '"';
            }

            $wikiarea .= '<textarea'.$attr.'>'.$this->getContent().'</textarea>' . "\n";

            $wikiarea .= $this->getToolbar();

            return $wikiarea;
        }
    }
?>