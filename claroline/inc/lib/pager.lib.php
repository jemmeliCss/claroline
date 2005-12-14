<?php
/**
 * Pager class allowing to manage the paging system into claroline
 *
 *  example 1 : $myPager = new claro_sql_pager('SELECT * FROM USER', $offset, $step);
 *
 *
 *            echo $myPager->disp_pager_tool_bar($_SERVER['PHP_SELF']);
 *
 *            $resultList = $myPager->get_result_list();
 *
 *            echo '<table>';
 *
 *            foreach($resultList as $thisresult)
 *            {
 *              echo '<tr><td>$thisresult[...]</td></tr>';
 *            }
 *
 *            echo '</table>';
 *
 *  example 2 : 
 *
 *            $myPager = new claro_sql_pager('SELECT * FROM USER', $offset, $step);
 *
 *            $myPager->set_sort('column_1', SORT_DESC);
 *
 *            echo $myPager->disp_pager_tool_bar($_SERVER['PHP_SELF']); 
 *
 *            echo '<table>';
 *
 *            $sortUrlList = $myPager->get_sort_url_list($_SERVER['PHP_SELF']);
 *
 *            echo '<tr>';
 *
 *            foreach ($sortUrlList as $thisColName => $thisColUrl)
 *            {
 *              echo '<th>< a href="'.thisColUrl.'">' . $thisColName . '</th>';
 *            }
 *
 *            echo '</tr>';
 *
 *            $resultList = $myPager->get_result_list();
 *
 *            foreach($resultList as $thisresult)
 *            {
 *              echo '<tr><td>$thisresult[...]</td></tr>';
 *            }
 *
 *            echo '</table>';
 *
 *
 *
 * Note : The pager will request page change by the $_GET['offset'] variable
 * If it conflicts with other variable you can change this name with the 
 * set_pager_call_param_name($paramName) method.
 *
 * @author Hugues Peeters <hugues.peeters@claroline.net>
 * 
 */

class claro_sql_pager
{
    var $sortKeyList = array(),
        $totalResultCount = null ,  $offsetCount = null  ,
        $resultList       = null;

    /**
     * Constructor
     *
     * @param string $sql current SQL query
     * @param int $offset requested offset
     * @param int $step current step paging
     */

    function claro_sql_pager($sql, $offset = 0, $step = 20)
    {
        $this->sql       = $sql;
        $this->offset    = (int) $offset;
        $this->step      = (int) 2;
        $this->set_pager_call_param_name('offset');
        $this->set_sort_key_call_param_name('sort');
        $this->set_sort_dir_call_param_name('dir');
    }

    /**
     * Allows to change the parameter name in the url for page change request.
     * By default, this parameter name is 'offset'.
     * @param string paramName
     */

    function set_pager_call_param_name($paramName)
    {
    	$this->pagerParamName = $paramName;
    }

    /**
     * Allows to change the parameter name in the url for sort key request.
     * By default, this parameter name is 'sort'.
     * @param string paramName
     */

    function set_sort_key_call_param_name($paramName)
    {
        $this->sortKeyParamName = $paramName;
    }

    /**
     * Allows to change the parameter name in the url for sort direction 
     * request. By default, this parameter name is 'dir'.
     * @param string paramName
     */

    function set_sort_dir_call_param_name($paramName)
    {
    	$this->sortDirParamName = $paramName;
    }   

    /**
     * Set a specificic sorting for the result returned by the query.
     * 
     * @param string $key - has to be something understable by the SQL parser.
     * @param string $direction use PHP constants SORT_ASC and SORT_DESC
     */

    function set_sort($key, $direction)
    {
        $this->set_multiple_sort( array($key => $direction) );
    }

    /**
     * Set multiple sorting for the result returned by the query.
     * 
     * @param array $keyList - each array key are the sort keys 
     *        it has to be something understable by the SQL parser.
     *        while each array values are sort direction of the concerned key
     */


    function set_multiple_sort($keyList)
    {
        $this->sortKeyList = array(); // reset the sort key list
        $this->sortKeyList = $keyList;
    }



    function add_sort_key($key, $direction)
    {
         if ($this->resultList) 
              claro_die('add_sort_key() IMPOSSIBLE : QUERY ALREADY COMMITED TO DATABASE SERVER.');

        if ( ! array_key_exists($key, $this->sortKeyList) )
        {
            $this->sortKeyList[$key] = $direction;
            return true;
        }

        return false;
    }
    
