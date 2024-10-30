<?php

/**
 * WWE LTL Database
 *
 * @package     WWE LTL Quotes
 * @author      Eniture-Technology
 */
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
function create_carriers_db($network_wide = null)
{
    global $wpdb;
    $old_table = "wp_freights";
    $new_table = $wpdb->prefix . "wp_freights";
    if ($wpdb->query("SHOW TABLES LIKE '" . $old_table . "'") != 0) {
        $wpdb->query("RENAME TABLE " . $old_table . " TO " . $new_table);
    }
    if (is_multisite() && $network_wide) {
        foreach (get_sites(['fields' => 'ids']) as $blog_id) {
            switch_to_blog($blog_id);
            global $wpdb;
            $carrier_table = $wpdb->prefix . "wp_freights";
            if ($wpdb->query("SHOW TABLES LIKE '" . $carrier_table . "'") === 0) {
                $sql = 'CREATE TABLE ' . $carrier_table . '(
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `speed_freight_shipmentQuoteId` varchar(600) NOT NULL,
                `speed_freight_carrierSCAC` varchar(600) NOT NULL,
                `speed_freight_carrierName` varchar(600) NOT NULL,
                `speed_freight_transitDays` varchar(600) NOT NULL,
                `speed_freight_guaranteedService` varchar(600) NOT NULL,
                `speed_freight_highCostDeliveryShipment` varchar(600) NOT NULL,
                `speed_freight_interline` varchar(600) NOT NULL,
                `speed_freight_nmfcRequired` varchar(600) NOT NULL,
                `speed_freight_carrierNotifications` varchar(600) NOT NULL,
                `carrier_logo` varchar(255) NOT NULL,
                `carrier_status` varchar(8) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

                dbDelta($sql);
            }
            restore_current_blog();
        }

    } else {
        global $wpdb;
        $carrier_table = $wpdb->prefix . "wp_freights";
        if ($wpdb->query("SHOW TABLES LIKE '" . $carrier_table . "'") === 0) {
            $sql = 'CREATE TABLE ' . $carrier_table . '(
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `speed_freight_shipmentQuoteId` varchar(600) NOT NULL,
                `speed_freight_carrierSCAC` varchar(600) NOT NULL,
                `speed_freight_carrierName` varchar(600) NOT NULL,
                `speed_freight_transitDays` varchar(600) NOT NULL,
                `speed_freight_guaranteedService` varchar(600) NOT NULL,
                `speed_freight_highCostDeliveryShipment` varchar(600) NOT NULL,
                `speed_freight_interline` varchar(600) NOT NULL,
                `speed_freight_nmfcRequired` varchar(600) NOT NULL,
                `speed_freight_carrierNotifications` varchar(600) NOT NULL,
                `carrier_logo` varchar(255) NOT NULL,
                `carrier_status` varchar(8) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

            dbDelta($sql);
        }
    }
}

/**
 * Create Warehouse Table
 * @global $wpdb
 */
