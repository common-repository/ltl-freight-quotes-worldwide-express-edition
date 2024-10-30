<?php

/**
 * WWE LTL Shipping Class
 *
 * @package     WWE LTL Quotes
 * @author      Eniture-Technology
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WWE LTL Shipping Method Init
 */
function ltl_shipping_method_init()
{

    if (!class_exists('WC_speedfreight_Shipping_Method')) {

        /**
         * Class WC_speedfreight_Shipping_Method
         */
        class WC_speedfreight_Shipping_Method extends WC_Shipping_Method
        {
            public $forceAllowShipMethodWwe = [];
            public $getPkgObjWwe;
            public $Wwe_Ltl_Liftgate_As_Option;
            public $ltl_res_inst;
            public $quote_settings;
            public $instore_pickup_and_local_delivery;
            public $InstorPickupLocalDelivery;
            public $group_small_shipments;
            public $web_service_inst;
            public $package_plugin;
            public $woocommerce_package_rates;
            public $shipment_type;
            public $minPrices;
            public $minPrices_liftgate;
            // FDO
            public $en_fdo_meta_data = [];
            public $en_fdo_meta_data_third_party = [];
            public $en_fdo_meta_data_limited = [];
            public $en_fdo_meta_data_limited_liftgate = [];
            public $minPrices_limited = [];
            public $minPrices_limited_liftgate = [];
            public $composite = false;
            public $la_label_sufex;
            public $la_label_append;
            public $lad_label;
            public $lad_append_label;
            public $la_label;
            public $la_append_label;
            public $limited_access_cost;
            public $limited_cost;
            public $limited_cost_liftgate;
            public $la_label_liftgate;
            public $la_label_append_liftgate;

            /**
             * smpkgFoundErr
             * @var array type
             */
            public $smpkgFoundErr = [];

            /**
             * Constructor
             * @param $instance_id
             */
            public function __construct($instance_id = 0)
            {
                $this->id = 'ltl_shipping_method';
                $this->instance_id = absint($instance_id);
                $this->method_title = __('WWE LTL Freight Quotes');
                $this->method_description = __('Real-time LTL freight quotes from Worldwide Express.');
                $this->supports = array(
                    'shipping-zones',
                    'instance-settings',
                    'instance-settings-modal',
                );
                $this->enabled = "yes";
                $this->title = "LTL Freight Quotes - Worldwide Express Edition";
                $this->init();

                $this->Wwe_Ltl_Liftgate_As_Option = new Wwe_Ltl_Liftgate_As_Option();
            }

            /**
             * Initialization
             */
            function init()
            {

                $this->init_form_fields();
                $this->init_settings();
                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }

            /**
             * Form Fields
             */
            public function init_form_fields()
            {

                $this->instance_form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable / Disable', 'woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Enable This Shipping Service', 'woocommerce'),
                        'default' => 'yes',
                        'id' => 'speed_freight_enable_disable_shipping'
                    )
                );
            }

            /**
             * Third party quotes
             * @param type $forceShowMethods
             * @return type
             */
            public function forceAllowShipMethodWwe($forceShowMethods)
            {
                if (!empty($this->getPkgObjWwe->ValidShipmentsArr) && (!in_array("ltl_freight", $this->getPkgObjWwe->ValidShipmentsArr))) {
                    $this->forceAllowShipMethodWwe[] = "free_shipping";
                    $this->forceAllowShipMethodWwe[] = "valid_third_party";
                } else {

                    $this->forceAllowShipMethodWwe[] = "ltl_shipment";
                }

                $forceShowMethods = array_merge($forceShowMethods, $this->forceAllowShipMethodWwe);
                return $forceShowMethods;
            }

            /**
             * Virtual Products
             */
            public function en_virtual_products()
            {
                global $woocommerce;
                $products = $woocommerce->cart->get_cart();
                $items = $product_name = [];
                foreach ($products as $key => $product_obj) {
                    $product = $product_obj['data'];
                    $is_virtual = $product->get_virtual();
                    if(isset($product->product_type) && $product->product_type == 'composite'){
                        $this->composite = true;
                    }
                    if ($is_virtual == 'yes') {
                        $attributes = $product->get_attributes();
                        $product_qty = $product_obj['quantity'];
                        $product_title = str_replace(array("'", '"'), '', $product->get_title());
                        $product_name[] = $product_qty . " x " . $product_title;

                        $meta_data = [];
                        if (!empty($attributes)) {
                            foreach ($attributes as $attr_key => $attr_value) {
                                $meta_data[] = [
                                    'key' => $attr_key,
                                    'value' => $attr_value,
                                ];
                            }
                        }

                        $items[] = [
                            'id' => $product_obj['product_id'],
                            'name' => $product_title,
                            'quantity' => $product_qty,
                            'price' => $product->get_price(),
                            'weight' => 0,
                            'length' => 0,
                            'width' => 0,
                            'height' => 0,
                            'type' => 'virtual',
                            'product' => 'virtual',
                            'sku' => $product->get_sku(),
                            'attributes' => $attributes,
                            'variant_id' => 0,
                            'meta_data' => $meta_data,
                        ];
                    }
                }

                $virtual_rate = [];

                if (!empty($items)) {
                    $virtual_rate = [
                        'id' => 'en_virtual_rate',
                        'label' => 'Virtual Quote',
                        'cost' => 0,
                    ];

                    $virtual_fdo = [
                        'plugin_type' => 'ltl',
                        'plugin_name' => 'wwe_quests',
                        'accessorials' => '',
                        'items' => $items,
                        'address' => '',
                        'handling_unit_details' => '',
                        'rate' => $virtual_rate,
                    ];

                    $meta_data = [
                        'sender_origin' => 'Virtual Product',
                        'product_name' => wp_json_encode($product_name),
                        'en_fdo_meta_data' => $virtual_fdo,
                    ];

                    $virtual_rate['meta_data'] = $meta_data;

                }
                if($this->composite == true){
                    $virtual_rate['composite'] = 'yes';
                }
                return $virtual_rate;
            }

            /**
             * Calculate Handeling Fee For Each Shipment
             * @param $handeling_fee
             * @param $total
             * @return int
             */
            function calculate_markup($total, $pricing_per_product)
            {
                // Pricing per product
                $en_pricing_per_product = apply_filters('en_pricing_per_product_existence', false);
                if (!$en_pricing_per_product) {
                    return $total;
                }

                $handeling_fee = 0;
                $product_quantity = 1;
                $product_rental = 'no';
                if (!empty($pricing_per_product)) {
                    foreach ($pricing_per_product as $key => $per_product) {
                        $handeling_fee = (isset($per_product['product_markup'])) ? $per_product['product_markup'] : 0;
                        $product_quantity = (isset($per_product['product_quantity'])) ? $per_product['product_quantity'] : 0;
                        $product_rental = (isset($per_product['product_rental'])) ? $per_product['product_rental'] : 'no';
                    }
                }

                $handeling_fee = isset($handeling_fee) && $handeling_fee > 0 ? $handeling_fee : 0;
                $handeling_fee = !$total > 0 ? 0 : $handeling_fee;
                $grandTotal = 0;
                if (floatval($handeling_fee)) {
                    $pos = strpos($handeling_fee, '%');
                    if ($pos > 0) {
                        $rest = substr($handeling_fee, $pos);
                        $exp = explode($rest, $handeling_fee);
                        $get = $exp[0];
                        $percnt = $get / 100 * $total;
                        $handeling_fee = $percnt;
                    }
                }

                if ($product_rental == 'yes') {
                    $total_fee = ((float)$total + (float)$handeling_fee) * 2;
                } else {
                    $total_fee = (float)$total + (float)$handeling_fee;
                }

                return $total_fee;
            }

            /**
             * Calculate Shipping
             * @param $package
             * @global $current_user
             * @global $wpdb
             */
            public function calculate_shipping($package = [], $eniture_admin_order_action = false)
            {
                if (is_admin() && !wp_doing_ajax() && !$eniture_admin_order_action) {
                    return [];
                }

                // Backup rates
                if (get_option('wc_pervent_proceed_checkout_eniture') == 'backup_rates') {
                    $rate = array(
                        'id' => $this->id . ':' . 'backup_rates',
                        'label' => get_option('eniture_backup_rates'),
                        'cost' => get_option('eniture_backup_rates_amount'),
                        'plugin_name' => 'wweLtl',
                        'plugin_type' => 'ltl',
                        'owned_by' => 'eniture'
                    );

                    $this->add_rate($rate);
                }

                $this->package_plugin = get_option('wwe_ltl_packages_quotes_package');

                $this->instore_pickup_and_local_delivery = FALSE;

                // Eniture debug mood
                do_action("eniture_error_messages", "Errors");

                $changObj = new Woo_Update_Changes();
                $freight_zipcode = (strlen(WC()->customer->get_shipping_postcode()) > 0) ? WC()->customer->get_shipping_postcode() : $changObj->speedfreight_postcode();

                $coupn = WC()->cart->get_coupons();
                if (isset($coupn) && !empty($coupn)) {
                    $freeShipping = $this->wweLTLFreeShipping($coupn);
                    if ($freeShipping == 'y')
                        return FALSE;
                }

                $this->create_speedfreight_ltl_option();
                global $wpdb;
                global $current_user;
                $sandbox = "";
                $quotes = [];
                $smallQuotes = [];
                $rate = [];
                $own_freight = [];

                $smallPackages = false;

                $allowArrangements = get_option('wc_settings_wwe_allow_for_own_arrangment');
                $ltl_res_inst = new ltl_shipping_get_quotes();
                $this->ltl_res_inst = $ltl_res_inst;
                $this->web_service_inst = $ltl_res_inst;

                $this->ltl_shipping_quote_settings();

//                  Eniture debug mood
                do_action("eniture_debug_mood", "Quote Settings", $this->ltl_res_inst->quote_settings);

                if (isset($this->ltl_res_inst->quote_settings['handling_fee']) &&
                    ($this->ltl_res_inst->quote_settings['handling_fee'] == "-100%")) {
                        $rates = array(
                            'id' => 'free',
                            'label' => 'Free Shipping',
                            'cost' => 0,
                            'plugin_name' => 'wweLtl',
                            'plugin_type' => 'ltl',
                            'owned_by' => 'eniture'
                        );
                        $this->add_rate($rates);
                        
                        return [];
                }

                $group_ltl_shipments = new group_ltl_shipments();
                $this->getPkgObjWwe = $group_ltl_shipments;

                $ltl_package = $group_ltl_shipments->ltl_package_shipments($package, $ltl_res_inst, $freight_zipcode);
                // Apply Hide Methods Shipping Rules
                $shipping_rule_obj = new EnWweLtlShippingRulesAjaxReq();
                $shipping_rules_applied = $shipping_rule_obj->apply_shipping_rules($ltl_package);
                if ($shipping_rules_applied) {
                    return [];
                }
 
                $ltl_res_inst->prevent_resi_available_in_cart = $group_ltl_shipments->prevent_resi_available_in_cart;
                // pricing_per_product
                $pricing_product_origins = [];
                if(isset($ltl_package['pricing_product_origins'])){
                    $pricing_product_origins = $ltl_package['pricing_product_origins'];
                    unset($ltl_package['pricing_product_origins']);
                }

                // Crowler work
                $ltl_package = apply_filters('en_check_sbs_packaging', $ltl_package);

                if (isset($ltl_package['warehouse_origin']))
                    unset($ltl_package['warehouse_origin']);


                add_filter('force_show_methods', array($this, 'forceAllowShipMethodWwe'));

                $no_param_multi_ship = 0;

                if (is_array($ltl_package) && count($ltl_package) > 1) {
                    foreach ($ltl_package as $key => $value) {
                        if (isset($value["NOPARAM"]) && $value["NOPARAM"] === 1 && empty($value["items"])) {
                            $no_param_multi_ship = 1;
                            unset($ltl_package[$key]);
                        }
                    }
                }

                $eniturePluigns = json_decode(get_option('EN_Plugins'));
                $calledMethod = [];
                $smallPluginExist = FALSE;

                if (!empty($ltl_package)) {

                    $ltl_products = $small_products = [];
                    foreach ($ltl_package as $key => $sPackage) {
                        if (array_key_exists('ltl', $sPackage)) {
                            $ltl_products[] = $sPackage;
                            $web_service_arr = $ltl_res_inst->ltl_shipping_get_web_service_array($sPackage, $this->package_plugin);
                            $response = $ltl_res_inst->ltl_shipping_get_web_quotes($web_service_arr, $ltl_package, $key);
                            
                            if (empty($response)) {
                                return [];
                            }

                            (!empty($response)) ? $quotes[$key] = $response : "";
                            continue;
                        } elseif (array_key_exists('small', $sPackage)) {
                            $sPackage['is_shipment'] = 'small';
                            $small_products[] = $sPackage;
                        }
                    }

                    if (isset($small_products) && !empty($small_products) && !empty($ltl_products)) {
                        foreach ($eniturePluigns as $enIndex => $enPlugin) {
                            $freightSmallClassName = 'WC_' . $enPlugin;
                            if (!in_array($freightSmallClassName, $calledMethod)) {
                                if (class_exists($freightSmallClassName)) {
                                    $smallPluginExist = TRUE;
                                    $SmallClassNameObj = new $freightSmallClassName();
                                    $package['itemType'] = 'ltl';
                                    $package['sPackage'] = $small_products;
                                    $smallQuotesResponse = $SmallClassNameObj->calculate_shipping($package, true);
                                    $smallQuotes[] = $smallQuotesResponse;
                                }
                                $calledMethod[] = $freightSmallClassName;
                            }
                        }
                    }
                }


                $smallQuotes = (is_array($smallQuotes) && (!empty($smallQuotes))) ? reset($smallQuotes) : $smallQuotes;
                $smallMinRate = (is_array($smallQuotes) && (!empty($smallQuotes))) ? current($smallQuotes) : $smallQuotes;

                // Virtual products
                $virtual_rate = $this->en_virtual_products();

                // FDO
                if (isset($smallMinRate['meta_data']['en_fdo_meta_data'])) {
                    if (!empty($smallMinRate['meta_data']['en_fdo_meta_data']) && !is_array($smallMinRate['meta_data']['en_fdo_meta_data'])) {
                        $en_third_party_fdo_meta_data = json_decode($smallMinRate['meta_data']['en_fdo_meta_data'], true);
                        isset($en_third_party_fdo_meta_data['data']) ? $smallMinRate['meta_data']['en_fdo_meta_data'] = $en_third_party_fdo_meta_data['data'] : '';
                    }
                    $this->en_fdo_meta_data_third_party = (isset($smallMinRate['meta_data']['en_fdo_meta_data']['address'])) ? [$smallMinRate['meta_data']['en_fdo_meta_data']] : $smallMinRate['meta_data']['en_fdo_meta_data'];
                }

                $smpkgCost = (isset($smallMinRate['cost'])) ? $smallMinRate['cost'] : 0;

                if (isset($smallMinRate) && (!empty($smallMinRate))) {
                    switch (TRUE) {
                        case (isset($smallMinRate['minPrices'])):
                            $small_quotes = $smallMinRate['minPrices'];
                            break;
                        default :
                            $shipment_zipcode = key($smallQuotes);
                            $small_quotes = array($shipment_zipcode => $smallMinRate);
                            break;
                    }
                }

                if (isset($quotes) && (empty($quotes))) {
                    return [];
                }

                $this->minPrices = [];
                $this->minPrices_liftgate = [];
                $this->quote_settings = $this->ltl_res_inst->quote_settings;
                // Excluded accessorials
                $this->quote_settings = $this->ltl_res_inst->recent_quote_settings;
                $this->quote_settings = json_decode(json_encode($this->quote_settings), true);
                $quotes = json_decode(json_encode($quotes), true);
                $handling_fee = $this->quote_settings['handling_fee'];

                do_action("eniture_debug_mood", "WWE LTL Eniture Quotes Rates", $quotes);
                do_action("eniture_debug_mood", "WWE LTL Plus Small Eniture Quotes Rates", $smpkgCost);

                $Ltl_Freight_Quotes = new Ltl_Freight_Quotes();

                $rates = [];
                // Virtual products
                $composite = isset($virtual_rate['composite']) ? $virtual_rate['composite'] : '';

                $this->la_label_sufex = ['LA'];
                $this->la_label_append = ' with limited access delivery ';
                // limited access
                $limited_access_delivery_class = new LimitedAccessDelivery();

                if ((count($quotes) > 1 || $smpkgCost > 0) || $no_param_multi_ship == 1 || (!empty($virtual_rate) && ($composite != 'yes'))) {
                    $multi_cost = 0;
                    $s_multi_cost = 0;
                    $this->lad_label = '';
                    $this->lad_append_label = '';
                    $this->la_label = '';
                    $this->la_append_label = '';
                    $this->limited_access_cost = 0;
                    $this->limited_cost = 0;
                    $this->limited_cost_liftgate = 0;
                    $this->la_label_liftgate = '';
                    $this->la_label_append_liftgate = '';
                    $_label = "";
                    $hold_at_terminal_fee = 0;
//                  Custom client work "ltl_remove_small_minimum_value_By_zero_when_coupon_add"
                    if (has_filter('small_min_remove_zero_type_params')) {
                        $smpkgCost = apply_filters('small_min_remove_zero_type_params', $package, $smpkgCost);
                    }

                    $this->quote_settings['shipment'] = "multi_shipment";

                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['WWE_LIFT'] = $small_quotes : "";
                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['WWE_NOTLIFT'] = $small_quotes : "";
                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['WWE_HAT'] = $small_quotes : "";

                    // Virtual products
                    if (!empty($virtual_rate)) {
                        $en_virtual_fdo_meta_data[] = $virtual_rate['meta_data']['en_fdo_meta_data'];
                        $virtual_meta_rate['virtual_rate'] = $virtual_rate;
                        $this->minPrices['WWE_LIFT'] = isset($this->minPrices['WWE_LIFT']) && !empty($this->minPrices['WWE_LIFT']) ? array_merge($this->minPrices['WWE_LIFT'], $virtual_meta_rate) : $virtual_meta_rate;
                        $this->minPrices['WWE_NOTLIFT'] = isset($this->minPrices['WWE_NOTLIFT']) && !empty($this->minPrices['WWE_NOTLIFT']) ? array_merge($this->minPrices['WWE_NOTLIFT'], $virtual_meta_rate) : $virtual_meta_rate;
                        $this->en_fdo_meta_data_third_party = !empty($this->en_fdo_meta_data_third_party) ? array_merge($this->en_fdo_meta_data_third_party, $en_virtual_fdo_meta_data) : $en_virtual_fdo_meta_data;
                        if ($this->quote_settings['HAT_status'] == 'yes') {
                            $this->minPrices['WWE_HAT'] = isset($this->minPrices['WWE_HAT']) && !empty($this->minPrices['WWE_HAT']) ? array_merge($this->minPrices['WWE_HAT'], $virtual_meta_rate) : $virtual_meta_rate;
                        }
                    }

                    $is_prevent_resi_custom_exists  = apply_filters('en_prevent_resi_custom_addon', false);

                    foreach ($quotes as $index => $quote) {

                        $key = "LTL_" . $index;
//                      Hold At Terminal is enabled

                        if (isset($quote['hold_at_terminal_quotes'])) {
                            $get_hold_at_terminal_quotes = $quote['hold_at_terminal_quotes'];
                            $calculate_hold_at_terminal_quotes = $Ltl_Freight_Quotes->calculate_quotes($get_hold_at_terminal_quotes, $this->quote_settings);
                            $hold_at_terminal_quotes = reset($calculate_hold_at_terminal_quotes);
                            if(isset($hold_at_terminal_quotes['meta_data']['en_fdo_meta_data']['accessorials']) &&
                                is_array($hold_at_terminal_quotes['meta_data']['en_fdo_meta_data']['accessorials'])){
                                $hold_at_terminal_quotes['meta_data']['en_fdo_meta_data']['accessorials']['limited_access'] = false;
                            }

                            $this->minPrices['WWE_HAT'][$key] = $hold_at_terminal_quotes;
                            // custom work
                            if($is_prevent_resi_custom_exists && isset($ltl_package[$index]['packaging_fee_value'])){
                                $hold_at_terminal_quotes['cost'] = apply_filters('en_prevent_resi_add_packaging_fee_to_rates', $hold_at_terminal_quotes['cost'], $ltl_package[$index]['packaging_fee_value']);
                            }

                            // FDO
                            $this->en_fdo_meta_data['WWE_HAT'][$key] = (isset($hold_at_terminal_quotes['meta_data']['en_fdo_meta_data'])) ? $hold_at_terminal_quotes['meta_data']['en_fdo_meta_data'] : [];

                            // Pricing per product
                            $pricing_per_product = (isset($hold_at_terminal_quotes['pricing_per_product'])) ? $hold_at_terminal_quotes['pricing_per_product'] : [];
                            $hold_at_terminal_fee += $this->calculate_markup($hold_at_terminal_quotes['cost'], $pricing_per_product);

                            unset($quote['hold_at_terminal_quotes']);
                            $append_hat_label = (isset($hold_at_terminal_quotes['hat_append_label'])) ? $hold_at_terminal_quotes['hat_append_label'] : "";
                            $append_hat_label = (isset($hold_at_terminal_quotes['_hat_append_label']) && (strlen($append_hat_label) > 0)) ? $append_hat_label . $hold_at_terminal_quotes['_hat_append_label'] : $append_hat_label;
                            $hat_label = [];
                        }

                        $simple_quotes = (isset($quote['simple_quotes'])) ? $quote['simple_quotes'] : [];
                        $quote = $this->remove_array($quote, 'simple_quotes');

                        if((empty($quote) && empty($simple_quotes)) || $ltl_res_inst->restrict_normal_rates){
                            continue;
                        }

                        $rates = $Ltl_Freight_Quotes->calculate_quotes($quote, $this->quote_settings);
                        $rates = reset($rates);

                        // limited access
                        $rates = $limited_access_delivery_class->remove_limited_access_accesorials($rates);
                        
                        $this->minPrices['WWE_LIFT'][$key] = $rates;

                        if($is_prevent_resi_custom_exists && isset($ltl_package[$index]['packaging_fee_value'])){
                            $rates['cost'] = apply_filters('en_prevent_resi_add_packaging_fee_to_rates', $rates['cost'], $ltl_package[$index]['packaging_fee_value']);
                        }

                        // FDO
                        $this->en_fdo_meta_data['WWE_LIFT'][$key] = (isset($rates['meta_data']['en_fdo_meta_data'])) ? $rates['meta_data']['en_fdo_meta_data'] : [];
                        // Pricing per product
                        $pricing_per_product = (isset($rates['pricing_per_product'])) ? $rates['pricing_per_product'] : [];
                        $_cost = (isset($rates['cost'])) ? $rates['cost'] : 0;
                        $_label = (isset($rates['label_sufex'])) ? $rates['label_sufex'] : "";
                        $append_label = (isset($rates['append_label'])) ? $rates['append_label'] : "";
                        $handling_fee = (isset($rates['markup']) && (strlen($rates['markup']) > 0)) ? $rates['markup'] : $handling_fee;

//                          Offer lift gate delivery as an option is enabled
                        if (isset($this->quote_settings['liftgate_delivery_option']) &&
                            ($this->quote_settings['liftgate_delivery_option'] == "yes") &&
                            (!empty($simple_quotes))) {
                            $s_rates = $Ltl_Freight_Quotes->calculate_quotes($simple_quotes, $this->quote_settings);
                            $s_rates = reset($s_rates);
                            $this->minPrices['WWE_NOTLIFT'][$key] = $s_rates;

                            if($is_prevent_resi_custom_exists && isset($ltl_package[$index]['packaging_fee_value'])){
                                $s_rates['cost'] = apply_filters('en_prevent_resi_add_packaging_fee_to_rates', $s_rates['cost'], $ltl_package[$index]['packaging_fee_value']);
                            }

                            // FDO
                            $this->en_fdo_meta_data['WWE_NOTLIFT'][$key] = (isset($s_rates['meta_data']['en_fdo_meta_data'])) ? $s_rates['meta_data']['en_fdo_meta_data'] : [];

                            $s_cost = (isset($s_rates['cost'])) ? $s_rates['cost'] : 0;
                            // Pricing per product
                            $pricing_per_product = (isset($s_rates['pricing_per_product'])) ? $s_rates['pricing_per_product'] : 0;
                            $s_label = (isset($s_rates['label_sufex'])) ? $s_rates['label_sufex'] : "";
                            $s_append_label = (isset($s_rates['append_label'])) ? $s_rates['append_label'] : "";
                            // Pricing per product
                            $s_cost = $this->calculate_markup($s_cost, $pricing_per_product);
                            if (get_option('speed_freight_limited_access_delivery') == "yes" && !empty($quote) &&
                                isset($rates['meta_data']['en_fdo_meta_data']['accessorials']['residential']) && $rates['meta_data']['en_fdo_meta_data']['accessorials']['residential'] != 1) {
                                $s_cost = $s_cost + get_option('speed_freight_limited_access_delivery_fee');
                                $this->en_fdo_meta_data['WWE_NOTLIFT'][$key]['rate']['cost'] = $s_cost;
                                //$rates['meta_data']['en_fdo_meta_data']['rate']['cost'] = $rates['meta_data']['en_fdo_meta_data']['rate']['cost'] + get_option('speed_freight_limited_access_delivery_fee');
                            }

                            // product level markup
                            if(!empty($s_rates['product_level_markup'])){
                                $s_cost = $this->add_handling_fee($s_cost, $s_rates['product_level_markup']);
                            }
                            
                            // origin level markup
                            if(!empty($s_rates['origin_markup'])){
                                $s_cost = $this->add_handling_fee($s_cost, $s_rates['origin_markup']);
                            }

                            $s_multi_cost += $this->add_handling_fee($s_cost, $handling_fee);
                            $this->en_fdo_meta_data['WWE_NOTLIFT'][$key]['rate']['cost'] = $this->add_handling_fee($s_cost, $handling_fee);
                        }
                        // limited access
                        $la_multishipment_array = $limited_access_delivery_class->add_limited_access_multishipment($quote, $simple_quotes, $Ltl_Freight_Quotes, $is_prevent_resi_custom_exists, $key, $index, $this->limited_access_cost, $handling_fee, $this->quote_settings, $ltl_package, $this->minPrices, $this->en_fdo_meta_data);
                        $this->en_fdo_meta_data_limited = [];
                        $this->en_fdo_meta_data_limited_liftgate = [];
                        $this->minPrices_limited = [];
                        $this->minPrices_limited_liftgate = [];

                        if(isset($la_multishipment_array['limited_cost_data']) && !empty($la_multishipment_array['limited_cost_data'])){

                            $this->limited_cost = $la_multishipment_array['limited_cost_data']['limited_cost'];
                            $this->minPrices_limited = $la_multishipment_array['limited_cost_data']['minPrices'];
                            $this->en_fdo_meta_data_limited = $la_multishipment_array['limited_cost_data']['en_fdo_meta_data'];

                            $this->la_label = $la_multishipment_array['la_label'];
                            $this->la_label_append = $la_multishipment_array['la_label_append'];
                        }

                        //limited_access_with_liftgate
                        if(isset($la_multishipment_array['limited_access_with_liftgate']) && !empty($la_multishipment_array['limited_access_with_liftgate'])){

                            $this->limited_cost_liftgate = $la_multishipment_array['limited_access_with_liftgate']['limited_cost'];
                            $this->minPrices_limited_liftgate = $la_multishipment_array['limited_access_with_liftgate']['minPrices'];
                            $this->en_fdo_meta_data_limited_liftgate = $la_multishipment_array['limited_access_with_liftgate']['en_fdo_meta_data'];
                            $this->la_label_liftgate = $la_multishipment_array['limited_access_with_liftgate']['label_arr'];
                            $this->la_label_append_liftgate = $la_multishipment_array['limited_access_with_liftgate']['label_append'];
                        }
                        $this->minPrices = array_merge($this->minPrices, $this->minPrices_limited, $this->minPrices_limited_liftgate);

                        isset($this->en_fdo_meta_data_limited['WWE_WLA']) ? $this->en_fdo_meta_data['WWE_WLA'] = $this->en_fdo_meta_data_limited['WWE_WLA'] : $this->en_fdo_meta_data;
                        isset($this->en_fdo_meta_data_limited_liftgate['WWE_WLAL']) ? $this->en_fdo_meta_data['WWE_WLAL'] = $this->en_fdo_meta_data_limited_liftgate['WWE_WLAL'] : $this->en_fdo_meta_data;

                        // Pricing per product
                        $_cost = $this->calculate_markup($_cost, $pricing_per_product);
                        if (get_option('speed_freight_limited_access_delivery') == "yes" && !empty($quote) &&
                        isset($rates['meta_data']['en_fdo_meta_data']['accessorials']['residential']) && $rates['meta_data']['en_fdo_meta_data']['accessorials']['residential'] != 1) {
                            $_cost = $_cost + get_option('speed_freight_limited_access_delivery_fee');
                            $this->en_fdo_meta_data['WWE_LIFT'][$key]['rate']['cost'] = $_cost;
                         }elseif(get_option('speed_freight_limited_access_delivery') == "yes" &&
                            !empty($quote) &&
                            isset($rates['meta_data']['en_fdo_meta_data']['accessorials']['residential']) &&
                            $rates['meta_data']['en_fdo_meta_data']['accessorials']['residential'] == 1 &&
                            get_option('wc_settings_wwe_residential_delivery') == 'yes') {
                            $_cost = $_cost + get_option('speed_freight_limited_access_delivery_fee');
                            $this->en_fdo_meta_data['WWE_LIFT'][$key]['rate']['cost'] = $_cost;
                            $this->en_fdo_meta_data['WWE_LIFT'][$key]['accessorials']['limited_access'] = 1;
                        }elseif(get_option('speed_freight_limited_access_delivery') == "yes" && !empty($quote)) {
                            $_cost = $_cost + get_option('speed_freight_limited_access_delivery_fee');
                            $this->en_fdo_meta_data['WWE_LIFT'][$key]['rate']['cost'] = $_cost;
                            $this->en_fdo_meta_data['WWE_LIFT'][$key]['accessorials']['limited_access'] = 1;
                        }

                        // Product level markup
                        if(!empty($rates['product_level_markup'])){
                            $_cost = $this->add_handling_fee($_cost, $rates['product_level_markup']);
                        }

                        // origin level markup
                        if(!empty($rates['origin_markup'])){
                            $_cost = $this->add_handling_fee($_cost, $rates['origin_markup']);
                        }

                        $multi_cost += $this->add_handling_fee($_cost, $handling_fee);
                        $this->en_fdo_meta_data['WWE_LIFT'][$key]['rate']['cost'] = $this->add_handling_fee($_cost, $handling_fee);
                    }
                    // Excluded accessorials
                    $en_accessorial_excluded = apply_filters('en_accessorial_excluded', []);
                    ($s_multi_cost > 0) ? $rate[] = $this->arrange_multiship_freight(($s_multi_cost + $smpkgCost), 'WWE_NOTLIFT', $s_label, $s_append_label) : "";
                    if ($s_multi_cost > 0 && !empty($en_accessorial_excluded) && in_array('liftgateResidentialExcluded', $en_accessorial_excluded)) {
                        $multi_cost = 0;
                    }
                    ($multi_cost > 0) ? $rate[] = $this->arrange_multiship_freight(($multi_cost + $smpkgCost), 'WWE_LIFT', $_label, $append_label) : "";
                    
                    if(isset($this->limited_cost)) {

                        ($this->limited_cost > 0) ? $rate[] = $this->arrange_multiship_freight(($this->limited_cost + $smpkgCost), 'WWE_WLA', $this->la_label, $this->la_append_label) : "";
                    }
                    if(isset($this->limited_cost_liftgate)) {
                        ($this->limited_cost_liftgate > 0) ? $rate[] = $this->arrange_multiship_freight(($this->limited_cost_liftgate + $smpkgCost), 'WWE_WLAL', $this->la_label_liftgate, $this->la_label_append_liftgate) : "";
                    }
                    ($hold_at_terminal_fee > 0) ? $rate[] = $this->arrange_multiship_freight(($hold_at_terminal_fee + $smpkgCost), 'WWE_HAT', ['WWE_HAT'], $append_hat_label) : "";
                    $this->shipment_type = 'multiple';
                    $rates = $this->wwe_ltl_add_rate_arr($rate);
                } else {

                    $quote = reset($quotes);
                    $rates = [];
                    if (isset($quote['hold_at_terminal_quotes'])) {
                        $hold_at_terminal_quotes = $quote['hold_at_terminal_quotes'];
                        $rates = $Ltl_Freight_Quotes->calculate_quotes($hold_at_terminal_quotes, $this->quote_settings);
                        $limited_access_hat = [];
                        foreach($rates as $la_key => $la_val) {
                            if(isset($la_val['meta_data']['en_fdo_meta_data']['accessorials']) &&
                                is_array($la_val['meta_data']['en_fdo_meta_data']['accessorials'])){
                                $la_val['meta_data']['en_fdo_meta_data']['accessorials']['limited_access'] = false;
                            }
                            $limited_access_hat[] = $la_val;
                        }
                        $rates = $limited_access_hat;
                        unset($quote['hold_at_terminal_quotes']);
                    }

//                  Dispaly Local and In-store PickUp Delivery 
                    $this->InstorPickupLocalDelivery = $ltl_res_inst->wwe_ltl_return_local_delivery_store_pickup();
                    $simple_quotes = (isset($quote['simple_quotes'])) ? $quote['simple_quotes'] : [];
                    $quote = $this->remove_array($quote, 'simple_quotes');

                    $calculate_quotes = $Ltl_Freight_Quotes->calculate_quotes($quote, $this->quote_settings);

                    if (get_option('speed_freight_limited_access_delivery') == "yes" && !empty($quote)) {
                        $limited_access_delivery = [];
                        foreach($calculate_quotes as $la_key => $la_val) {
                            if(isset($la_val['meta_data']['en_fdo_meta_data']['la_residential_status']) && $la_val['meta_data']['en_fdo_meta_data']['la_residential_status'] == 'r'){
                                if(isset($la_val['meta_data']['en_fdo_meta_data']['accessorials']) &&
                                    is_array($la_val['meta_data']['en_fdo_meta_data']['accessorials'])){
                                    $la_val['meta_data']['en_fdo_meta_data']['accessorials']['limited_access'] = true;
                                    $la_val['cost'] = $la_val['cost'] + get_option('speed_freight_limited_access_delivery_fee');
                                    $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + get_option('speed_freight_limited_access_delivery_fee');

                                }
                            }
                            $limited_access_delivery[] = $la_val;
                        }
                        $calculate_quotes = $limited_access_delivery;
                    }
                    $rates = (!empty($rates)) ? array_merge($rates, $calculate_quotes) : $calculate_quotes;

//                  Offer lift gate delivery as an option is enabled
                    if (isset($this->quote_settings['liftgate_delivery_option']) &&
                        ($this->quote_settings['liftgate_delivery_option'] == "yes") &&
                        (!empty($simple_quotes))) {
                        $simple_rates = $Ltl_Freight_Quotes->calculate_quotes($simple_quotes, $this->quote_settings);
                        if (get_option('speed_freight_limited_access_delivery') == "yes" && !empty($quote)) {
                            $limited_access_delivery = [];
                            foreach($simple_rates as $la_key => $la_val) {
                                if(isset($la_val['meta_data']['en_fdo_meta_data']['la_residential_status']) && $la_val['meta_data']['en_fdo_meta_data']['la_residential_status'] == 'r'){
                                    if(isset($la_val['meta_data']['en_fdo_meta_data']['accessorials']) &&
                                        is_array($la_val['meta_data']['en_fdo_meta_data']['accessorials'])){
                                        $la_val['meta_data']['en_fdo_meta_data']['accessorials']['limited_access'] = true;
                                        $la_val['cost'] = $la_val['cost'] + get_option('speed_freight_limited_access_delivery_fee');
                                        $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + get_option('speed_freight_limited_access_delivery_fee');


                                    }
                                }else{
                                    $la_val['cost'] = $la_val['cost'] + get_option('speed_freight_limited_access_delivery_fee');
                                    $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + get_option('speed_freight_limited_access_delivery_fee');
                                }
                                $limited_access_delivery[] = $la_val;
                            }
                            $rates = array_merge($rates, $limited_access_delivery);
                        }else {
                            $rates = array_merge($rates, $simple_rates);
                        }

                    }

                    // limited access
                    $rates = $limited_access_delivery_class->add_limited_access_singleshipment($quote, $simple_quotes, $Ltl_Freight_Quotes, $rates, $this->quote_settings);

                    $cost_sorted_key = [];

                    $this->quote_settings['shipment'] = "single_shipment";

                    $is_prevent_resi_custom_exists  = apply_filters('en_prevent_resi_custom_addon', false);
                    if($is_prevent_resi_custom_exists){
                        $first_ltl_package = reset($ltl_package);
                        if(!empty($first_ltl_package['packaging_fee_value'])){
                            $packaging_fee_arr = $first_ltl_package['packaging_fee_value'];
                        }
                    }

                    foreach ($rates as $key => $quote) {

                        // custom work
                        if($is_prevent_resi_custom_exists && isset($packaging_fee_arr)){
                            $quote['cost'] = apply_filters('en_prevent_resi_add_packaging_fee_to_rates', $quote['cost'], $packaging_fee_arr);
                        }

                        $handling_fee = (isset($rates['markup']) && (strlen($rates['markup']) > 0)) ? $rates['markup'] : $handling_fee;
                        $_cost = (isset($quote['cost'])) ? $quote['cost'] : 0;

                        // Pricing per product
                        $pricing_per_product = (isset($quote['pricing_per_product'])) ? $quote['pricing_per_product'] : [];
                        $_cost = $this->calculate_markup($_cost, $pricing_per_product);
                        isset($rates[$key]['cost']) ? $rates[$key]['cost'] = $_cost : '';
                        $quote_type_avg = isset($quote['meta_data'], $quote['meta_data']['en_fdo_meta_data'], $quote['meta_data']['en_fdo_meta_data']['rate'], $quote['meta_data']['en_fdo_meta_data']['rate']['quote_type']) && $quote['meta_data']['en_fdo_meta_data']['rate']['quote_type'] == 'hold_at_terminal_quote' ? $quote['meta_data']['en_fdo_meta_data']['rate']['quote_type']: '';
                        if (!(isset($quote['quote_type']) && $quote['quote_type'] = 'hold_at_terminal_quote')) {
                            
                            // Product level markup
                            if(!empty($quote['product_level_markup'])){
                                $_cost = $this->add_handling_fee($_cost, $quote['product_level_markup']);
                            }

                            // origin level markup
                            if(!empty($quote['origin_markup'])){
                                $_cost = $this->add_handling_fee($_cost, $quote['origin_markup']);
                            }

                            if (empty($quote_type_avg)) {
                                if(isset($rates[$key]['cost']) && !empty($rates[$key])) {
                                    $rates[$key]['cost'] = $this->add_handling_fee($_cost, $handling_fee);
                                }

                                if (isset($rates[$key]['meta_data']['en_fdo_meta_data']['rate']['cost'])) {
                                    $rates[$key]['meta_data']['en_fdo_meta_data']['rate']['cost'] = $this->add_handling_fee($_cost, $handling_fee);
                                }
                            }
                        }

                        $cost_sorted_key[$key] = (isset($quote['cost'])) ? $quote['cost'] : 0;
                        if(isset($rates[$key]) && !empty($rates[$key])) {
                            $rates[$key]['shipment'] = "single_shipment";
                        }
                    }

                    // Array_multisort
                    array_multisort($cost_sorted_key, SORT_ASC, $rates);

                    $this->shipment_type = 'single';
                    $rates = $this->wwe_ltl_add_rate_arr($rates);
                }

                // Origin terminal address
                if ($this->shipment_type == 'single' || count($pricing_product_origins) == 1) {
                    if(count($pricing_product_origins) == 1){
                        $this->InstorPickupLocalDelivery = $ltl_res_inst->wwe_ltl_return_local_delivery_store_pickup();
                    }
                    (isset($this->ltl_res_inst->InstorPickupLocalDelivery->localDelivery) && ($this->ltl_res_inst->InstorPickupLocalDelivery->localDelivery->status == 1)) ? $this->local_delivery($this->ltl_res_inst->en_wd_origin_array['fee_local_delivery'], $this->ltl_res_inst->en_wd_origin_array['checkout_desc_local_delivery'], $this->ltl_res_inst->en_wd_origin_array) : "";
                    (isset($this->ltl_res_inst->InstorPickupLocalDelivery->inStorePickup, $this->ltl_res_inst->InstorPickupLocalDelivery->totalDistance) && ($this->ltl_res_inst->InstorPickupLocalDelivery->inStorePickup->status == 1)) ? $this->pickup_delivery($this->ltl_res_inst->en_wd_origin_array['checkout_desc_store_pickup'], $this->ltl_res_inst->en_wd_origin_array, $this->ltl_res_inst->InstorPickupLocalDelivery->totalDistance) : "";
                }

                return $rates;
            }

            /**
             * final rates sorting
             * @param array type $rates
             * @param array type $package
             * @return array type
             */
            function en_sort_woocommerce_available_shipping_methods($rates, $package)
            {
                // If there are no rates don't do anything
                if (!$rates) {
                    return [];
                }

                // Check the option to sort shipping methods by price on quote settings
                if (get_option('shipping_methods_do_not_sort_by_price') != 'yes') {
                    // Get an array of prices
                    $prices = [];
                    foreach ($rates as $rate) {
                        $prices[] = $rate->cost;
                    }

                    // Use the prices to sort the rates
                    array_multisort($prices, $rates);
                }
                // Return the rates
                return $rates;
            }

            /**
             * Pickup delivery quote
             * @return array type
             */
            function pickup_delivery($label, $en_wd_origin_array, $total_distance)
            {
                $this->woocommerce_package_rates = 1;
                $this->instore_pickup_and_local_delivery = TRUE;

                $label = (isset($label) && (strlen($label) > 0)) ? $label : 'In-store pick up';
                // Origin terminal address
                $address = (isset($en_wd_origin_array['address'])) ? $en_wd_origin_array['address'] : '';
                $city = (isset($en_wd_origin_array['city'])) ? $en_wd_origin_array['city'] : '';
                $state = (isset($en_wd_origin_array['state'])) ? $en_wd_origin_array['state'] : '';
                $zip = (isset($en_wd_origin_array['zip'])) ? $en_wd_origin_array['zip'] : '';
                $phone_instore = (isset($en_wd_origin_array['phone_instore'])) ? $en_wd_origin_array['phone_instore'] : '';
                strlen($total_distance) > 0 ? $label .= ' | ' . str_replace("mi", "miles", $total_distance) . ' away' : '';
                strlen($address) > 0 ? $label .= ' | ' . $address : '';
                strlen($city) > 0 ? $label .= ', ' . $city : '';
                strlen($state) > 0 ? $label .= ' ' . $state : '';
                strlen($zip) > 0 ? $label .= ' ' . $zip : '';
                strlen($phone_instore) > 0 ? $label .= ' | ' . $phone_instore : '';

                $pickup_delivery = array(
                    'id' => 'in-store-pick-up',
                    'cost' => !empty($en_wd_origin_array['fee_store_pickup']) ? $en_wd_origin_array['fee_store_pickup'] : 0,
                    'label' => $label,
                    'plugin_name' => 'wweLtl',
                    'plugin_type' => 'ltl',
                    'owned_by' => 'eniture'
                );

                add_filter('woocommerce_package_rates', array($this, 'en_sort_woocommerce_available_shipping_methods'), 10, 2);
                $this->add_rate($pickup_delivery);
            }

            /**
             * Local delivery quote
             * @param string type $cost
             * @return array type
             */
            function local_delivery($cost, $label, $en_wd_origin_array)
            {
                $this->woocommerce_package_rates = 1;
                $this->instore_pickup_and_local_delivery = TRUE;
                $label = (isset($label) && (strlen($label) > 0)) ? $label : 'Local Delivery';

                $local_delivery = array(
                    'id' => 'local-delivery',
                    'cost' => !empty($cost) ? $cost : 0,
                    'label' => $label,
                    'plugin_name' => 'wweLtl',
                    'plugin_type' => 'ltl',
                    'owned_by' => 'eniture'
                );

                add_filter('woocommerce_package_rates', array($this, 'en_sort_woocommerce_available_shipping_methods'), 10, 2);
                $this->add_rate($local_delivery);
            }

            /**
             * Remove array
             * @return array
             */
            function remove_array($quote, $remove_index)
            {
                unset($quote[$remove_index]);

                return $quote;
            }

            /**
             * Arrange Own Freight
             * @return array
             */
            function arrange_own_freight()
            {

                return array(
                    'id' => 'own_freight',
                    'cost' => 0,
                    'label' => get_option('wc_settings_wwe_text_for_own_arrangment'),
                    'calc_tax' => 'per_item',
                    'plugin_name' => 'wweLtl',
                    'plugin_type' => 'ltl',
                    'owned_by' => 'eniture'
                );
            }

            /**
             * Multishipment
             * @return array
             */
            function arrange_multiship_freight($cost, $id, $label_sufex, $append_label)
            {

                return array(
                    'id' => $id,
                    'label' => "Freight",
                    'cost' => $cost,
                    'label_sufex' => $label_sufex,
                    'append_label' => $append_label,
                    'plugin_name' => 'wweLtl',
                    'plugin_type' => 'ltl',
                    'owned_by' => 'eniture'
                );
            }

            /**
             *
             * @param string type $price
             * @param string type $handling_fee
             * @return float type
             */
            function add_handling_fee($price, $handling_fee)
            {
                $handling_fee = $price > 0 ? $handling_fee : 0;
                $handelingFee = 0;
                if ($handling_fee != '' && $handling_fee != 0) {
                    if (strrchr($handling_fee, "%")) {

                        $prcnt = (float)$handling_fee;
                        $handelingFee = (float)$price / 100 * $prcnt;
                    } else {
                        $handelingFee = (float)$handling_fee;
                    }
                }

                $handelingFee = $this->smooth_round($handelingFee);
                $price = (float)$price + $handelingFee;
                return $price;
            }

            /**
             *
             * @param float type $val
             * @param int type $min
             * @param int type $max
             * @return float type
             */
            function smooth_round($val, $min = 2, $max = 4)
            {
                $result = round($val, $min);
                if ($result == 0 && $min < $max) {
                    return $this->smooth_round($val, ++$min, $max);
                } else {
                    return $result;
                }
            }

            /**
             * sort array
             * @param array type $rate
             * @return array type
             */
            public function sort_asec_order_arr($rate, $index)
            {
                $price_sorted_key = [];
                foreach ($rate as $key => $cost_carrier) {
                    $price_sorted_key[$key] = (isset($cost_carrier[$index])) ? $cost_carrier[$index] : 0;
                }
                array_multisort($price_sorted_key, SORT_ASC, $rate);

                return $rate;
            }

            /**
             * Label from quote settings tab
             * @return string type
             */
            public function wwe_label_as()
            {
                return (strlen($this->quote_settings['wwe_label']) > 0) ? $this->quote_settings['wwe_label'] : "Freight";
            }

            /**
             * filter and update label
             * @param type $label_sufex
             * @return string
             */
            public function filter_from_label_sufex($label_sufex)
            {
                $append_label = "";
                $rad_status = true;
                $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
                if (stripos(implode($all_plugins), 'residential-address-detection.php') || is_plugin_active_for_network('residential-address-detection/residential-address-detection.php')) {
                    if(get_option('suspend_automatic_detection_of_residential_addresses') != 'yes') {
                        $rad_status = get_option('residential_delivery_options_disclosure_types_to') != 'not_show_r_checkout';
                    }
                }

                switch (TRUE) {
                    case(count($label_sufex) == 1):
                        (in_array('L', $label_sufex)) ? $append_label = " with lift gate delivery " : "";
                        (in_array('R', $label_sufex) && $rad_status == true) ? $append_label = " with residential delivery " : "";
                        (in_array('LA', $label_sufex)) ? $append_label = " with limited access delivery " : "";
                        (in_array('WWE_HAT', $label_sufex) || in_array('HAT', $label_sufex)) ? $append_label = " with hold at terminal" : "";
                        break;
                    case (count($label_sufex) == 3):
                    case(count($label_sufex) == 2):
                        (in_array('L', $label_sufex)) ? $append_label = " with lift gate delivery " : "";
                        (in_array('R', $label_sufex) && $rad_status == true) ? $append_label .= (strlen($append_label) > 0) ? " and residential delivery " : " with residential delivery " : "";
                        (in_array('LA', $label_sufex)) ? $append_label .= " and limited access delivery " : "";
                        (in_array('WWE_HAT', $label_sufex) || in_array('HAT', $label_sufex)) ? $append_label .= (strlen($append_label) > 0) ? " and hold at terminal " : " with hold at terminal " : "";
                        break;
                }

                return $append_label;
            }

            /**
             * Append label in quote
             * @param array type $rate
             * @return string type
             */
            public function set_label_in_quote($rate)
            {
                $rate_label = "";
                $label_sufex = (isset($rate['label_sufex']) && is_array($rate['label_sufex'])) ? array_unique($rate['label_sufex']) : [];
                $rate_label = (!isset($rate['label']) ||
                    ($this->quote_settings['shipment'] == "single_shipment" &&
                        strlen($this->quote_settings['wwe_label']) > 0)) ?
                    $this->wwe_label_as() : $rate['label'];

                $rate_label .= (isset($this->quote_settings['sandbox'])) ? ' (Sandbox) ' : '';
                $rate_label .= $this->filter_from_label_sufex($label_sufex);

                $shipment_type = isset($this->quote_settings['shipment']) && !empty($this->quote_settings['shipment']) ? $this->quote_settings['shipment'] : '';
                if (isset($this->quote_settings['delivery_estimates']) && !empty($this->quote_settings['delivery_estimates'])
                    && $this->quote_settings['delivery_estimates'] != 'dont_show_estimates' && $shipment_type != 'multi_shipment') {
                    if ($this->quote_settings['delivery_estimates'] == 'delivery_date') {
                        isset($rate['delivery_time_stamp']) && is_string($rate['delivery_time_stamp']) && strlen($rate['delivery_time_stamp']) > 0 ? $rate_label .= ' ( Expected delivery by ' . date('m-d-Y', strtotime($rate['delivery_time_stamp'])) . ')' : '';
                    } else if ($this->quote_settings['delivery_estimates'] == 'delivery_days') {
                        isset($rate['delivery_estimates']) && is_string($rate['delivery_estimates']) && strlen($rate['delivery_estimates']) > 0 ? $rate_label .= ' ( Intransit days: ' . $rate['delivery_estimates'] . ' )' : '';
                    }
                }

                return $rate_label;
            }

            /**
             * rates to add_rate woocommerce
             * @param array type $add_rate_arr
             */
            public function wwe_ltl_add_rate_arr($add_rate_arr)
            {
                //revmax custom work ticket #732783627
                if (is_plugin_active('wwex_revmax_custom_shipping_quotes.php_/wwex_revmax_custom_shipping_quotes.php')) {
                    $add_rate_arr = apply_filters('wwe_add_rate_arr', $add_rate_arr);
                }
                if (isset($add_rate_arr) && (!empty($add_rate_arr)) && (is_array($add_rate_arr))) {
                    // Images for FDO
                    $image_urls = apply_filters('en_fdo_image_urls_merge', []);
                    add_filter('woocommerce_package_rates', array($this, 'en_sort_woocommerce_available_shipping_methods'), 10, 2);
                    $instore_pickup_local_devlivery_action = apply_filters('wwe_quests_quotes_plans_suscription_and_features', 'instore_pickup_local_devlivery');

                    foreach ($add_rate_arr as $key => $rate) {
                        if (isset($rate['cost']) && $rate['cost'] > 0) {
                            $rate['label'] = $this->set_label_in_quote($rate);
                            if (isset($rate['meta_data'])) {
                                $rate['meta_data']['label_sufex'] = (isset($rate['label_sufex'])) ? json_encode($rate['label_sufex']) : [];
                            }

                            $rate['id'] = (isset($rate['id'])) ? $rate['id'] : '';
                            // limited access
                            if (isset($this->minPrices[$rate['id']])) {
                                $rate['meta_data']['min_prices'] = json_encode($this->minPrices[$rate['id']]);
                                $rate['meta_data']['en_fdo_meta_data']['data'] = array_values($this->en_fdo_meta_data[$rate['id']]);

                                (!empty($this->en_fdo_meta_data_third_party)) ? $rate['meta_data']['en_fdo_meta_data']['data'] = array_merge($rate['meta_data']['en_fdo_meta_data']['data'], $this->en_fdo_meta_data_third_party) : '';
                                $rate['meta_data']['en_fdo_meta_data']['shipment'] = 'multiple';
                                $rate['meta_data']['en_fdo_meta_data'] = wp_json_encode($rate['meta_data']['en_fdo_meta_data']);
                            } else {
                                $en_set_fdo_meta_data['data'] = [$rate['meta_data']['en_fdo_meta_data']];
                                $en_set_fdo_meta_data['shipment'] = 'sinlge';
                                $rate['meta_data']['en_fdo_meta_data'] = wp_json_encode($en_set_fdo_meta_data);
                            }

                            $rate['id'] = (isset($rate['id']) && is_string($rate['id'])) ? 'ltl_shipping_method:' . $rate['id'] : '';
                            
                            // Images for FDO
                            $rate['meta_data']['en_fdo_image_urls'] = wp_json_encode($image_urls);

                            if (isset($this->web_service_inst->en_wd_origin_array['suppress_local_delivery']) && $this->web_service_inst->en_wd_origin_array['suppress_local_delivery'] == "1" && (!is_array($instore_pickup_local_devlivery_action)) && ($this->shipment_type != 'multiple')) {

                                $rate = apply_filters('suppress_local_delivery', $rate, $this->web_service_inst->en_wd_origin_array, $this->package_plugin, $this->InstorPickupLocalDelivery);

                                if (!empty($rate)) {
                                    $this->add_rate($rate);
                                    $this->woocommerce_package_rates = 1;
                                    $add_rate_arr[$key] = $rate;
                                }
                            } else {
//                              Custom client work 
                                if (has_filter('add_duplicate_array') &&
                                    (isset($rate['shipment'])) && ($rate['shipment'] == "single_shipment")) {
                                    $quote = apply_filters('add_duplicate_array', $rate);
                                    foreach ($quote as $key => $value) {
                                        $this->add_rate($value);
                                        $add_rate_arr[$key] = $value;
                                    }
                                } else {
                                    $this->add_rate($rate);
                                    $add_rate_arr[$key] = $rate;
                                }
                            }
                        }
                    }

                    (isset($this->quote_settings['own_freight']) && ($this->quote_settings['own_freight'] == "yes")) ? $this->add_rate($this->arrange_own_freight()) : "";
                }

                return $add_rate_arr;
            }

            /**
             * quote sorted array
             *
             */

            /**
             * quote settings array
             * @global $wpdb $wpdb
             */
            function ltl_shipping_quote_settings()
            {
                global $wpdb;
                $enable_carriers = $wpdb->get_results('SELECT `speed_freight_carrierSCAC` FROM ' . WWE_CARRIERS .' Where carrier_status ="1"');
                $enable_carriers = json_decode(json_encode($enable_carriers), TRUE);
                $rating_method = get_option('wc_settings_wwe_rate_method');
                $wwe_label = get_option('wc_settings_wwe_label_as');
                $VersionCompat = new VersionCompat();
                $enable_carriers = $VersionCompat->enArrayColumn($enable_carriers, 'speed_freight_carrierSCAC');

                $this->ltl_res_inst->quote_settings['transit_days'] = get_option('wc_settings_wwe_delivery_estimate');
                $this->ltl_res_inst->quote_settings['own_freight'] = get_option('wc_settings_wwe_allow_for_own_arrangment');
                $this->ltl_res_inst->quote_settings['total_carriers'] = get_option('wc_settings_wwe_Number_of_options');
                $this->ltl_res_inst->quote_settings['rating_method'] = (isset($rating_method) && (strlen($rating_method)) > 0) ? $rating_method : "Cheapest";
                $this->ltl_res_inst->quote_settings['wwe_label'] = ($rating_method == "average_rate" || $rating_method == "Cheapest") ? $wwe_label : "";
                $this->ltl_res_inst->quote_settings['handling_fee'] = get_option('wc_settings_wwe_hand_free_mark_up');
                $this->ltl_res_inst->quote_settings['enable_carriers'] = $enable_carriers;
                $this->ltl_res_inst->quote_settings['liftgate_delivery'] = get_option('wc_settings_wwe_lift_gate_delivery');
                $this->ltl_res_inst->quote_settings['liftgate_delivery_option'] = get_option('wwe_quests_liftgate_delivery_as_option');
                $this->ltl_res_inst->quote_settings['residential_delivery'] = get_option('wc_settings_wwe_residential_delivery');
                $this->ltl_res_inst->quote_settings['liftgate_resid_delivery'] = get_option('en_woo_addons_liftgate_with_auto_residential');
                $this->ltl_res_inst->quote_settings['notify_delivery'] = get_option('wwe_quests_notify_delivery_as_option');
                $this->ltl_res_inst->quote_settings['limited_access_delivery'] = get_option('speed_freight_limited_access_delivery');
                $this->web_service_inst->quote_settings['HAT_status'] = get_option('wwe_ltl_hold_at_terminal_checkbox_status');
                $this->web_service_inst->quote_settings['HAT_fee'] = get_option('wwe_ltl_hold_at_terminal_fee');
                $this->web_service_inst->quote_settings['dont_sort'] = get_option('shipping_methods_do_not_sort_by_price');
                // Cuttoff Time
                $this->web_service_inst->quote_settings['delivery_estimates'] = get_option('wwe_lfq_delivery_estimates');
                $this->web_service_inst->quote_settings['orderCutoffTime'] = get_option('wwe_lfq_freight_order_cut_off_time');
                $this->web_service_inst->quote_settings['shipmentOffsetDays'] = get_option('wwe_lfq_freight_shipment_offset_days');
                $this->web_service_inst->quote_settings['handling_weight'] = get_option('wwe_freight_handling_weight');
                $this->web_service_inst->quote_settings['maximum_handling_weight'] = get_option('wwe_freight_maximum_handling_weight');
                $this->web_service_inst->recent_quote_settings = $this->web_service_inst->quote_settings;

            }

            /**
             * Create plugin option
             */
            function create_speedfreight_ltl_option()
            {
                $eniture_plugins = get_option('EN_Plugins');
                if (!$eniture_plugins) {
                    add_option('EN_Plugins', json_encode(array('speedfreight')));
                } else {
                    $plugins_array = json_decode($eniture_plugins, true);
                    if (!in_array('speedfreight', $plugins_array)) {
                        array_push($plugins_array, 'speedfreight');
                        update_option('EN_Plugins', json_encode($plugins_array));
                    }
                }
            }

            /**
             * Check is free shipping or not
             * @param $coupon
             * @return string
             */
            function wweLTLFreeShipping($coupon)
            {
                foreach ($coupon as $key => $value) {
                    if ($value->get_free_shipping() == 1) {
                        $free = array(
                            'id' => 'free',
                            'label' => 'Free Shipping',
                            'cost' => 0,
                            'plugin_name' => 'wweLtl',
                            'plugin_type' => 'ltl',
                            'owned_by' => 'eniture'
                        );
                        $this->add_rate($free);
                        return 'y';
                    }
                }
                return 'n';
            }


        }

    }
}
