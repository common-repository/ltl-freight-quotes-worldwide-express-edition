<?php
/**
 * WWE LTL Distance Get
 *
 * @package     WWE LTL Quotes
 * @author      Eniture-Technology
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Get_ltl_distance
 */
class Get_ltl_distance
{
    /**
     * Get Distance Function
     * @param $map_address
     * @param $accessLevel
     * @return json
     */
    function ltl_get_distance($map_address, $accessLevel, $destinationZip = [])
    {

        $domain = wwe_quests_get_domain();
        $post = array(
            'acessLevel' => $accessLevel,
            'address' => $map_address,
            'originAddresses' => (isset($map_address)) ? $map_address : "",
            'destinationAddress' => (isset($destinationZip)) ? $destinationZip : "",
            'eniureLicenceKey' => get_option('wc_settings_wwe_licence_key'),
            'ServerName' => $domain,
        );


        if (is_array($post) && count($post) > 0) {

            $ltl_curl_obj = new WWE_LTL_Curl_Request();
            $output = $ltl_curl_obj->wwe_ltl_get_curl_response(WWE_FREIGHT_DOMAIN_HITTING_URL . '/addon/google-location.php', $post);
            return $output;
        }
    }
}
