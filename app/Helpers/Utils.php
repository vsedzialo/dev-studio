<?php
namespace DevStudio\Helpers;

/**
 * Utils helper class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Utils {

	protected static $file_components = [];
	protected static $file_dirs       = [];
	protected static $abspath         = null;
	protected static $contentpath     = null;
	protected static $sort_field      = null;

    /**
     * Check if user admin
     *
     * @since 1.0.0
     * @return bool
     */
    static function is_admin() {
        if ( isset( $GLOBALS['current_screen'] ) && class_exists('WP_Screen') )
            return $GLOBALS['current_screen']->in_admin();
        elseif ( defined( 'WP_ADMIN' ) )
            return WP_ADMIN;

        return false;
    }

    /**
     * Prepare backtrace data
     *
     * @since 1.0.0
     */
    public static function backtrace($bt = null) {
        if (!$bt) $bt = debug_backtrace();
        $data = [];

        foreach($bt as $key=>$val) {
            if ( $key > 3 ) {
                $index = ! empty( $val['file'] ) ? $val['file'] . ', line ' . $val['line'] : $val['function'];

                $data[ $index ] = [];

                if ( ! empty( $val['function'] ) ) {
                    $data[ $index ]['function'] = $val['function'];
                }
                if ( ! empty( $val['class'] ) ) {
                    $data[ $index ]['class'] = $val['class'];
                }
                if ( ! empty( $val['args'] ) ) {
                    $data[ $index ]['args'] = $val['args'];
                }
            }
        }

        return $data;
    }

    /**
     * Prepare SQL query before output
     *
     * @since 1.0.0
     * @param $sql
     * @return null|string|string[]
     */
    public static function prepare_sql($sql) {
        $sql = preg_replace('# (SELECT|FROM|WHERE|INNER JOIN|LEFT JOIN|RIGHT JOIN|HAVING|GROUP BY|ORDER BY|LIMIT|AND|OR|SHOW VARIABLES) #i', '<br/><strong>$1</strong> ', ' '.$sql);
        $sql = preg_replace('#^<br/>#', '', $sql);
        return $sql;
    }

	static function is_ajax() {
		return defined( 'DOING_AJAX' );
	}

    /**
     * Get units map for JS
     *
     * @since 1.0.0
     * @return array
     */
	static function map() {
		$data = [];

		foreach(DevStudio()->modules() as $module_name=>$module) {
			foreach ( $module->components() as $component_name => $component ) {
				foreach ( $component->units() as $unit_name => $unit ) {

                    if (empty($data[ $module_name ])) {
                        $data[ $module_name ] = [
                            'title'      => $module->title,
                            'components' => []
                        ];
                    }
                    if (empty($data[ $module_name ]['components'][ $component_name ])) {
                        $data[ $module_name ]['components'][ $component_name ] = [
                            'title' => $component->title,
                            'units' => []
                        ];
                    }
                    $data[ $module_name ]['components'][ $component_name ]['units'][ $unit_name ] = [
                        'title' => !empty($unit->tabTitle) ? $unit->tabTitle:$unit->title
                    ];
				}
			}
		}

		return $data;
	}

	public static function callback_data( array $callback ) {

		if ( is_string( $callback['function'] ) and ( false !== strpos( $callback['function'], '::' ) ) ) {
			$callback['function'] = explode( '::', $callback['function'] );
		}

		try {

			if ( is_array( $callback['function'] ) ) {
				if ( is_object( $callback['function'][0] ) ) {
					$class  = get_class( $callback['function'][0] );
					$access = '->';
				} else {
					$class  = $callback['function'][0];
					$access = '::';
				}
				$callback['name'] = $class . $access . $callback['function'][1] . '()';
				$ref = new \ReflectionMethod( $class, $callback['function'][1] );
			} elseif ( is_object( $callback['function'] ) ) {
				if ( is_a( $callback['function'], 'Closure' ) ) {
					$ref  = new \ReflectionFunction( $callback['function'] );
					$file = self::standard_dir( $ref->getFileName(), '' );
					if ( 0 === strpos( $file, '/' ) ) {
						$file = basename( $ref->getFileName() );
					}
					/* translators: 1: Line number, 2: File name */
					$callback['name'] = sprintf( __( 'Closure on line %1$d of %2$s', 'dev-studio' ), $ref->getStartLine(), $file );
				} else {
					// the object should have a __invoke() method
					$class = get_class( $callback['function'] );
					$callback['name'] = $class . '->__invoke()';
					$ref = new \ReflectionMethod( $class, '__invoke' );
				}
			} else {
				$callback['name'] = $callback['function'] . '()';
				$ref = new \ReflectionFunction( $callback['function'] );
			}

			$callback['file'] = $ref->getFileName();
			$callback['line'] = $ref->getStartLine();

			$name = trim( $ref->getName() );

			if ( '__lambda_func' === $name || 0 === strpos( $name, 'lambda_' ) ) {
				if ( preg_match( '|(?P<file>.*)\((?P<line>[0-9]+)\)|', $callback['file'], $matches ) ) {
					$callback['file'] = $matches['file'];
					$callback['line'] = $matches['line'];
					$file = trim( self::standard_dir( $callback['file'], '' ), '/' );
					/* translators: 1: Line number, 2: File name */
					$callback['name'] = sprintf( __( 'Anonymous function on line %1$d of %2$s', 'dev-studio' ), $callback['line'], $file );
				} else {
					// https://github.com/facebook/hhvm/issues/5807
					unset( $callback['line'], $callback['file'] );
					$callback['name'] = $name . '()';
					$callback['error'] = new \WP_Error( 'unknown_lambda', __( 'Unable to determine source of lambda function', 'dev-studio' ) );
				}
			}

			if ( ! empty( $callback['file'] ) ) {
				$callback['component'] = self::get_file_component( $callback['file'] );
			} else {
				$callback['component'] = [
					'type'    => 'php',
					'name'    => 'PHP',
					'context' => '',
				];
			}
		} catch ( \ReflectionException $e ) {
			$callback['error'] = new \WP_Error( 'reflection_exception', $e->getMessage() );
		}

		return $callback;
	}

	public static function standard_dir( $dir, $path_replace = null ) {

		$dir = self::normalize_path( $dir );

		if ( is_string( $path_replace ) ) {
			if ( ! self::$abspath ) {
				self::$abspath     = self::normalize_path( ABSPATH );
				self::$contentpath = self::normalize_path( dirname( WP_CONTENT_DIR ) . '/' );
			}
			$dir = str_replace( [
				self::$abspath,
				self::$contentpath,
			], $path_replace, $dir );
		}

		return $dir;

	}

    /**
     * Normalize a filesystem path
     *
     * @since 1.0.0
     * @param $path
     * @return string
     */
	public static function normalize_path( $path ) {
		if ( function_exists( 'wp_normalize_path' ) ) {
			$path = wp_normalize_path( $path );
		} else {
			$path = str_replace( ['\\', '//'], '/', $path );
		}
		return $path;
	}

	public static function get_file_component( $file ) {

		$file = self::standard_dir( $file );

		if ( isset( self::$file_components[ $file ] ) ) {
			return self::$file_components[ $file ];
		}

		foreach ( self::get_file_dirs() as $type => $dir ) {
			if ( $dir && ( 0 === strpos( $file, trailingslashit( $dir ) ) ) ) {
				break;
			}
		}

		$context = $type;
		switch ( $type ) {
			case 'plugin':
			case 'mu-plugin':
			case 'mu-vendor':
				$plug = str_replace( '/vendor/', '/', $file );
				$plug = plugin_basename( $plug );
				if ( strpos( $plug, '/' ) ) {
					$plug = explode( '/', $plug );
					$plug = reset( $plug );
				} else {
					$plug = basename( $plug );
				}
				if ( 'plugin' !== $type ) {
					/* translators: %s: Plugin name */
					$name = sprintf( __( 'MU Plugin: %s', 'dev-studio' ), $plug );
				} else {
					/* translators: %s: Plugin name */
					$name = sprintf( __( 'Plugin: %s', 'dev-studio' ), $plug );
				}
				$context = $plug;
				break;
			case 'go-plugin':
			case 'vip-plugin':
			case 'vip-client-mu-plugin':
				$plug = str_replace( self::$file_dirs[ $type ], '', $file );
				$plug = trim( $plug, '/' );
				if ( strpos( $plug, '/' ) ) {
					$plug = explode( '/', $plug );
					$plug = reset( $plug );
				} else {
					$plug = basename( $plug );
				}
				if ( 'vip-client-mu-plugin' === $type ) {
					/* translators: %s: Plugin name */
					$name = sprintf( __( 'VIP Client MU Plugin: %s', 'dev-studio' ), $plug );
				} else {
					/* translators: %s: Plugin name */
					$name = sprintf( __( 'VIP Plugin: %s', 'dev-studio' ), $plug );
				}
				$context = $plug;
				break;
			case 'stylesheet':
				if ( is_child_theme() ) {
					$name = __( 'Child Theme', 'dev-studio' );
				} else {
					$name = __( 'Theme', 'dev-studio' );
				}
				$type = 'theme';
				break;
			case 'template':
				$name = __( 'Parent Theme', 'dev-studio' );
				$type = 'theme';
				break;
			case 'other':
				$name    = self::standard_dir( $file );
				$name    = str_replace( dirname( self::$file_dirs['other'] ), '', $name );
				$parts   = explode( '/', trim( $name, '/' ) );
				$name    = $parts[0] . '/' . $parts[1];
				$context = $file;
				break;
			case 'core':
				$name = __( 'Core', 'dev-studio' );
				break;
			case 'unknown':
			default:
				$name = __( 'Unknown', 'dev-studio' );
				break;
		}

		return self::$file_components[ $file ] = compact( 'type', 'name', 'context' );
	}


	public static function get_file_dirs() {
		if ( empty( self::$file_dirs ) ) {
			self::$file_dirs['plugin']     = self::standard_dir( WP_PLUGIN_DIR );
			self::$file_dirs['mu-vendor']  = self::standard_dir( WPMU_PLUGIN_DIR . '/vendor' );
			self::$file_dirs['go-plugin']  = self::standard_dir( WPMU_PLUGIN_DIR . '/shared-plugins' );
			self::$file_dirs['mu-plugin']  = self::standard_dir( WPMU_PLUGIN_DIR );
			self::$file_dirs['vip-plugin'] = self::standard_dir( get_theme_root() . '/vip/plugins' );

			if ( defined( 'WPCOM_VIP_CLIENT_MU_PLUGIN_DIR' ) ) {
				self::$file_dirs['vip-client-mu-plugin'] = self::standard_dir( WPCOM_VIP_CLIENT_MU_PLUGIN_DIR );
			}

			self::$file_dirs['theme']      = null;
			self::$file_dirs['stylesheet'] = self::standard_dir( get_stylesheet_directory() );
			self::$file_dirs['template']   = self::standard_dir( get_template_directory() );
			self::$file_dirs['other']      = self::standard_dir( WP_CONTENT_DIR );
			self::$file_dirs['core']       = self::standard_dir( ABSPATH );
			self::$file_dirs['unknown']    = null;
		}
		return self::$file_dirs;
	}

    /**
     * Prepare string before output
     *
     * @since 1.0.0
     * @param $col
     * @return string
     */
    public static function prepare_string($col) {
        if (!is_string($col['val'])) return $col['val'];
        
        if (!isset($col['original'])) {
            $col['val'] = htmlspecialchars($col['val']);
        }
        $search = [
            '#\[\[ds\-([^\]]+)\]\]#',
            '#\[\[/ds\-[^\]]+\]\]#'
        ];
        $replace = [
            '<span class="ds-$1">',
            '</span>'
        ];
        return preg_replace($search, $replace, $col['val']);
    }
    
    
    /**
     * Get style data
     *
     * @since 1.0.0
     * @param $obj
     * @return array
     */
    public static function style_data($obj) {
        
        $data = [
            'src' => !preg_match('#^[0-9]*$#', $obj->src) ? $obj->src:'',
            'ver' => $obj->ver,
            'deps' => $obj->deps,
            'media' => $obj->args,
            'extra' => $obj->extra
        ];

        $host = '';
        if (preg_match('#^[\/]{1}#i', $obj->src))
            $host = $_SERVER['HTTP_HOST'];
        else if (preg_match('#^http#', $obj->src)) {
            $parts = parse_url($obj->src);
            $host = $parts['host'];
        }
        $data['host'] = $host;

        if (!empty($obj->extra)) {
            $size = 0;
            foreach((array)$obj->extra as $key=>$val) $size += mb_strlen(print_r($val, true), 'UTF-8');
                //$size += mb_strlen($val, 'UTF-8');
            $data['extra_size'] = $size;
        }
    
        return $data;
    }

    /**
     * Output time
     *
     * @since 1.0.0
     * @param $time
     * @param $icon
     * @return string
     */
    public static function time($time, $decimal = 2, $icon = false) {
        return ($icon ? '<span class="fa fa-clock-o"></span>&nbsp;':'') .
               (function_exists( 'number_format_i18n' ) ? number_format_i18n( $time, $decimal ) : number_format( $time, $decimal )) .
               ''.__('s', 'dev-studio');
    }

    /**
     * Get boolean value
     *
     * @since 1.0.0
     * @param $value
     * @return string
     */
    public static function true_false($value) {
        return $value ? 'true' : 'false';
    }

    /**
     * Get icon depend on boolean valaue
     *
     * @since 1.0.0
     * @param $value
     * @return string
     */
    public static function fa_true_false($value) {
        if ($value === 'true' || $value === 'false') {
            return '<span class="fa fa-check ds-' . $value . '"></span>';
        } else {
            return $value;
        }
    }

    /**
     * Get function conditional value
     *
     * @since 1.0.0
     * @param $func
     * @return mixed
     */
    public static function get_cond_func($func) {
        if (!function_exists($func)) return static::not_exists();
        try {
            return $func() ? 'true' : 'false';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * Get conditionals array for output
     *
     * @since 1.0.0
     * @param $data
     * @return array
     */
    public static function get_cond_data($data) {
        $return = [];
        $true = [];
        $false = [];
        $not_exists = [];

        foreach ($data as $key => $value) {
            if ($value === true || $value === 'true' || $value == 1) $true[$key] = $value;
            if ($value === false || $value === 'false') $false[$key] = $value;
            if (!is_bool($value) && $value === self::not_exists()) $not_exists[$key] = $value;
        }

        foreach ($true as $key => $value) {
            $return[] = [
                'class' => 'mark',
                'cols' => [
                    ['val' => $key],
                    ['val' => 'true']
                ]
            ];
        }
        foreach ($false as $key => $value) {
            $return[] = [
                'class' => 'mask',
                'cols' => [
                    ['val' => $key],
                    ['val' => 'false']
                ]

            ];
        }

        foreach ($not_exists as $key => $value) {
            $return[] = [
                'cols' => [
                    ['val' => $key],
                    ['val' => $value, 'original' => true]
                ]

            ];
        }
        return $return;
    }

    /**
     * Get constant value
     *
     * @since 1.0.0
     * @param $const
     * @return mixed
     */
    public static function get_const_value($const) {
        if (!defined($const)) return self::not_exists();
        if (constant($const) === true) return 'true';
        if (constant($const) === false) return 'false';
        if (strtoupper(constant($const)) === 'NAN') return 'NAN';
        if (strtoupper(constant($const)) === 'INF') return 'INF';
        return constant($const);
    }

    /**
     * Get formatted array for output
     *
     * @since 1.0.0
     * @param $data
     * @return array
     */
    public static function get_simple_data($data) {
        $return = [];

        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                $return[] = [
                    'cols' => [
                        ['val' => $key],
                        ['val' => $value]
                    ]
                ];
            }
        }
        return $return;
    }

    /**
     * Get formatted array for output
     *
     * @since 1.0.0
     * @param $data
     * @return array
     */
    public static function get_simple_array($data) {
        $return = [];

        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                $return[] = [
                    ['val' => $key],
                    ['val' => $value]
                ];
            }
        }
        return $return;
    }

    /**
     * Return "not exists" message
     *
     * @since 1.0.0
     * @return string
     */
    public static function not_exists() {
        return '<span class="ds-not-exists">undefined</span>';
    }

    /**
     * Return "no data" message
     *
     * @since 1.0.0
     * @return string
     */
    public static function no_data() {
        return '<span class="message">'.__('No data', 'dev-studio').'</span>';
    }

    public static function not_available() {
        return '<span class="message">'.__('Not yet available', 'dev-studio').'</span>';
    }


    /**
     * Return custom message
     *
     * @since 1.0.0
     * @param $message
     * @param bool $error
     * @return string
     */
    public static function message($message, $error=false) {
        return '<span class="ds-message '.($error ? 'ds-error':'').'">'.$message.'</span>';
    }

    public static function key($key) {
        $key = str_replace( ['\\'], ['/'], $key );
        $storage_dir = str_replace( ['\\'], ['/'], DevStudio()->dir('storage'));
        $key = str_replace( $storage_dir, '', $key );
        return $key;
    }

    /**
     * Check if exclude WP ajax query
     *
     * @since 1.0.0
     * @return boolean
     */
    public static function exclude_wp_ajax() {
        return isset(DevStudio()->options()['data']['ajax']['exclude_wp_ajax']) &&
            DevStudio()->options()['data']['ajax']['exclude_wp_ajax'] === 'yes' &&
            isset($_REQUEST['action']) &&
            in_array($_REQUEST['action'], ['heartbeat','wp-remove-post-lock']);
    }

    public static function collect() {
        
        // Don't collect data when plugin deactivated
        if (isset($_REQUEST['action']) && $_REQUEST['action']==='deactivate' && preg_match('#/dev\-studio\.php$#', $_REQUEST['plugin'])) return false;
        
        return  DevStudio()->enabled() &&
                !DevStudio()->me() &&
                !Utils::exclude_wp_ajax() &&
                !isset($_REQUEST['doing_wp_cron']) &&
                !preg_match('#\.map$#', $_SERVER['REQUEST_URI']);
    }

}