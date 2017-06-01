<?php
/**
 * User: Eduardo Kraus
 * Date: 27/05/17
 * Time: 11:05
 */

namespace local_kopere_dashboard\chartsjs;


class Lines extends Base
{
    public static function createMultiLines ( array $lines, $xAxisLabel, $yAxisLabel )
    {
        self::start ();

        $datum = $labels = array();
        foreach ( $lines as $line ) {
            $values = $line[ 'values' ];
            $labels = $valuesItens = array();
            foreach ( $values as $value ) {
                $valuesItens[] = '{ x: new Date("' . $value[ 'x' ] . ' GMT-0300"), y: ' . $value[ 'y' ] . ' }';
                $labels[]      = 'new Date("' . $value[ 'x' ] . ' GMT-0300")';
            }

            $cor = self::getColor ();
            $datum[]
                 = "{
                        label           : '{$line['key']}',
                        backgroundColor : " . preg_replace ( "/rgb\((\d+, \d+, \d+)\)/", "rgba($1, 0.5)", $cor ) . ",
                        borderColor     : " . $cor . ",
                        data            : [ " . implode ( ',', $valuesItens ) . " ],
                        fill            : false
                    }";
        }

        ?>
        <div id="canvas-holder" style="width:40%">
            <canvas id="chart-area"/>
        </div>
        <script>
            var data = {
                datasets : [ <?php echo implode ( ', ', $datum ) ?> ],
                labels   : [ <?php echo implode ( ', ', $labels ) ?> ]
            };
            var ctx  = document.getElementById ( "chart-area" ).getContext ( "2d" );
            new Chart ( ctx, {
                type    : 'line',
                data    : data,
                options : {
                    responsive : true,
                    tooltips   : {
                        mode      : 'index',
                        intersect : false,
                        callbacks : {
                            title : function ( tooltipItem, data ) {
                                console.log ( [ tooltipItem, data ] );
                                var date = new Date ( data.labels[ tooltipItem[ 0 ].index ] );
                                return doisDigitos ( date.getDate () ) + " de " + getMes ( date.getMonth () ) + " de " + date.getFullYear ();
                            }
                        }
                    },
                    hover      : {
                        mode      : 'nearest',
                        intersect : true
                    },
                    scales     : {
                        xAxes : [ {
                            display    : true,
                            scaleLabel : {
                                display     : true,
                                labelString : '<?php echo $xAxisLabel ?>'
                            },
                            ticks      : {
                                minRotation : 0,
                                maxRotation : 0,
                                callback    : function ( tick ) {
                                    console.log ( tick );
                                    var date = new Date ( tick );
                                    return doisDigitos ( date.getDate () ) + '/' + doisDigitos ( date.getMonth () + 1 );
                                }
                            }
                        } ],
                        yAxes : [ {
                            display    : true,
                            scaleLabel : {
                                display     : true,
                                labelString : '<?php echo $yAxisLabel ?>'
                            }
                        } ]
                    }
                }
            } );
            function doisDigitos ( num ) {
                if ( num < 10 )
                    return '0' + num;
                return num;
            }
            function getMes ( num ) {
                var months = 'Janeiro Fevereiro Março Abril Maio Junho Julho Agosto Setembro Outubro Novembro Dezembro'.split ( ' ' );
                return months[ num ];
            }
            function getShortMes ( num ) {
                var months = 'Jan Fev Mar Abr Mai Jun Jul Ago Set Out Nov Dez'.split ( ' ' );
                return months[ num ];
            }

        </script>
        <?php
    }
}