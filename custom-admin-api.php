<?php
/**
 * Plugin Name: Custom Admin & API
 * Description: Customizes the WordPress login page.
 * Version: 1.0.0
 * Author: Amratanshu
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** --------------------------------------
 * Login Page
 * -------------------------------------- */
function custom_login_styles() {
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' );

    $login_styles = <<<CSS
    body.login {
        background-color: #f3f4f6;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }
    body.login div#login {
        background-color: #fff;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        max-width: 400px;
        width: 90%;
    }
    body.login h1 a {
        background-image: none;
        text-indent: 0;
        display: block;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    body.login h1 a:before {
        content: "Your Logo";
        font-size: 1.5rem;
        color: #1e293b;
    }
    body.login form {
        margin-bottom: 1rem;
    }
    body.login label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4b5563;
        font-weight: 500;
        font-size: 0.875rem;
    }
    body.login input[type="text"],
    body.login input[type="password"] {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
        margin-bottom: 1rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    body.login input[type="text"]:focus,
    body.login input[type="password"]:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        outline: none;
    }
    body.login .button.wp-submit {
        width: 100%;
        padding: 0.75rem 1.5rem;
        background-color: #3b82f6;
        color: #fff;
        border: none;
        border-radius: 0.375rem;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.15s ease, box-shadow 0.15s ease;
    }
    body.login .button.wp-submit:hover {
        background-color: #2563eb;
    }
    body.login .button.wp-submit:focus {
        box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        outline: none;
    }
    body.login .forgetmenot {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    body.login .forgetmenot label {
        margin-left: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }
    body.login .forgetmenot input[type="checkbox"] {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }
    body.login #nav,
    body.login #backtoblog {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 1rem;
        text-align: center;
    }
    body.login #nav a,
    body.login #backtoblog a {
        color: #3b82f6;
    }
    body.login #nav a:hover,
    body.login #backtoblog a:hover {
        color: #2563eb;
    }
    .login #login_error {
        border-left-color: #dc2626  ;
        background-color: #fee2e2  ;
        color: #b91c1c  ;
        border-radius: 0.375rem;
        padding: 1rem;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
CSS;
    wp_add_inline_style( 'login', $login_styles );
}
add_action( 'login_enqueue_scripts', 'custom_login_styles' );

add_filter( 'login_headerurl', function() {
    return home_url();
} );

add_filter( 'login_headertext', function() {
    return get_bloginfo( 'name' );
} );

/** --------------------------------------
 * Add Button to Admin Dashboard
 * -------------------------------------- */
function add_dashboard_button() {
    add_meta_box(
        'custom_dashboard_button',
        'Custom Redirect Button',
        'render_dashboard_button',
        'dashboard',
        'normal',
        'high'
    );
}
add_action( 'wp_dashboard_setup', 'add_dashboard_button' );

function render_dashboard_button() {
    $admin_url = admin_url( 'xyz' );
    echo '<button id="redirect-button" class="button button-primary">Go to XYZ</button>';
    echo "<script>
        document.getElementById('redirect-button').addEventListener('click', function() {
            window.location.href = '" . esc_url_raw( $admin_url ) . "';
        });
    </script>";
}

/** --------------------------------------
 * REST API Endpoint - Took Help 
 * -------------------------------------- */
add_action( 'rest_api_init', function() {
    register_rest_route( 'custom/v1', '/users/(?P<id>\d+)', array(
        'methods'  => 'GET',
        'callback' => function( $request ) {
            return rest_ensure_response( array( 'user_id' => 4 ) );
        },
        'permission_callback' => '__return_true',
    ) );
} );

/** --------------------------------------
 * AJAX Endpoint 
 * -------------------------------------- */
add_action( 'wp_ajax_get_user_id_ajax', 'get_user_id_ajax' );
add_action( 'wp_ajax_nopriv_get_user_id_ajax', 'get_user_id_ajax' );

function get_user_id_ajax() {
    wp_send_json_success( array( 'user_id' => 4 ) );
}

/** --------------------------------------
 * Admin Menu for AJAX Demo
 * -------------------------------------- */
add_action( 'admin_menu', function() {
    add_menu_page(
        'AJAX Test Page',
        'AJAX Test',
        'manage_options',
        'ajax-test-page',
        'render_ajax_test_page',
        'dashicons-admin-tools'
    );
} );

function render_ajax_test_page() {
    ?>
    <div class="wrap">
        <h1>AJAX Test Page</h1>
        <p>Click the button to send an AJAX request and get the user ID.</p>
        <button id="ajax-button" class="button button-primary">Get User ID via AJAX</button>
        <div id="ajax-response"></div>
        <script>
        document.getElementById('ajax-button').addEventListener('click', function() {
            var data = {
                'action': 'get_user_id_ajax',
                'id': 5
            };
            jQuery.post(ajaxurl, data, function(response) {
                if (response.success) {
                    document.getElementById('ajax-response').innerHTML = 'User ID: ' + response.data.user_id;
                } else {
                    document.getElementById('ajax-response').innerHTML = 'Error: ' + response.data;
                }
            });
        });
        </script>
    </div>
    <?php
}
