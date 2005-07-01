<?php
    // $Id$
     
    // vim: expandtab sw=4 ts=4 sts=4:
     
    if( (bool) stristr( $_SERVER['PHP_SELF'], basename(__FILE__) ) )
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
     * @package Wiki
     */
     
    require_once dirname(__FILE__) . '/wiki2xhtml/class.wiki2xhtml.php';
    require_once dirname(__FILE__) . '/class.wikistore.php';
    require_once dirname(__FILE__) . '/class.wikipage.php';
     
    define ("WIKI_WORD_PATTERN", '((?<![A-Za-z0-9��-��-��-�])([A-Z�-��-�][a-z��-��-�]+){2,}(?![A-Za-z0-9��-��-��-�]))' );
     
    /**
    * Wiki2xhtml rendering engine
    *
    * @see wiki2xhtml
    */
    class Wiki2xhtmlRenderer extends wiki2xhtml
    {
        var /*% Wiki*/ $wiki;
         
        /**
         * Constructor
         * @param Wiki wiki
         */
        function Wiki2xhtmlRenderer( &$wiki )
        {
            wiki2xhtml::wiki2xhtml();
             
            $this->wiki =& $wiki;
             
            // set wiki rendering options
            // use wikiwords to link wikipages
            $this->setOpt( 'active_wikiwords', 1 );
            // auto detect images
            $this->setOpt( 'active_auto_img', 1 );
            // set first wiki title level
            $this->setOpt( 'first_title_level', 2 );
            // use setext title syntax ie ===== and ----- instead of !!! and !!
            $this->setOpt( 'active_setext_title', 1 );
            // set acronyms file
            $this->setOpt( 'acronyms_file', dirname(__FILE__) . '/wiki2xhtml/acronyms.txt' );
            // set wiki word pattern
            $this->setOpt( 'words_pattern', WIKI_WORD_PATTERN );
            // set footnotes patten
            $this->setOpt( 'note_str', '<div class="footnotes"><a name="footNotes"></a><h2>Notes</h2>%s</div>' );
            // use urls to link wikipages
            $this->setOpt( 'active_wiki_urls', 1 );
        }
         
        /**
         * Parse WikiWords and create hypertext reference to wiki page
         *
         * @access private
         * @see class.wiki2xhtml.php
         * @return string hypertext reference to wiki page
         */
        function parseWikiWord( $str, $tag, $attr, $type )
        {
            $tag = 'a';
            $attr = ' href="'.$str.'"';
            if ( $this->wiki->pageExists( $str ) )
                {
                return "<a href=\"".$_SERVER['PHP_SELF']
                    ."?action=show&amp;title=".urlencode($str )
                    . "&amp;wikiId=" . $this->wiki->getWikiId()
                    . "\" class=\"wikiShow\">"
                    . $str
                    . "</a>"
                    ;
            }
            else
            {
                return "<a href=\"".$_SERVER['PHP_SELF']
                    . "?action=edit&amp;title=" . urlencode($str )
                    . "&amp;wikiId=" . $this->wiki->getWikiId()
                    . "\" class=\"wikiEdit\">"
                    . $str
                    . "</a>"
                    ;
            }
        }
         
        /**
         * Parse links in pages
         *
         * @see class.wiki2xhtml.php#__parseLink($str, &$tag, &$attr, &$type)
         */
        function __parseLink($str, &$tag, &$attr, &$type )
        {
            $n_str = $this->__inlineWalk($str, array('acronym', 'img' ) );
            $data = $this->__splitTagsAttr($n_str );
            $no_image = false;
             
            if (count($data ) == 1)
            {
                $url = trim($str );
                $content = $str;
                $lang = '';
                $title = '';
            }
            elseif (count($data ) > 1 )
            {
                $url = trim($data[1] );
                $content = $data[0];
                $lang = (!empty($data[2] ) )
                ? $this->protectAttr($data[2], true )
                :
                '' ;
                $title = (!empty($data[3] ) )
                ? $data[3] :
                '' ;
                $no_image = (!empty($data[4] ) )
                ? (boolean) $data[4] :
                false ;
            }
             
            $array_url = $this->__specialUrls();
            $url = preg_replace(array_flip($array_url ), $array_url, $url );
             
            # On vire les &nbsp; dans l'url
            $url = str_replace('&nbsp;', ' ', $url);
             
            if ( ereg('^(.+)[.](gif|jpg|jpeg|png)$', $url )
                && !$no_image && $this->getOpt('active_auto_img' ) )
            {
                # On ajoute les dimensions de l'image si locale
                # Id�e de Stephanie
                $img_size = NULL;
                if (!ereg('[a-zA-Z]+://', $url ) )
                {
                    if (ereg('^/', $url ) )
                    {
                        $path_img = $_SERVER['DOCUMENT_ROOT'] . $url;
                    }
                    else
                    {
                        $path_img = $url;
                    }
                     
                    $img_size = @getimagesize($path_img );
                }
                 
                $attr = ' src="'.$this->protectAttr($this->protectUrls($url ) ).'"' . $attr .= (count($data) > 1 )
                ? ' alt="'.$this->protectAttr($content ).'"' :
                ' alt=""' ;
                $attr .= ($lang )
                ? ' lang="'.$lang.'"' :
                '' ;
                $attr .= ($title )
                ? ' title="'.$this->protectAttr($title).'"' :
                '' ;
                $attr .= (is_array($img_size ) ) ? ' '.$img_size[3] :
                '';
                 
                $tag = 'img';
                $type = 'close';
                return NULL;
            }
            else
            {
                if ($this->getOpt('active_antispam' ) && preg_match('/^mailto:/', $url ) )
                {
                    $url = 'mailto:'.$this->__antiSpam(substr($url, 7));
                }
                 
                if (!ereg('[a-zA-Z]+://', $url) && $this->getOpt('active_wiki_urls' ))
                {
                    $attr = $this->_getWikiPageLink($url );
                }
                else
                {
                    $attr = ' href="'.$this->protectAttr($this->protectUrls($url ) ).'"' . ' rel="nofollow"' ;
                }
                
                $attr .= ($lang)
                ? ' hreflang="'.$lang.'"' :
                '' ;
                $attr .= ($title)
                ? ' title="'.$this->protectAttr($title ).'"' :
                '' ;
                 
                return $content;
            }
        }

        /**
         * Render the given string using the wiki2xhtml renderer
         * @param string txt wiki syntax string
         * @return string xhtml-rendered string
         */
        function render( $txt )
        {
            return $this->transform($txt );
        }

        /**
         * Parse page names in URLS and create hypertext reference to wiki page
         *
         * @access private
         * @param string pageName name of the page
         * @return string hypertext reference to wiki page
         */
        function _getWikiPageLink( $pageName )
        {
            // allow links to use wikiwords for wiki page locations
            if ($this->getOpt('active_wikiwords') && $this->getOpt('words_pattern'))
            {
                $pageName = preg_replace('/���'.$this->getOpt('words_pattern').'���/msU', '$1', $pageName);
            }
             
            if ($this->wiki->pageExists( $pageName ) )
            {
                return ' href="' . $_SERVER['PHP_SELF']
                    . '?action=show&amp;title=' . urlencode($pageName )
                    . '&amp;wikiId=' . $this->wiki->getWikiId()
                    . '" class="wikiShow"'
                    ;
            }
            else
            {
                return ' href="' . $_SERVER['PHP_SELF']
                    . '?action=edit&amp;title=' . urlencode($pageName )
                    . '&amp;wikiId=' . $this->wiki->getWikiId()
                    . '" class="wikiEdit"'
                    ;
            }
        }
    }
?>
