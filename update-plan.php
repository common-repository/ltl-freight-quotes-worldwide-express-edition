<?php

/**
 * WWE ltl Update Plan
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_en_wwe_ltl_activate_hit_to_update_plan', 'en_wwe_ltl_activate_hit_to_update_plan');
add_action('wp_ajax_nopriv_en_wwe_ltl_activate_hit_to_update_plan', 'en_wwe_ltl_activate_hit_to_update_plan');

/**
 * Activate WWE LTL
 */
function en_wwe_ltl_activate_hit_to_update_plan($network_wide = null)
{
    if ( is_multisite() && $network_wide ) {

        foreach (get_sites(['fields'=>'ids']) as $blog_id) {
            switch_to_blog($blog_id);
            $domain = wwe_quests_get_domain();

            $index = 'ltl-freight-quotes-worldwide-express-edition/woocommercefrieght.php';
            $plugin_info = get_plugins();
            $plugin_version = isset($plugin_info[$index]['Version']) ? $plugin_info[$index]['Version'] : '';

            $plugin_dir_url = plugin_dir_url(__FILE__) . 'en-hit-to-update-plan.php';
            $post_data = array(
                'platform' => 'wordpress',
                'carrier' => '1',
                'store_url' => $domain,
                'webhook_url' => $plugin_dir_url,
                'plugin_version' => $plugin_version,
            );

            $license_key = get_option('wc_settings_wwe_licence_key');
            strlen($license_key) > 0 ? $post_data['license_key'] = $license_key : '';

            $url = WWE_FREIGHT_DOMAIN_HITTING_URL . "/web-hooks/subscription-plans/create-plugin-webhook.php?";
            $response = wp_remote_get($url,
                array(
                    'method' => 'GET',
                    'timeout' => 60,
                    'redirection' => 5,
                    'blocking' => true,
                    'body' => $post_data,
                )
            );
            $output = wp_remote_retrieve_body($response);
            $response = json_decode($output, TRUE);

            $plan = (isset($response['pakg_group'])) ? $response['pakg_group'] : '';
            $expire_day = (isset($response['pakg_duration'])) ? $response['pakg_duration'] : '';
            $expiry_date = (isset($response['expiry_date'])) ? $response['expiry_date'] : '';
            $plan_type = (isset($response['plan_type'])) ? $response['plan_type'] : '';

            if ($response['pakg_price'] == '0') {
                $plan = '0';
            }

            update_option('wwe_ltl_packages_quotes_package', "$plan");
            update_option('wwe_ltl_package_expire_days', "$expire_day");
            update_option('wwe_ltl_package_expire_date', "$expiry_date");
            update_option('wwe_quests_store_type', "$plan_type");

            en_check_wwe_ltl_plan_on_product_detail();
            restore_current_blog();
        }

    } else {
        $domain = wwe_quests_get_domain();

        $index = 'ltl-freight-quotes-worldwide-express-edition/woocommercefrieght.php';
        $plugin_info = get_plugins();
        $plugin_version = isset($plugin_info[$index]['Version']) ? $plugin_info[$index]['Version'] : '';

        $plugin_dir_url = plugin_dir_url(__FILE__) . 'en-hit-to-update-plan.php';
        $post_data = array(
            'platform' => 'wordpress',
            'carrier' => '1',
            'store_url' => $domain,
            'webhook_url' => $plugin_dir_url,
            'plugin_version' => $plugin_version,
        );

        $license_key = get_option('wc_settings_wwe_licence_key');
        strlen($license_key) > 0 ? $post_data['license_key'] = $license_key : '';

        $url = WWE_FREIGHT_DOMAIN_HITTING_URL . "/web-hooks/subscription-plans/create-plugin-webhook.php?";
        $response = wp_remote_get($url,
            array(
                'method' => 'GET',
                'timeout' => 60,
                'redirection' => 5,
                'blocking' => true,
                'body' => $post_data,
            )
        );
        $output = wp_remote_retrieve_body($response);
        $response = json_decode($output, TRUE);

        $plan = (isset($response['pakg_group'])) ? $response['pakg_group'] : '';
        $expire_day = (isset($response['pakg_duration'])) ? $response['pakg_duration'] : '';
        $expiry_date = (isset($response['expiry_date'])) ? $response['expiry_date'] : '';
        $plan_type = (isset($response['plan_type'])) ? $response['plan_type'] : '';

        if ($response['pakg_price'] == '0') {
            $plan = '0';
        }

        update_option('wwe_ltl_packages_quotes_package', "$plan");
        update_option('wwe_ltl_package_expire_days', "$expire_day");
        update_option('wwe_ltl_package_expire_date', "$expiry_date");
        update_option('wwe_quests_store_type', "$plan_type");

        en_check_wwe_ltl_plan_on_product_detail();
    }

}

/**
 * Product detail Features
 */
