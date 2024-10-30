<?php
/**
 * WWE LTL Test connection
 *
 * @package     WWE LTL Quotes
 * @author      Eniture-Technology
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_nopriv_ltl_validate_keys', 'ltl_speedfreight_submit');
add_action('wp_ajax_ltl_validate_keys', 'ltl_speedfreight_submit');
/**
 * Test connection Function
 */
function ltl_speedfreight_submit()
{

    $sp_user = $_POST['speed_freight_username'];
    $sp_pass = $_POST['speed_freight_password'];
    $sp_au_key = $_POST['authentication_key'];
    $sp_acc = $_POST['world_wide_express_account_number'];
    $sp_licence_key = $_POST['speed_freight_licence_key'];
    $sp_client_id = (isset($_POST['client_id'])) ? sanitize_text_field($_POST['client_id']) : '';
    $sp_client_secret = (isset($_POST['client_secret'])) ? sanitize_text_field($_POST['client_secret']) : '';

    $domain = wwe_quests_get_domain();

    $post = array(
        'plugin_licence_key' => $sp_licence_key,
        'plugin_domain_name' => ltl_speedfreight_parse_url($domain),
        'speed_freight_username' => $sp_user,
        'speed_freight_password' => $sp_pass
    );

    if (isset($_POST['api_end_point']) && $_POST['api_end_point'] == 'wwe_ltl_new_api') {
        $post['ApiVersion'] = '2.0';
        $post['clientId'] = $sp_client_id;
        $post['clientSecret'] = $sp_client_secret;
    } else {
        $post['world_wide_express_account_number'] = $sp_acc;
        $post['authentication_key'] = $sp_au_key;
    }
    
    $output = array();
    if (is_array($post) && count($post) > 0) {

        $ltl_curl_obj = new WWE_LTL_Curl_Request();
        $output = $ltl_curl_obj->wwe_ltl_get_curl_response(WWE_FREIGHT_DOMAIN_HITTING_URL . '/carriers/wwe-freight/speedfreightTest.php', $post);
        $output = isset($output) & !empty($output) ? $output : array();
    }
    print_r($output);
    die;
}

/**
 * URL parsing
 * @param $domain
 * @return url
 */
function ltl_speedfreight_parse_url($domain)
{

    $domain = trim($domain);
    $parsed = parse_url($domain);
    if (empty($parsed['scheme'])) {

        $domain = 'http://' . ltrim($domain, '/');
    }
    $parse = parse_url($domain);
    $refinded_domain_name = $parse['host'];
    $domain_array = explode('.', $refinded_domain_name);
    if (in_array('www', $domain_array)) {

        $key = array_search('www', $domain_array);
        unset($domain_array[$key]);
        if(phpversion() < 8) {
            $refinded_domain_name = implode($domain_array, '.'); 
        }else {
            $refinded_domain_name = implode('.', $domain_array);
        }
    }
    return $refinded_domain_name;
}
