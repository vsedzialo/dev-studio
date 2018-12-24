<?php
namespace DS_AVAFields\Core;

/**
 * Access class
 *
 * User access methods
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Access
{
    private $access;

    private $user;
    private $post;


    public function __construct($access) {

        $this->access = true;

        // Get current user
        $this->user = wp_get_current_user();

        // Get current post
        $this->post = get_post();


        /*
        'user_capability' => '',
        'user_id' => '',
        'user_role' => '',

        // post_meta
        'post_format' => '',
        'post_id' => '',
        'post_level' => '',
        'post_ancestor_id' => '',
        'post_template' => '',
        'post_term' => '',
        'post_type' => '',

        // term_meta
        'term' => '',
        'term_parent' => '',
        'term_level' => '',
        'term_ancestor' => '',
        'term_taxonomy' => '',

        // theme_options
        'blog_id' => '',
        */

        foreach ($access as $key => $data) {
            if (!empty($data)) {

                if (method_exists(__CLASS__, $key)) {

                    // Define value
                    $value = is_array($data) && isset($data['value']) ? $data['value'] : $data;

                    // Define except
                    $except = is_array($data) && isset($data['except']) && is_bool($data['except']) ? true : false;

                    // Check on having access
                    $access = $this->$key($value);

                    if ($except === true) $access = !$access;

                    if ( !$access ) $this->access = false;
                }
            }
        }
    }

    public function get() {
        return $this->access;
    }

    // Check user capability
    public function user_capability($value) {
        return !empty($this->user) && !empty($this->user->allcaps[$value]);
    }

    // Check user ID
    public function user_id($value) {
        return !empty($this->user) && $this->user->ID == $value;
    }

    // Check user role
    public function user_role($value) {
        return !empty($this->user) && in_array($value, $this->user->roles);
    }
}