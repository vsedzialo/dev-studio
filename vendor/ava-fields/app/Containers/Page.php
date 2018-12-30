<?php
namespace DS_AVAFields\Containers;

/**
 * Page container class
 *
 * Allow to add custom settings
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Page extends \DS_AVAFields\Core\Abstracts\Container
{
    public $default = [];

    public function __construct($type, $params)
    {
        parent::__construct($type, $params);

        if ($type != 'custom') {
            add_action('admin_menu', [$this, 'admin_menu']);
        }

        // Load fields options
        $this->load_fields_options();
    }

    public function load_fields_options()
    {
        $this->Options->fields_options = ($this->Options)::get_options( $this->Options->options['option_name'] );
    }

    public function admin_menu()
    {
        $menu = $this->params['menu'];
        $capability = $this->params['access']['user_capability'];
        $function = [$this, 'output'];

        switch ($this->type) {
            case 'dashboard-page':
                add_dashboard_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'theme-page':
                add_theme_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'posts-page':
                add_posts_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'media-page':
                add_media_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'pages-page':
                add_pages_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'comments-page':
                add_comments_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'plugins-page':
                add_plugins_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'users-page':
                add_users_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'tools-page':
                add_theme_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'settings-page':
                add_options_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            case 'post-type-page':
                add_submenu_page('edit.php?post_type=' . $menu['post_type'], $menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                break;
            default:
                if (isset($menu['parent'])) {
                    add_submenu_page($menu['parent'], $menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                } else {
                    add_menu_page($menu['menu_title'], $menu['menu_title'], $capability, $menu['menu_slug'], $function);
                }
        }
    }

    public function add_section($id, $params)
    {
        $section = new \DS_AVAFields\Sections\Page($this->id, $id, $params);

        if ($section) {
            $this->sections[$id] = $section;
        }

        return $section;
    }

    public function render()
    {
        $this->html = '<div class="avaf avaf-container avaf-' . $this->type . '" data-container="' . esc_attr($this->id) . '" data-option_name="' . esc_attr($this->params['options']['option_name']) . '">';

        $this->html .= $this->get_header();

        $active_section = $this->get_active_section();

        // Navigation menu & sections
        $this->html .= '<div class="avaf-nav-sections ' . esc_attr($this->params['appearance']['nav_style']) . '">';

            // Navigation menu
            if ($this->params['appearance']['nav']) {
                $this->html .= '<div class="avaf-nav">';

                foreach ($this->sections as $section_id => $section) {

                    $active_class = ($active_section == $section->id ? 'active' : '');

                    $this->html .= '<a class="avaf-nav-item ' . esc_attr($active_class) . '" href="#' . esc_attr($section->id) . '" data-section="' . esc_attr($section->id) . '">';

                    if (!empty($section->params['icon'])) {
                        $this->html .= '<img class="avaf-nav-icon" src="' . esc_url($section->params['icon']) . '">';
                    }
                    $this->html .= '<span>' . $section->params['title'] . '</span>';
                    $this->html .= '</a>';
                }
                $this->html .= '</div>';
            }
            // end Navigation menu

            // Sections
            $this->html .= '<div class="avaf-sections">';

            foreach ($this->sections as $section_id => $section) {
                $this->html .= $section->render([
                        'active' => $active_section
                    ]
                );
            }
            $this->html .= '</div>';
            // end Section

        $this->html .= '</div>';
        // end Navigation menu & sections

        // Control panel
        if (!empty($this->params['control'])) {
            $this->html .= '<div class="avaf-control">';
            foreach ($this->params['control'] as $key => $data) {
                if (!empty($data)) {
                    $this->html .= '<button class="avaf-button ' . (isset($data['class']) ? esc_attr($data['class']) : '') . '" data-container="' . esc_attr($this->id) . '">' .
                        wp_kses_post($data['text']) .
                        '</button>';
                }
            }
            $this->html .= '</div>';
        }

        // Preloader
        $this->html .= '<div class="avaf-preloader" data-container="' . esc_attr($this->id) . '">';
            $this->html .= '<div class="avaf-pl-image">';
            $this->html .= !isset($this->params['preloader']['image'])
                ? '<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px" viewBox="0 0 128 16" xml:space="preserve"><rect x="0" y="0" width="100%" height="100%" fill="#FFFFFF" /><path fill="#949494" fill-opacity="0.42" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z"/><g><path fill="#000000" fill-opacity="1" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z"/><animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite"/></g></svg>'
                : '<img src="'.esc_url($this->params['preloader']['image']).'">';
            $this->html .= '</div>';

            $this->html .= '<div class="avaf-pl-text">';
            $this->html .= !isset($this->params['preloader']['text'])
                ? __('Saving...', 'dev-studio')
                : $this->params['preloader']['text'];
            $this->html .= '</div>';
        $this->html .= '</div>';

        return $this->html;
    }

    public function get_header()
    {
        $html = '';
        if (!empty($this->params['container']['title']) || !empty($this->params['container']['subtitle'])) {
            $html = '<div class="avaf-header">';

            $html .= '<div class="avaf-title">' . $this->params['container']['title'] . '</div>';
            if (isset($this->params['container']['subtitle'])) {
                $html .= '<div class="avaf-subtitle">' . $this->params['container']['subtitle'] . '</div>';
            }

            $html .= '</div>';
        }
        return $html;
    }

    public function get_active_section()
    {
        if (isset($this->params['container']['section'])) return $this->params['container']['section'];

        return 'general';
    }
}
