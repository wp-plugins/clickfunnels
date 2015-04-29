<?php
/**
    * Plugin Name: ClickFunnels
    * Plugin URI: http://clickfunnels.com
    * Description: Connect your ClickFunnel pages to your blog. Create new pages, connect to homepage or 404 pages.
    * Version: 1.0.7
    * Author: Etison, LLC
    * Author URI: http://clickfunnels.com
*/
define( "CF_URL", plugin_dir_url( __FILE__ ) );
define( "CF_PATH", plugin_dir_path( __FILE__ ) );
define( "CF_API_EMAIL", get_option( 'clickfunnels_api_email' ) );
define( "CF_API_AUTH_TOKEN", get_option( 'clickfunnels_api_auth' ) );
define( "CF_API_URL", "https://api.clickfunnels.com/" );
include "CF_API.php";
class ClickFunnels {
    public function __construct( CF_API $api ) {
        $this->api = $api;
        add_action( "init", array( $this, "create_custom_post_type" ) );
        add_action( "admin_enqueue_scripts", array( $this, "load_scripts" ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_filter( 'manage_edit-clickfunnels_columns', array( $this, 'add_columns' ) );
        add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
        add_action( 'manage_posts_custom_column', array( $this, 'fill_columns' ) );
        add_action( "template_redirect", array( $this, "do_redirects" ), 10, 2 );
        add_action( 'trashed_post', array( $this, 'post_trash' ), 10 );
        add_filter( 'post_updated_messages', array( $this, 'updated_message' ) );
        // check permalinks
        if ( get_option( 'permalink_structure' ) == '' ) {
            $this->add_notice( 'ClickFunnels needs <a href="options-permalink.php">permalinks</a> enabled!', 0 );
        }
    }
    public function updated_message( $messages ) {
        $post_id = get_the_ID();
        // make sure this is one of our pages
        if ( get_post_meta( $post_id, "cf_thepage", true ) == "" )
            return $messages;
        $data = $this->get_url( $post_id );
        $view_html = " <a target='_blank' href='{$data['url']}'>Click to View</a> ";
        $messages['post'][1] = 'Successfully Updated Page';
        $messages['post'][4] = 'Successfully Updated Page';
        $messages['post'][6] = 'Successfully Saved Your Changes';
        $messages['post'][10] = 'Successfully Updated Page';
        return $messages;
    }
    public function post_trash( $post_id ) {
        $cf_slug= get_post_meta( $post_id, 'cf_slug', true );
        $cf_options = get_option( "cf_options" );
        unset( $cf_options["pages"][$cf_slug] );
        update_option( "cf_options", $cf_options );
        if ( $this->is_404( $post_id ) ) {
            $this->set_404( "", "" );
        }
        if ( $this->is_home( $post_id ) ) {
            $this->set_home( "", "" );
        }
    }
    public function do_redirects() {
        global $page;
        header('Content-Type: text/html; charset=utf-8');
        $current = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        // remove parameters
        $current = explode( "?", $current );
        $current = $current[0];
        $home_url = get_home_url()."/";
        $slug = str_replace( $home_url, "", $current );
        $slug= rtrim( $slug, '/' );
        $cf_options = get_option( "cf_options" );
        if ( !empty( $cf_options["pages"][$slug] ) ) {
            $thepage = explode( "{#}", $cf_options["pages"][$slug] );
            // echo $thepage[0] . "<br/> position:"; // funnel ID
            // echo $thepage[1]; // page position

            echo $this->get_page_html( $thepage[0], $thepage[1], $thepage[4] );
            exit();
        } else if ( is_404() ) {
                $page = $this->get_404();
                if ( !empty( $page['post_id'] ) ) {
                    $thepage = explode( "{#}", $page['page_id'] );
                    echo $this->get_page_html( $thepage[0], $thepage[1], $thepage[4] );
                    exit();
                }
                 else if (get_option( 'clickfunnels_404Redirect' ) == 'yesRedirect') {
                    wp_redirect( get_bloginfo( 'siteurl' ) , 301 );
                }
            }
        else if ( is_home() ) {
                $page = $this->get_home();
                if ( !empty( $page['post_id'] ) ) {
                    $thepage = explode( "{#}", $page['page_id'] );
                    echo $this->get_page_html( $thepage[0], $thepage[1], $thepage[4] );
                    exit();
                }
            }
    }
    public function fill_columns( $column ) {
        $id = get_the_ID();
        $cf_type = get_post_meta( $id, 'cf_type', true );
        $cf_slug= get_post_meta( $id, 'cf_slug', true );
        $cf_thefunnel= get_post_meta( $id, 'cf_thefunnel', true );
        if ( $cf_type =="hp" && !$this->is_home( $id ) ) {
            $cf_type = "notype";
        }
        if ( $cf_type =="np" && !$this->is_404( $id ) ) {
            $cf_type = "notype";
        }
        if ( 'cf_post_name' == $column ) {
            $url = get_edit_post_link( get_the_ID() );
            $funnel_id = get_post_meta( get_the_ID(), 'cf_thefunnel', true );
            if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) {
                echo "[ Incorrect API ]  <a href='../wp-admin/edit.php?post_type=clickfunnels&page=cf_api'>Setup API Settings</a>";
            } else {
                echo '<strong><a href="' . $url .'">' .  $this->get_funnel_name( $funnel_id ) . '</a></strong>' ;
            }
        }
        switch ( $cf_type ) {
        case "p":
            $post_type = "Page";
            $url = get_option( 'clickfunnels_siteURL' )."/".$cf_slug;
            break;
        case "hp":
            $post_type = "Home Page";
            $url = get_option( 'clickfunnels_siteURL' ).'/';
            break;
        case "np":
            $post_type = "404 Page";
            $url = get_option( 'clickfunnels_siteURL' ).'/test-url-random';
            break;
        default:
            $post_type = "Not defined yet";
            $url = '';
        }
        if ( 'cf_type' == $column ) {
            echo "<strong>$post_type</strong>";
        }
        if ( 'cf_path' == $column ) {
            if ( !empty( $url ) ) {
                echo " <a href='$url' target='_blank'>$url</a>";
            }
        }
    }
    private function get_url( $post_id ) {
        $cf_type = get_post_meta( $post_id, 'cf_type', true );
        $cf_slug= get_post_meta( $post_id, 'cf_slug', true );
        switch ( $cf_type ) {
        case "p":
            $data['post_type'] = "Page";
            $data['url'] = get_option( 'clickfunnels_siteURL' )."/".$cf_slug;
            break;
        case "hp":
            $data['post_type']= "Home Page";
            $data['url'] = get_option( 'clickfunnels_siteURL' ).'/';
            break;
        case "np":
            $data['post_type'] = "404 Page";
            $data['url'] = get_option( 'clickfunnels_siteURL' ).'/test-url-random';
        default:
            $data['post_type'] = "Not Defined";
            $data['url'] = '';
        }
        return $data;
    }
    public function get_funnels() {
        $cf_funnels = get_transient( "cf_funnels" );
        if ( !empty( $cf_funnels ) ) {
            return $cf_funnels;
        }
        else {
            $cf_funnels  = $this->api->get_funnels();
            return $cf_funnels;
        }
    }
    public function get_page_html( $funnel_id, $position, $meta ) {
        $page_html = get_transient( "cf_page_html_{$funnel_id}" );
        if ( !empty( $page_html ) ) {
            return $page_html;
        }
        else {
            $page_html = $this->api->get_page_html( $funnel_id, $position, $meta );
            return $page_html;
        }
    }
    public function save_meta( $post_id, $post ) {
        if ( !isset( $_POST['clickfunnel_nonce'] ) || !wp_verify_nonce( $_POST['clickfunnel_nonce'], "save_clickfunnel" ) )
            return $post_id;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;
        if ( $post->post_type != 'clickfunnels' )
            return $post_id;
            $cf_type = $_POST['cf_type'];
            $cf_page = $_POST['cf_page'];
            $cf_thepage = $_POST['cf_thepage'];
            $cf_slug = $_POST['cf_slug'];
            $cf_thefunnel = $_POST['cf_thefunnel'];
            $cf_seo_tags = $_POST['cf_seo_tags'];
            update_post_meta( $post_id, "cf_type", $cf_type );
            update_post_meta( $post_id, "cf_page", $cf_page );
            update_post_meta( $post_id, "cf_thepage", $cf_thepage );
            update_post_meta( $post_id, "cf_slug", $cf_slug );
            update_post_meta( $post_id, "cf_thefunnel", $cf_thefunnel );
            update_post_meta( $post_id, "cf_seo_tags", $cf_seo_tags );
            $cf_options = get_option( "cf_options" );
            unset( $cf_options['pages'][$cf_slug] );
            update_option( "cf_options", $cf_options );
        if ( $this->is_404( $post_id ) )
            $this->set_404( "", "" );
        if ( $this->is_home( $post_id ) )
            $this->set_home( "", "" );
        switch ( $cf_type ) {
        case "p":
            $cf_options = get_option( "cf_options" );
            $cf_options['pages'][$cf_slug] = $cf_thefunnel;
            update_option( "cf_options", $cf_options );
            break;
        case "hp":  // home page
            $this->set_home( $post_id, $cf_thefunnel );
            break;
        case "np":  // 404 page
            $this->set_404( $post_id, $cf_thefunnel );
            break;
        }
    }
    public function get_page( $page_slug ) {
        $cf_options = get_option( "cf_options" );
        return $cf_options['pages'][$page_slug];
    }
    public function is_page( $page_slug ) {
        $cf_options = get_option( "cf_options" );
        if ( !empty( $cf_options['pages'][$page_slug] ) )
            return true;
        return false;
    }
    public function set_home( $post_id, $page_id ) {
        $cf_options = get_option( "cf_options" );
        $cf_options['home']['post_id'] = $post_id;
        $cf_options['home']['page_id'] = $page_id;
        update_option( "cf_options", $cf_options );
    }
    public function get_home() {
        $cf_options = get_option( "cf_options" );
        return $cf_options['home'];
    }
    public function is_home( $post_id ) {
        $cf_options = get_option( "cf_options" );
        if ( $cf_options['home']['post_id'] == $post_id )
            return true;
        return false;
    }
    public function set_404( $post_id, $page_id ) {
        $cf_options = get_option( "cf_options" );
        $cf_options['404']['post_id'] = $post_id;
        $cf_options['404']['page_id'] = $page_id;
        update_option( "cf_options", $cf_options );
    }
    public function get_404() {
        $cf_options = get_option( "cf_options" );
        return $cf_options['404'];
    }
    public function is_404( $post_id ) {
        $cf_options = get_option( "cf_options" );
        if ( $cf_options['404']['post_id'] == $post_id )
            return true;
        return false;
    }
    public function add_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['cf_post_name'] = "Funnel";
        $new_columns['cf_type'] = 'Type';
        $new_columns['cf_path'] = 'URL';
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }
    function view( $view, $data = array() ) {
        ob_start();
        extract( $data );
        include CF_PATH.$view.'.php';
        $content=ob_get_clean();
        return $content;
    }
    public function add_meta_box() {
        add_meta_box(
            'clickfunnels_meta_box', // $id
            'Setup Your ClickFunnels Page', // $title
            array( $this, "show_meta_box" ),
            'clickfunnels', // $page
            'normal', // $context
            'high' // $priority
        );
    }
    public function get_page_mode( $type = "edit" ) {
        global $pagenow;
        if ( !is_admin() ) return false;
        if ( $type == "edit" ) {
            return in_array( $pagenow, array( 'post.php', ) );
        } elseif ( $type == "new" ) { // check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        } else { // check for either new or edit
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
        }
    }
    public function show_meta_box( $post ) {
        $id = $post->ID;
        $slug = get_post_meta( $id, "cf_slug", true );
        $thefunnel = get_post_meta( $id, "cf_thefunnel", true );
        $cf_seo_tags = get_post_meta( $id, "cf_seo_tags", true );
        $original_cf_type = get_post_meta( $id, "cf_type", true );
        if ( $this->get_page_mode( "new" ) )
            $original_cf_type = "p";
        if ( $original_cf_type == "p" ) {
            $data['cf_type'] = 'p';
            $data['cf_page'] =  $this->get_page( $slug );
            $data['cf_slug'] = $slug;
        }
        if ( $this->is_home( $id ) ) {
            $data['cf_type'] = 'hp';
            $data['cf_page'] = $this->get_home();
        }
        if ( $this->is_404( $id ) ) {
            $data['cf_type'] = 'np';
            $data['cf_page'] = $this->get_404();
        }
        if ( $this->get_page_mode( "edit" ) )
            $data['delete_link'] = get_delete_post_link( $id );
        else
            $data['delete_link'] ="";
        $data['cf_funnels'] = $this->get_funnels();
        echo $this->view( "admin", $data );
    }
    public function get_funnel_name( $funnel_id ) {
        $funnels = $this->get_funnels();
        foreach ( $funnels as $funnel )
            if ( $funnel->id == $funnel_id )
                return $funnel->name;
    }
    public function load_scripts() {
        global $post_type;
        if ( $post_type == "clickfunnels" ) {
            $this->load_actual_scripts();
        }
    }
    public function add_notice( $message, $type=1 ) {
        $this->message = $message;
        switch ( $type ) {
        case 1:
            add_action( "admin_notices", array( $this, "success_massage" ) );
            break;
        case 0:
            add_action( "admin_notices", array( $this, "error_message" ) );
            break;
        }
    }
    public function success_message() {
        echo '<div id="message" class="updated"><p><strong>Updated Click Funnels Page.</strong></p></div>';
    }
    public function error_message() {
        echo '<div id="message" class="error"><p><strong>Uh oh, something went wrong.</strong></p></div>';
    }
    public function load_actual_scripts() {
        wp_register_style( "clickfunnels_bootstrap", CF_URL."css/bootstrap.css" );
        wp_enqueue_style( "clickfunnels_bootstrap" );
        wp_enqueue_style( "clickfunnels_admin" );
        wp_register_script( "clickfunnels_admin", CF_URL."js/admin.js" );
        wp_enqueue_script( "clickfunnels_admin" );
    }
    public function remove_save_box() {
        global $wp_meta_boxes;
        foreach ( $wp_meta_boxes['clickfunnels'] as $k=>$v )
            foreach ( $v as $l=>$m )
                foreach ( $m as $o=>$p )
                    if ( $o !="clickfunnels_meta_box" )
                        unset( $wp_meta_boxes['clickfunnels'][$k][$l][$o] );
    }
    public function create_custom_post_type() {
        $labels = array(
            'name' => _x( 'ClickFunnels', 'post type general name' ),
            'singular_name' => _x( 'Pages', 'post type singular name' ),
            'add_new' => _x( 'Add New', 'Click Funnels' ),
            'add_new_item' => __( 'Add New ClickFunnels Page' ),
            'edit_item' => __( 'Edit ClickFunnels Page' ),
            'new_item' => __( 'Add New' ),
            'all_items' => __( 'Pages' ),
            'view_item' => __( 'View Click Funnels Pages' ),
            'search_items' => __( 'Search Click Funnels' ),
            'not_found' => __( 'Nothing found' ),
            'not_found_in_trash' => __( 'Nothing found in Trash' ),
            'parent_item_colon' => '',
            'hide_post_row_actions' => array(
                 'trash'
                 ,'edit'
                 ,'quick-edit'
               )
        );

        register_post_type( 'clickfunnels',
            array(
                'labels' =>  $labels,
                'public' => true,
                'menu_icon' => plugins_url( )."/clickfunnels/icon.png",
                'has_archive' => true,
                'supports' => array( '' ),
                'rewrite' => array( 'slug' => 'clickfunnels' ),
                'hide_post_row_actions' => array( 'trash' ),
                'register_meta_box_cb' => array( $this, "remove_save_box" )
               
            )
        );
    }
}
add_action( 'admin_menu', 'cf_plugin_submenu' );
add_action( 'views_edit-clickfunnels', 'remove_edit_post_views' );
function remove_edit_post_views( $views ) {
         if( get_post_type() === 'movie' )  {
            unset($views['all']);
            unset($views['publish']);
            unset($views['trash']);
        }
        unset($views);
}
function cf_plugin_submenu() {
    add_submenu_page( 'edit.php?post_type=clickfunnels', __( 'Settings', 'menu-test' ), __( 'Settings', 'menu-test' ), 'manage_options', 'cf_api', 'cf_api_settings_page' );
    add_submenu_page( 'edit.php?post_type=clickfunnels', __( 'How to Use ClickFunnels Plugin', 'menu-test' ), __( 'Support', 'menu-test' ), 'manage_options', 'clickfunnels_support', 'support_clickfunnels' );
}
function cf_api_settings_page() {
    include 'access.php';
}
function support_clickfunnels() {
    include 'support.php';
}
function clickfunnels_loadjquery($hook) {
    if( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
        return;
    }
    wp_enqueue_script( 'jquery' );
}
add_action('admin_enqueue_scripts', 'clickfunnels_loadjquery');
$api = new CF_API();
$click = new ClickFunnels( $api );

function cf_get_file_contents ($url) {
    if (function_exists('curl_exec')){ 
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        $url_get_contents_data = (curl_exec($conn));
        curl_close($conn);
    }elseif(function_exists('file_get_contents')){
        $url_get_contents_data = file_get_contents($url);
    }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
        $handle = fopen ($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
    }else{
        $url_get_contents_data = false;
    }
return $url_get_contents_data;
} 