function en_check_wwe_ltl_plan_on_product_detail()
{

    $hazardous_feature_PD = 0;
    $dropship_feature_PD = 1;
    $nested_material_PD = 0;
    $insurance_feature_PD = 0;

//  Hazardous Material

    $hazardous_material = apply_filters('wwe_quests_quotes_plans_suscription_and_features', 'hazardous_material');
    if (!is_array($hazardous_material)) {
        $hazardous_feature_PD = 1;
    }

//  Nested material
    $nested_mateials = apply_filters('wwe_quests_quotes_plans_suscription_and_features', 'nested_material');
    if (!is_array($nested_mateials)) {
        $nested_material_PD = 1;
    }

//  Dropship 
    if (get_option('wwe_quests_store_type') == "1") {
        $action_dropship = apply_filters('wwe_quests_quotes_plans_suscription_and_features', 'multi_dropship');
        if (!is_array($action_dropship)) {
            $dropship_feature_PD = 1;
        } else {
            $dropship_feature_PD = 0;
        }
    }

    //  Insurance Fee
    $action_insurance = apply_filters('wwe_quests_quotes_plans_suscription_and_features', 'insurance_fee');
    if (!is_array($action_insurance)) {
        $insurance_feature_PD = 1;
    }

    update_option('eniture_plugin_2', array('wwe_ltl_packages_quotes_package' => array('plugin_name' => 'WooCommerce WWE LTL Quotes', 'multi_dropship' => $dropship_feature_PD, 'hazardous_material' => $hazardous_feature_PD, 'nested_material' => $nested_material_PD, 'insurance_fee' => $insurance_feature_PD)));
}

/**
 * Deactivate WWE LTL
 */
function en_wwe_ltl_deactivate_hit_to_update_plan($network_wide = null)
{
    if ( is_multisite() && $network_wide ) {

        foreach (get_sites(['fields'=>'ids']) as $blog_id) {
            switch_to_blog($blog_id);
            delete_option('eniture_plugin_2');
            delete_option('wwe_ltl_packages_quotes_package');
            delete_option('wwe_ltl_package_expire_days');
            delete_option('wwe_ltl_package_expire_date');
            delete_option('wwe_quests_store_type');
            restore_current_blog();
        }

    } else {
        delete_option('eniture_plugin_2');
        delete_option('wwe_ltl_packages_quotes_package');
        delete_option('wwe_ltl_package_expire_days');
        delete_option('wwe_ltl_package_expire_date');
        delete_option('wwe_quests_store_type');
    }
}

/**
 * Get WWE ltl Plan
 * @return string
 */
function en_wwe_ltl_plan_name()
{
    $plan = get_option('wwe_ltl_packages_quotes_package');
    $expire_days = get_option('wwe_ltl_package_expire_days');
    $expiry_date = get_option('wwe_ltl_package_expire_date');
    $plan_name = "";

    switch ($plan) {
        case 3:
            $plan_name = "Advanced Plan";
            break;
        case 2:
            $plan_name = "Standard Plan";
            break;
        case 1:
            $plan_name = "Basic Plan";
            break;
        case 0:
            $plan_name = "Trial Plan";
            break;
    }

    $package_array = array(
        'plan_number' => $plan,
        'plan_name' => $plan_name,
        'expire_days' => $expire_days,
        'expiry_date' => $expiry_date
    );
    return $package_array;
}

/**
 * Show WWE ltl Plan Notice
 * @return string
 */
function en_wwe_ltl_plan_notice()
{
    if (isset($_GET['tab']) && ($_GET['tab'] == "wwe_quests")) {
        $plan_package = en_wwe_ltl_plan_name();
        $plan_number = get_option('wwe_ltl_packages_quotes_package');
        $store_type = get_option('wwe_quests_store_type');

        if ($store_type == "1" || $store_type == "0" && ($plan_number == "0" || $plan_number == "1" || $plan_number == "2" || $plan_number == "3")) {

            $click_here_to_update_plan = ' <a href="javascript:void(0)" data-action="en_wwe_ltl_activate_hit_to_update_plan" onclick="en_update_plan(this);">Click here</a> to refresh the plan';

            if ($plan_package['plan_number'] == '0') {

                echo '<div class="notice notice-success is-dismissible">
                <p> You are currently on the ' . $plan_package['plan_name'] . '. Your plan will be expire on ' . $plan_package['expiry_date'] . $click_here_to_update_plan . '.</p>
                </div>';
            } else if ($plan_package['plan_number'] == '1' || $plan_package['plan_number'] == '2' || $plan_package['plan_number'] == '3') {

                echo '<div class="notice notice-success is-dismissible">
                <p> You are currently on ' . $plan_package['plan_name'] . '. The plan renews on ' . $plan_package['expiry_date'] . $click_here_to_update_plan . '.</p>
                </div>';
            } else {
                echo '<div class="notice notice-warning is-dismissible">
                <p>Your currently plan subscription is inactive. ' . $click_here_to_update_plan . ' to check the subscription status. If the subscription status remains inactive, log into eniture.com and update your license.</p>
                </div>';
            }
        }
    }
}

add_action('admin_notices', 'en_wwe_ltl_plan_notice');
