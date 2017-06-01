<?php
/**
 * User: Eduardo Kraus
 * Date: 18/05/17
 * Time: 04:58
 */

namespace local_kopere_dashboard\util;


class DatatableSearchUtil
{
    private $columnOrder;
    private $columnsSelect;
    private $start;
    private $length;
    private $order;
    private $orderDir;
    private $where;


    public function __construct ( $columnSelect, $columnOrder )
    {
        $this->columnOrder   = $columnOrder;
        $this->columnsSelect = $columnSelect;
        $this->start         = optional_param ( 'start', 0, PARAM_INT );
        $this->length        = optional_param ( 'length', 0, PARAM_INT );

        $this->processWhere ();
        $this->proccessOrder ();
    }

    public function processWhere ()
    {
        if ( isset( $_POST[ 'search' ][ 'value' ] ) && isset( $_POST[ 'search' ][ 'value' ][ 0 ] ) ) {
            $like = array();
            foreach ( $this->columnOrder as $column ) {
                $find = $_POST[ 'search' ][ 'value' ];
                $find = str_replace ( "'", "\'", $find );
                $find = str_replace ( "--", "", $find );
                if ( is_array ( $column ) )
                    $like [] = $column[ 1 ] . " LIKE '%{$find}%'";
                else
                    $like [] = $column . " LIKE '%{$find}%'";
            }
            $this->where = 'AND (' . implode ( ' OR ', $like ) . ')';
        }
    }

    private function proccessOrder ()
    {
        if ( isset( $_POST[ 'order' ] ) && isset( $_POST[ 'columns' ] ) ) {
            $_column = $_POST[ 'order' ][ 0 ][ 'column' ];
            if ( is_array ( $this->columnOrder[ $_column ] ) )
                $this->order = $this->columnOrder[ $_column ][ 0 ];
            else
                $this->order = $this->columnOrder[ $_column ];
            $this->orderDir = $_POST[ 'order' ][ 0 ][ 'dir' ];
        }
    }

    public function executeSqlAndReturn ( $sql, $group='', $params=array() )
    {
        global $DB;

        $sqlSearch = $sql . " $this->where $group";
        $sqlSearch = str_replace ( '{[columns]}', 'count(*) as num', $sqlSearch );

        $sqlTotal = $sql . " $group";
        $sqlTotal = str_replace ( '{[columns]}', 'count(*) as num', $sqlTotal );

        $sqlReturn = $sql . " $this->where ORDER BY $this->order $this->orderDir $group LIMIT $this->start, $this->length";
        $sqlReturn = str_replace ( '{[columns]}', implode ( ', ', $this->columnsSelect ), $sqlReturn );


        // mail ( 'kraus@eduardokraus.com', 'aaaa', print_r ( [ $sqlSearch, $sqlTotal, $sqlReturn ], 1 ) );

        $result   = $DB->get_records_sql ( $sqlReturn, $params );
        $total    = $DB->get_record_sql ( $sqlTotal, $params );
        $totalNum = $total->num;
        if ( $this->where ) {
            $search    = $DB->get_record_sql ( $sqlSearch, $params );
            $searchNum = $search->num;
        } else
            $searchNum = $totalNum;

        Json::encodeAndReturn ( $result, $totalNum, $searchNum );
    }
}