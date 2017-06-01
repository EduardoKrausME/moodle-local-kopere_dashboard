<?php
/**
 * User: Eduardo Kraus
 * Date: 31/01/17
 * Time: 06:30
 */

namespace local_kopere_dashboard\report;


class ReportBenchmark
{
    private $results = array();

    /**
     * benchmark constructor.
     */
    public function __construct ()
    {
        $tests  = array(
            'cload',
            'processor',
            'fileread',
            'filewrite',
            'courseread',
            'coursewrite',
            'querytype1',
            'querytype2',
            'loginguest',
            'loginuser'
        );
        $benchs = array();
        $idtest = 0;

        foreach ( $tests as $name ) {

            ++$idtest;

            // Inistialize and execute the test
            $start  = microtime ( true );
            $result = $this->start_test ( $name );

            // Populate if empty result
            empty( $result[ 'limit' ] ) ? $result[ 'limit' ] = 0 : null;
            empty( $result[ 'over' ] ) ? $result[ 'over' ] = 0 : null;

            // Overwrite the result if start/stop if defined
            $over_start = isset( $result[ 'start' ] ) ? $result[ 'start' ] : $start;
            $over_stop  = isset( $result[ 'stop' ] ) ? $result[ 'stop' ] : microtime ( true );
            $stop       = round ( $over_stop - $over_start, 3 );

            // Store and merge result
            $benchs[ $name ] = array(
                    'during' => $stop,
                    'id'     => $idtest,
                    'class'  => $this->get_feedback_class ( $stop, $result[ 'limit' ], $result[ 'over' ] ),
                    'name'   => get_string ( $name . 'name', 'local_kopere_dashboard' ),
                    'info'   => get_string ( $name . 'moreinfo', 'local_kopere_dashboard' ),
                ) + $result;
        }

        // Store all results
        $this->results = $benchs;
    }

    /**
     * Start a benchmark test
     *
     * @param string $name Test name
     *
     * @return array Test result
     */
    private function start_test ( $name )
    {
        return ReportBenchmark_test::$name();
    }

    /**
     * Get the class with the timer result
     *
     * @param int $during
     * @param int $limit
     * @param int $over
     *
     * @return string Get the class
     */
    private function get_feedback_class ( $during, $limit, $over )
    {
        if ( $during >= $over ) {
            $class = 'bg-danger';
        } elseif ( $during >= $limit ) {
            $class = 'bg-warning';
        } else {
            $class = 'bg-success';
        }

        return $class;
    }

    /**
     * @return array Get the result of all tests
     */
    public function get_results ()
    {
        return $this->results;
    }

    /**
     * @return array Get the total time and score of all tests
     */
    public function get_total ()
    {
        $total = 0;

        foreach ( $this->results as $result ) {
            $total += $result[ 'during' ];
        }

        return array(
            'total' => $total,
            'score' => ceil ( $total * 100 ),
        );
    }
}