function create_ltl_wh_db($network_wide = null)
{
    if (is_multisite() && $network_wide) {

        foreach (get_sites(['fields' => 'ids']) as $blog_id) {
            switch_to_blog($blog_id);
            global $wpdb;
            $warehouse_table = $wpdb->prefix . "warehouse";
            if ($wpdb->query("SHOW TABLES LIKE '" . $warehouse_table . "'") === 0) {
                $origin = 'CREATE TABLE ' . $warehouse_table . '(
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    city varchar(200) NOT NULL,
                    state varchar(200) NOT NULL,
                    address varchar(255) NOT NULL,
                    phone_instore varchar(255) NOT NULL,
                    zip varchar(200) NOT NULL,
                    country varchar(200) NOT NULL,
                    location varchar(200) NOT NULL,
                    nickname varchar(200) NOT NULL,
                    enable_store_pickup VARCHAR(255) NOT NULL,
                    miles_store_pickup VARCHAR(255) NOT NULL ,
                    match_postal_store_pickup VARCHAR(255) NOT NULL ,
                    checkout_desc_store_pickup VARCHAR(255) NOT NULL ,
                    fee_store_pickup VARCHAR(10) NOT NULL ,
                    enable_local_delivery VARCHAR(255) NOT NULL ,
                    miles_local_delivery VARCHAR(255) NOT NULL ,
                    match_postal_local_delivery VARCHAR(255) NOT NULL ,
                    checkout_desc_local_delivery VARCHAR(255) NOT NULL ,
                    fee_local_delivery VARCHAR(255) NOT NULL ,
                    suppress_local_delivery VARCHAR(255) NOT NULL,
                    wwe_correct_city VARCHAR(100) NOT NULL,
                    origin_markup VARCHAR(255),    
                    PRIMARY KEY  (id) )';
                dbDelta($origin);
            }

            $enable_store_pickup_col = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'enable_store_pickup'");
            if (!(isset($enable_store_pickup_col->Field) && $enable_store_pickup_col->Field == 'enable_store_pickup')) {
                $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN enable_store_pickup VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN miles_store_pickup VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN match_postal_store_pickup VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN checkout_desc_store_pickup VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN fee_store_pickup VARCHAR(10) NOT NULL , "
                    . "ADD COLUMN enable_local_delivery VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN miles_local_delivery VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN match_postal_local_delivery VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN checkout_desc_local_delivery VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN fee_local_delivery VARCHAR(255) NOT NULL , "
                    . "ADD COLUMN suppress_local_delivery VARCHAR(255) NOT NULL", $warehouse_table));
            }

            $wwe_correct_city = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'wwe_correct_city'");
            if (!(isset($wwe_correct_city->Field) && $wwe_correct_city->Field == 'wwe_correct_city')) {
                $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN wwe_correct_city VARCHAR(100) NOT NULL", $warehouse_table));
            }

            $wwe_origin_markup = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'origin_markup'");
            if (!(isset($wwe_origin_markup->Field) && $wwe_origin_markup->Field == 'origin_markup')) {
                $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN origin_markup VARCHAR(255) NOT NULL", $warehouse_table));
            }

            // Origin terminal address
            wwe_ltl_update_warehouse();
            restore_current_blog();
        }

    } else {
        global $wpdb;
        $warehouse_table = $wpdb->prefix . "warehouse";
        if ($wpdb->query("SHOW TABLES LIKE '" . $warehouse_table . "'") === 0) {
            $origin = 'CREATE TABLE ' . $warehouse_table . '(
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    city varchar(200) NOT NULL,
                    state varchar(200) NOT NULL,
                    address varchar(255) NOT NULL,
                    phone_instore varchar(255) NOT NULL,
                    zip varchar(200) NOT NULL,
                    country varchar(200) NOT NULL,
                    location varchar(200) NOT NULL,
                    nickname varchar(200) NOT NULL,
                    enable_store_pickup VARCHAR(255) NOT NULL,
                    miles_store_pickup VARCHAR(255) NOT NULL ,
                    match_postal_store_pickup VARCHAR(255) NOT NULL ,
                    checkout_desc_store_pickup VARCHAR(255) NOT NULL ,
                    fee_store_pickup VARCHAR(10) NOT NULL ,
                    enable_local_delivery VARCHAR(255) NOT NULL ,
                    miles_local_delivery VARCHAR(255) NOT NULL ,
                    match_postal_local_delivery VARCHAR(255) NOT NULL ,
                    checkout_desc_local_delivery VARCHAR(255) NOT NULL ,
                    fee_local_delivery VARCHAR(255) NOT NULL ,
                    suppress_local_delivery VARCHAR(255) NOT NULL,
                    wwe_correct_city VARCHAR(100) NOT NULL,
                    origin_markup VARCHAR(255),   
                    PRIMARY KEY  (id) )';
            dbDelta($origin);
        }

        $enable_store_pickup_col = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'enable_store_pickup'");
        if (!(isset($enable_store_pickup_col->Field) && $enable_store_pickup_col->Field == 'enable_store_pickup')) {
            $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN enable_store_pickup VARCHAR(255) NOT NULL , "
                . "ADD COLUMN miles_store_pickup VARCHAR(255) NOT NULL , "
                . "ADD COLUMN match_postal_store_pickup VARCHAR(255) NOT NULL , "
                . "ADD COLUMN checkout_desc_store_pickup VARCHAR(255) NOT NULL , "
                . "ADD COLUMN fee_store_pickup VARCHAR(10) NOT NULL , "
                . "ADD COLUMN enable_local_delivery VARCHAR(255) NOT NULL , "
                . "ADD COLUMN miles_local_delivery VARCHAR(255) NOT NULL , "
                . "ADD COLUMN match_postal_local_delivery VARCHAR(255) NOT NULL , "
                . "ADD COLUMN checkout_desc_local_delivery VARCHAR(255) NOT NULL , "
                . "ADD COLUMN fee_local_delivery VARCHAR(255) NOT NULL , "
                . "ADD COLUMN suppress_local_delivery VARCHAR(255) NOT NULL", $warehouse_table));
        }

        $wwe_correct_city = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'wwe_correct_city'");
        if (!(isset($wwe_correct_city->Field) && $wwe_correct_city->Field == 'wwe_correct_city')) {
            $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN wwe_correct_city VARCHAR(100) NOT NULL", $warehouse_table));
        }

        $wwe_origin_markup = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'origin_markup'");
        if (!(isset($wwe_origin_markup->Field) && $wwe_origin_markup->Field == 'origin_markup')) {
            $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN origin_markup VARCHAR(255) NOT NULL", $warehouse_table));
        }

        // Origin terminal address
        wwe_ltl_update_warehouse();
    }

}