    /**
     * (Private method) Rewrite the SQL query to allowing paging. It adds LIMIT 
     * parameter to the end of the query end SQL_CALC_FOUND_ROWS between the 
     * SELECT statement and the column list 
     *
     * @param  string $sql current SQL query
     * @param  int $offset requested offset
     * @param int $step current step paging
     * @return string the rewrote query
     */

    function get_prepared_query($sql, $offset, $step, $sortKeyList)
    {
        if ( count($sortKeyList) > 0 )
        {
            $orderByList = array();
            foreach( $sortKeyList as $thisSortKey => $thisSortDirection)
            {
                if     ( $thisSortDirection == SORT_DESC) $direction = 'DESC';
                elseif ( $thisSortDirection == SORT_ASC ) $direction = 'ASC';
                else                                      $direction = '';

                $orderByList[] = $thisSortKey . ' ' . $direction ;
            }

            $sql .= "\n\t" . 'ORDER BY '. implode(', ', $orderByList) ;
        }

        if ( $step > 0 )
        {
            // Include SQL_CALC_FOUND_ROWS inside the query
            // This mySQL clause permit to know how many rows the statement 
            // would have returned with no LIMIT clause, without running the 
            // statement again. To retrieve this rows count, one invokes
            // FOUND_ROWS() afterward (see get_total_result_count method).

            $sql = substr_replace ($sql, 'SELECT SQL_CALC_FOUND_ROWS ', 
                                  0   , strlen('SELECT '))
                   . "\n\t" . ' LIMIT ' . $offset . ', ' . $step;
        }

        return $sql;
    }

    function get_total_result_count()
    {
       if ( ! $this->totalResultCount )
       {
            $this->get_result_list(); // required to be executed before SELECT FOUND_ROWS
       }
       
       return $this->totalResultCount;
    }

    function get_offset_count()
    {
        if ( ! $this->offsetCount )
        {
            $this->offsetCount = ceil( $this->get_total_result_count() / $this->step );
        }

        return $this->offsetCount;
    }

    /**
     * return the result of the SQL query exectued into the constructor
     *
     * @return string
     */

    function get_result_list()
    {
        if ( ! $this->resultList )
        {
            $preparedQuery = $this->get_prepared_query($this->sql,
                                                     $this->offset, $this->step, 
                                                     $this->sortKeyList);

            $this->resultList        = claro_sql_query_fetch_all( $preparedQuery );

            // The query below has to be executed just after the previous one. 
            // Otherwise other potential queries could impair the reliability 
            // of mySQL FOUND_ROWS() function.

            $this->totalResultCount  = claro_sql_query_get_single_value('SELECT FOUND_ROWS()');
        }

        return $this->resultList;
    }

    /**
     * return the offset needed to get the previous page
     *
     * @return int
     */

    function get_previous_offset()
    {
        $previousOffset = $this->offset - $this->step;

        if ($previousOffset >= 0) return $previousOffset;
        else                      return false;
    }

    /**
     * return the offset needed to get the next page
     *
     * @return int
     */

    function get_next_offset()
    {
        $nextOffset = $this->offset + $this->step;

        if ($nextOffset < $this->get_total_result_count() ) return $nextOffset;
        else                                                return false;
    }

    /**
     * return the offset needed to get the first page
     *
     * @return int
     */

    function get_first_offset()
    {
        return 0;
    }

    /**
     * return the offset needed to get the last page
     *
     * @return int
     */

    function get_last_offset()
    {
        return (int)($this->get_offset_count() - 1) * $this->step;
    }

    /**
     * return the offset list needed for each page
     *
     * @return array of int
     */

    function get_offset_list()
    {

        $offsetList = array();
        
        for ($i = 0, $currentOffset = 0, $offsetCount = $this->get_offset_count(); 
             $i < $offsetCount;
             $i ++)
        {
            $offsetList [] = $currentOffset;
            $currentOffset = $currentOffset + $this->step;
        }

        return $offsetList;
    }


    /**
     * returns prepared url able to require sorting for each column 
     * of the pager results
     *
     * @param  string $url 
     * @return array 
     */

    function get_sort_url_list($url)
    {
        $urlList        = array();
        $sortArgList    = array();

        if ( count($this->get_result_list() ) )
        {
            list($firstResultRow) = $this->get_result_list();
            $sortArgList          = array_keys($firstResultRow);
        }
        else
        {
            $sortArgList = claro_sql_field_names($this->sql);
        }

        foreach($sortArgList as $thisArg)
        {
            if (   array_key_exists($thisArg, $this->sortKeyList) 
                && $this->sortKeyList[$thisArg] != SORT_DESC)
            {
                $direction = SORT_DESC;
            }
            else
            {
                $direction = SORT_ASC;
            }

            $urlList[$thisArg] = $url 
                       . ( ( strstr($url, '?') !== false ) ? '&amp;' : '?' )
                       . $this->sortKeyParamName . '=' . urlencode($thisArg)
                       . '&amp;' . $this->sortDirParamName . '=' . $direction;
        }

        return $urlList;
    }

