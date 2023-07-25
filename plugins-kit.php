<?php
/*
Plugin Name: Plugins Kit
*/
function is_acf_active()
{
    return class_exists('ACF');
}

register_activation_hook(__FILE__, 'my_acf_plugin_activation');
function my_acf_plugin_activation()
{
    if (!is_acf_active()) {
        my_acf_install();
    }
}

function my_acf_install()
{
    $plugin_path = plugin_dir_path(__FILE__);
    $acf_zip_file = $plugin_path . 'includes/acf-pro.zip';

    if (file_exists($acf_zip_file)) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();
        $unzip_result = unzip_file($acf_zip_file, WP_PLUGIN_DIR);

        if (is_wp_error($unzip_result)) {
            wp_die('Failed to install ACF Pro. Please make sure the includes folder contains the ACF Pro zip file.');
        } else {
            $active_plugins = get_option('active_plugins');
            if (!in_array('advanced-custom-fields-pro/acf.php', $active_plugins)) {
                array_push($active_plugins, 'advanced-custom-fields-pro/acf.php');
                update_option('active_plugins', $active_plugins);
            }

            if (!in_array('plugins-kit/plugins-kit.php', $active_plugins)) {
                array_push($active_plugins, 'plugins-kit/plugins-kit.php');
                update_option('active_plugins', $active_plugins);
            }

            wp_redirect(admin_url('plugins.php'));
            exit;
        }
    } else {
        wp_die('ACF Pro zip file not found. Please make sure the includes folder contains the ACF Pro zip file.');
    }
}
