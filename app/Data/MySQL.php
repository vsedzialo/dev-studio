<?php
namespace DevStudio\Data;

use DevStudio\Core\Cache;
use DevStudio\Helpers\Utils;

/**
 * MySQL data class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class MySQL {

    /**
     * Tables data
     *
     * @since 1.0.0
     * @return mixed
     */
    public static function tables() {
        global $wpdb;

        if (!empty($wpdb) && is_object($wpdb) && defined('DB_NAME')) {
            $sql = $wpdb->prepare('
                SELECT *, round(((data_length + index_length) / 1024 / 1024), 2) as SIZE 
                FROM information_schema.TABLES 
                WHERE table_schema = %s
                ORDER BY table_name',
                [ DB_NAME ]
            );
            return $wpdb->get_results($sql, ARRAY_A);
        }

    }

    /**
     * Variables data
     *
     * @since 1.0.0
     * @return mixed
     */
    public static function variables() {
        global $wpdb;

        $data = [];
        if (!empty($wpdb) && is_object($wpdb) && defined('DB_NAME')) {
            $result = $wpdb->get_results('SHOW VARIABLES', ARRAY_A);
            foreach($result as $item) $data[$item['Variable_name']] = $item['Value'];
        }
        return $data;
    }

    /**
     * Queries data
     *
     * @since 1.0.0
     * @return array|mixed
     */
    public static function queries() {
        global $wpdb;

        // Load data from storage
        $data = Cache::load([
            'type' => 'mysql_queries'
        ]);

        if (empty($data) && !empty($wpdb) && is_object($wpdb) && !empty($wpdb->queries)) {
            $time = 0;
            $callers = [];

            foreach ($wpdb->queries as $key=>$qdata) {

                // Prepare backtrace
                $qdata[2] = preg_replace("#(require|require_once|include|include_once)\('#", '', $qdata[2]);
                $qdata[2] = preg_replace("#'\), #", ', ', $qdata[2]);

                $query = trim($qdata[0]);
                $backtrace = explode(', ', $qdata[2]);
                $caller = end($backtrace);

                // Exclude me
                if (DevStudio()->exclude_me() && DevStudio()->me('func', $caller)) continue;

                $slow_query = $qdata[1] > DevStudio()->options()['modules']['mysql']['slow_query'];

                // Query data
                $_data =  [
                    'query' => Utils::prepare_sql($query),
                    'time' => sprintf('%3.6f', $qdata[1]),
                    'slow_query' => $slow_query,
                    'caller' => $caller,
                    'backtrace' => $backtrace
                ];

                // Global time
                $time += $qdata[1];

                // Callers
                if (!isset($callers[$caller])) $callers[$caller] = [
                    'queries' => 0,
                    'time' => 0,
                    'types' => []
                ];
                $callers[$caller]['queries']++;
                $callers[$caller]['time'] += $qdata[1];

                if (preg_match('#^(SELECT|INSERT|UPDATE|DELETE|SET|SHOW) #i', $query, $matches)) {
                    $type = $matches[1];
                } else {
                    $type = '{Other}';
                }
                if (!isset($callers[$caller]['types'][$type])) {
                    $callers[$caller]['types'][$type] = [
                        'queries' => 0,
                        'time' => 0
                    ];
                }
                $callers[$caller]['types'][$type]['queries']++;
                $callers[$caller]['types'][$type]['time'] += $qdata[1];
                if (empty($data['types']) || !in_array($type, $data['types'])) $data['types'][] = $type;

                $_data['qtype'] = $type;
                $data['queries'][$key] = $_data;

                // Slow queries
                if ($slow_query) {
                    $data['slow_queries'][] = [
                        'key' => $key,
                        'query' => $_data
                    ];
                }
            }
            $data['time'] = sprintf('%3.6f', $time);
            $data['callers'] = $callers;
            $data['slow_queries'] = isset($data['slow_queries']) ? $data['slow_queries']:[];

            // Save data to storage
            Cache::save([
                'type' => 'mysql_queries',
                'data' => $data
            ]);
        }

        return $data;
    }
}