    /**
     * Display a standart pager tool bar
     *
     * @author Hugues Peeters <hugues.peeters@claroline.net>
     * @param  string $url - where the pager tool bar commands need to point to
     * @param  int $linkMax - (optionnal) maximum of page links in the pager tool bar
     * @return void
     */

    function disp_pager_tool_bar($url, $linkMax = 10)
    {

        if ( count($this->sortKeyList) > 0 )
        {
            // Add optionnal sorting calls. 
            // IT KEEPS ONLY THE FIRST SORT KEY !

            reset($this->sortKeyList);
            list($sortKey, $sortDir) = each($this->sortKeyList);

            $url .= ( ( strrpos($url, '?') === false) ? '?' : '&amp;') 
                 .  $this->sortKeyParamName.'=' . urlencode($sortKey)
                 .  '&amp;'.$this->sortDirParamName.'=' . $sortDir;
        }

        if ( strrpos($url, '?') === false) $url .= '?'    .$this->pagerParamName.'=';
        else                               $url .= '&amp;'.$this->pagerParamName.'=';


        $startPage    = $this->get_first_offset();
        $previousPage = $this->get_previous_offset();
        $pageList     = $this->get_offset_list();
        $nextPage     = $this->get_next_offset();
        $endPage      = $this->get_last_offset();

        $output =                                                                                        "\n\n"
                . '<table class="claroPager" border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n"
                . '<tr valign="top">'                                                                  . "\n"
                . '<td align="left" width="20%">'                                                      . "\n"
                ;

        if ($previousPage !== false)
        {
            $output .= '<b>'
                    . '<a href="' . $url . $startPage    . '">|&lt;&lt;</a>&nbsp;&nbsp;'
                    . '<a href="' . $url . $previousPage . '">&lt; </a>'
                    . '</b>'
                    ;
        }
        else
        {
            $output .= '&nbsp;';
        }

        $output .=                                     "\n"
                .  '</td>'                           . "\n"
                .  '<td align="center" width="60%">' . "\n"
                ;

        // current page
        $currentPage = (int) $this->offset / $this->step ;

        // total page
        $pageCount = $this->get_offset_count();

        // start page    
        if ( $currentPage > $linkMax ) $firstLink = $currentPage - $linkMax;
        else                           $firstLink = 0;

        // end page
        if ( $currentPage + $linkMax < $pageCount ) $lastLink = $currentPage + $linkMax;
        else                                        $lastLink = $pageCount;

        // display 1 ... {start_page}
        
        if ( $firstLink > 0 )
        {
            $output .= '<a href="' . $url . $pageList[0] . '">' . (0+1) . '</a>&nbsp;';
            if ( $firstLink > 1 ) $output .= '...&nbsp;';
        } 

        if ( $pageCount > 1) 
        {
            // display page
            for ($link = $firstLink; $link < $lastLink ; $link++)
            {
                if ( $currentPage == $link )
                {
                    $output .= '<b>' . ($link + 1) . '</b> '; // current page
                }
                else
                {
                    $output .= '<a href="' . $url . $pageList[$link] . '">' . ($link + 1) . '</a> ';
                }
            }
        }

        // display 1 ... {start_page}
        if ( $lastLink < $pageCount )
        {
            if ( $lastLink + 1 < $pageCount ) $output .= '...';

            $output .= '&nbsp;<a href="'. $url . $pageList[$pageCount-1] . '">'.($pageCount).'</a>';
        } 

        $output .=                                   "\n"
                .  '</td>'.                          "\n"
                .  '<td align="right" width="20%">'. "\n"
                ;

        if ($nextPage !== false)
        {
            $output .= '<b>'
                    .  '<a href="' . $url . $nextPage . '"> &gt;</a>&nbsp;&nbsp;'
                    .  '<a href="' . $url . $endPage  . '"> &gt;&gt;|</a>'
                    .  '</b>'
                    ;
        }
        else
        {
            $output .= '&nbsp;';
        }

        $output .=             "\n"
                .  '</td>'    ."\n"
                .  '</tr>'    ."\n"
                .  '</table>' ."\n\n"
                ;

        return $output;
    }
}

?>