/**
 * Update warehouse
 */
function wwe_ltl_update_warehouse()
{
    // Origin terminal address
    // Terminal phone number
    global $wpdb;
    $warehouse_table = $wpdb->prefix . "warehouse";
    $warehouse_address = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'phone_instore'");
    if (!(isset($warehouse_address->Field) && $warehouse_address->Field == 'phone_instore')) {
        $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN address VARCHAR(255) NOT NULL", $warehouse_table));
        $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN phone_instore VARCHAR(255) NOT NULL", $warehouse_table));
    }

    // instore pickup delivery fee
    $instore_pickup_fee = $wpdb->get_row("SHOW COLUMNS FROM " . $warehouse_table . " LIKE 'fee_store_pickup'");
    if (!(isset($instore_pickup_fee->Field) && $instore_pickup_fee->Field == 'fee_store_pickup')) {
        $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN fee_store_pickup VARCHAR(10) NOT NULL", $warehouse_table));
    }
}

/**
 * Install Carriers On Activation
 */
function ltl_freihgt_installation_carrier($network_wide = null)
{
    if (is_multisite() && $network_wide) {

        foreach (get_sites(['fields' => 'ids']) as $blog_id) {
            switch_to_blog($blog_id);
            $carriers_obj = new wwe_ltl_carriers();
            $create_class_obj = new wwe_ltl_carriers();
            $carriers_obj->carriers();
            if (!function_exists('create_ltl_class')) {
                $create_class_obj->create_ltl_class();
            }
            restore_current_blog();
        }

    } else {
        $carriers_obj = new wwe_ltl_carriers();
        $create_class_obj = new wwe_ltl_carriers();
        $carriers_obj->carriers();
        if (!function_exists('create_ltl_class')) {
            $create_class_obj->create_ltl_class();
        }
    }
}

/**
 *
 * Update warehouse/dropship correct city for WWE
 *
 */
function wwe_ltl_get_all_warehouse_dropship($address = NULL, $network_wide = null)
{
    if (is_multisite() && $network_wide) {

        foreach (get_sites(['fields' => 'ids']) as $blog_id) {
            switch_to_blog($blog_id);
            global $wpdb;
            $addresses = $wpdb->get_results("SELECT id, city as senderCity, state as senderState, zip as senderZip, country as senderCountryCode FROM " . $wpdb->prefix . "warehouse", ARRAY_A);
            $addresses = (isset($address) && !empty($address)) ? $address : $addresses;

            $update_status = isset($address['update_status']) ? $address['update_status'] : '';
            if (isset($addresses) && !empty($addresses)) {

                $domain = wwe_quests_get_domain();
                $api_credentials = array(
                    'username' => get_option('wc_settings_wwe_speed_freight_username'),
                    'password' => get_option('wc_settings_wwe_speed_freight_password'),
                    'account_number' => get_option('wc_settings_wwe_world_wide_express_account_number'),
                    'authentication_key' => get_option('wc_settings_wwe_authentication_key'),
                );

                $postData = array(
                    'acessLevel' => 'wweOriginValidate',
                    'carrier' => 'LTL',
                    'address' => $addresses,
                    'api' => $api_credentials,
                    'eniureLicenceKey' => get_option('wc_settings_wwe_licence_key'),
                    'ServerName' => $domain,
                );

                $field_string = http_build_query($postData);
                $response = wp_remote_post(WWE_FREIGHT_DOMAIN_HITTING_URL . '/addon/google-location.php', array(
                        'method' => 'POST',
                        'timeout' => 60,
                        'redirection' => 5,
                        'blocking' => true,
                        'body' => $field_string,
                    )
                );

                $output = wp_remote_retrieve_body($response);

                if (isset($output) && !empty($output)) {

                    $response = json_decode($output);
                    $error_status = (isset($response->error) && !empty($response->error)) ? $response->error : '';
                    if (empty($error_status)) {

                        foreach ($response as $id => $address) {
//                  if warehouse / dropship is updated then unset wwe corrected city
                            if (isset($update_status) && !empty($update_status) && $update_status == 1) {
                                $data = array('wwe_correct_city' => '');
                                $wpdb->update($wpdb->prefix . 'warehouse', $data, array('id' => $id));
                            }

                            if ($address->severity == 'ERROR' && isset($address->validCity)) {
                                $data = array('wwe_correct_city' => $address->validCity);
                                $wpdb->update($wpdb->prefix . 'warehouse', $data, array('id' => $id));
                            }
                        }
                    }
                }
            }
            restore_current_blog();
        }

    } else {
        global $wpdb;
        $addresses = $wpdb->get_results("SELECT id, city as senderCity, state as senderState, zip as senderZip, country as senderCountryCode FROM " . $wpdb->prefix . "warehouse", ARRAY_A);
        $addresses = (isset($address) && !empty($address)) ? $address : $addresses;

        $update_status = isset($address['update_status']) ? $address['update_status'] : '';
        if (isset($addresses) && !empty($addresses)) {

            $domain = wwe_quests_get_domain();
            $api_credentials = array(
                'username' => get_option('wc_settings_wwe_speed_freight_username'),
                'password' => get_option('wc_settings_wwe_speed_freight_password'),
                'account_number' => get_option('wc_settings_wwe_world_wide_express_account_number'),
                'authentication_key' => get_option('wc_settings_wwe_authentication_key'),
            );

            $postData = array(
                'acessLevel' => 'wweOriginValidate',
                'carrier' => 'LTL',
                'address' => $addresses,
                'api' => $api_credentials,
                'eniureLicenceKey' => get_option('wc_settings_wwe_licence_key'),
                'ServerName' => $domain,
            );

            $field_string = http_build_query($postData);
            $response = wp_remote_post(WWE_FREIGHT_DOMAIN_HITTING_URL . '/addon/google-location.php', array(
                    'method' => 'POST',
                    'timeout' => 60,
                    'redirection' => 5,
                    'blocking' => true,
                    'body' => $field_string,
                )
            );

            $output = wp_remote_retrieve_body($response);

            if (isset($output) && !empty($output)) {

                $response = json_decode($output);
                $error_status = (isset($response->error) && !empty($response->error)) ? $response->error : '';
                if (empty($error_status)) {

                    foreach ($response as $id => $address) {
//                  if warehouse / dropship is updated then unset wwe corrected city
                        if (isset($update_status) && !empty($update_status) && $update_status == 1) {
                            $data = array('wwe_correct_city' => '');
                            $wpdb->update($wpdb->prefix . 'warehouse', $data, array('id' => $id));
                        }

                        if ($address->severity == 'ERROR' && isset($address->validCity)) {
                            $data = array('wwe_correct_city' => $address->validCity);
                            $wpdb->update($wpdb->prefix . 'warehouse', $data, array('id' => $id));
                        }
                    }
                }
            }
        }
    }

}

/**
 * Create shipping rules database table
 */
function create_wwe_ltl_shipping_rules_db($network_wide = null)
{
    if ( is_multisite() && $network_wide ) {

        foreach (get_sites(['fields'=>'ids']) as $blog_id) {
            switch_to_blog($blog_id);
            global $wpdb;
            $shipping_rules_table = $wpdb->prefix . "eniture_wwe_ltl_shipping_rules";

            if ($wpdb->query("SHOW TABLES LIKE '" . $shipping_rules_table . "'") === 0) {
                $query = 'CREATE TABLE ' . $shipping_rules_table . '(
                    id INT(10) NOT NULL AUTO_INCREMENT,
                    name VARCHAR(50) NOT NULL,
                    type VARCHAR(30) NOT NULL,
                    settings TEXT NULL,
                    is_active TINYINT(1) NOT NULL,
                    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                )';

                dbDelta($query);
            }

            restore_current_blog();
        }

    } else {
        global $wpdb;
        $shipping_rules_table = $wpdb->prefix . "eniture_wwe_ltl_shipping_rules";

        if ($wpdb->query("SHOW TABLES LIKE '" . $shipping_rules_table . "'") === 0) {
            $query = 'CREATE TABLE ' . $shipping_rules_table . '(
                id INT(10) NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                type VARCHAR(30) NOT NULL,
                settings TEXT NULL,
                is_active TINYINT(1) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id) 
            )';

            dbDelta($query);
        }
    }
}