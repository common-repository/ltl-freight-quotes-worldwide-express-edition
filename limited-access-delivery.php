<?php

class LimitedAccessDelivery
{
    public $la_label_sufex;
    public $la_label_append;
    public $la_status;
    public $la_option_status;
    public $limited_access_type;
    public $limited_access_value = 0;
    public $quote_settings;
    public $limited_access_cost = 0;
    public $limited_cost = 0;
    public $WC_speedfreight_Shipping_Method;
    public $liftgate_delivery;
    public $limited_access_only;
    public $limited_access_key;
    public $limited_liftgate;
    public $label_with_liftgate;
    public $la_without_rad;
    public $limited_access_liftgate;
    public $en_fdo_meta_data;
    public $lal_costs = 0;
    public $la_costs = 0;
    public $la_label;
    public $la_append_label;


    public function __construct() {
        $this->la_status = get_option('speed_freight_limited_access_delivery');
        $this->la_option_status = get_option('speed_freight_limited_access_delivery_as_option');
        $this->limited_access_type = $this->la_status == 'yes' ? 'always' : ($this->la_option_status == 'yes' ? 'option' : '');
        $this->limited_access_value = get_option('speed_freight_limited_access_delivery_fee');
        //$this->liftgate_delivery = get_option('wc_settings_wwe_lift_gate_delivery');
        $this->la_label_sufex = ['LA'];
        $this->la_label_append = ' with limited access delivery ';
        $this->limited_access_only = 'LAonly'; // limited access only
        $this->limited_access_key =  'WWE_WLA';
        $this->limited_access_liftgate =  'WWE_WLAL';
        $this->limited_liftgate = 'LA';
        $this->label_with_liftgate = ' with liftgate and limited access delivery ';
        $this->WC_speedfreight_Shipping_Method = new WC_speedfreight_Shipping_Method();
    }
    /**
     * improve code
     */
    public function add_limited_access_singleshipment($quote, $simple_quotes, $Ltl_Freight_Quotes, $rates, $quote_settings){
        $this->quote_settings = $quote_settings;
        $simple_rates = !empty($quote) ? $Ltl_Freight_Quotes->calculate_quotes($quote, $this->quote_settings) : [];
        $liftgate_delivery_option = isset($this->quote_settings['liftgate_delivery_option'])? $this->quote_settings['liftgate_delivery_option'] : '';
        $liftgate_delivery = $this->quote_settings['liftgate_delivery'];
        // Excluded accessorials
        $en_accessorial_excluded = apply_filters('en_accessorial_excluded', []);
        if ($this->limited_access_type == "always") {
            $rates = $this->limited_access_data_always($simple_rates, $rates, $simple_quotes, $quote);
        }

        if ($this->limited_access_type == "option") {
            $rates = $this->limited_access_data_option($simple_rates, $rates, $simple_quotes, $quote, $liftgate_delivery);
        }
        if ($this->limited_access_type == "option" &&
            $liftgate_delivery_option == "yes" &&
            !in_array('liftgateResidentialExcluded', $en_accessorial_excluded)) {
            $rates = $this->limited_access_data_option_liftgate($simple_rates, $rates, $simple_quotes, $quote, $liftgate_delivery);
        }
        return $rates;
    }
    /**
     * add limited access for always
     */
    public function limited_access_data_always($simple_rates, $rates, $simple_quotes, $quote){
        $limited_access = [];
        foreach($simple_rates as $la_key => $la_val) {
            if(isset($la_val['meta_data']['en_fdo_meta_data']['la_residential_status']) && $la_val['meta_data']['en_fdo_meta_data']['la_residential_status'] != 'r'){
                $la_val['cost'] = $la_val['cost'] + $this->limited_access_value;
                $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + $this->limited_access_value;
            }elseif(isset($la_val['meta_data']['en_fdo_meta_data']['la_residential_status']) &&
                $la_val['meta_data']['en_fdo_meta_data']['la_residential_status'] == 'r' &&
                get_option('wc_settings_wwe_residential_delivery') != 'yes' ) {
                $la_val = $this->limited_access_liftgate($la_val, $simple_quotes, $quote);
                $la_val = $this->limited_access_residential($la_val, $simple_quotes, $quote);
                $la_val['cost'] = $la_val['cost'] + $this->limited_access_value;
                $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + $this->limited_access_value;
            }elseif(isset($la_val['meta_data']['en_fdo_meta_data']['la_residential_status']) &&
                $la_val['meta_data']['en_fdo_meta_data']['la_residential_status'] == 'r' &&
                get_option('wc_settings_wwe_residential_delivery') == 'yes' ) {
                $la_val = $this->limited_access_residential($la_val, $simple_quotes, $quote);
                $la_val['cost'] = $la_val['cost'] + $this->limited_access_value;
                $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + $this->limited_access_value;

            }

            $limited_access[] = $la_val;
        }

        $simple_rates = $limited_access;
        $rates = array_merge($rates, $simple_rates);
        if(get_option('wc_settings_wwe_rate_method') == 'average_rate') {
            unset($rates[0]);
        }
        $avg_rate = $rates;
        
        $rates = get_option('wc_settings_wwe_rate_method') == 'average_rate' ? $avg_rate : $rates;
        return  $rates; 
    }
    /**
     * add limited access for option
     */
    public function limited_access_data_option($simple_rates, $rates, $simple_quotes, $quote, $liftgate_delivery){

        foreach($simple_rates as $la_key => $la_val) {
            $la_val = $this->limited_access_residential($la_val, $simple_quotes, $quote);
            $lad_rates_id = $this->limited_access_only.$la_val['id'];
            // check liftgate surcharges index for legacy and new API
            $lfg_exist = isset($la_val['surcharges']['(FEE)Liftgate Delivery']) || isset($la_val['surcharges']['LIFTGATE DELIVERY']);
            if($lfg_exist && isset($la_val['surcharges']['(FEE)Residential Delivery'])) {

                $la_val['label_sufex'] = $this->la_label_sufex;
                if($liftgate_delivery != 'yes') {
                    $la_val['label_sufex'][] = 'L';
                }
                if(get_option('wc_settings_wwe_residential_delivery') != 'yes') {
                    $la_val['label_sufex'][] = 'R';
                }
                $la_val['append_label'] = $this->la_label_append;
                $la_val['id'] = $lad_rates_id;
                $la_val['cost'] = $la_val['cost'] + $this->limited_access_value;
                $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + $this->limited_access_value;

            }else {
                $la_val['label_sufex'] = ['LA'];
                if(isset($la_val['surcharges']['(FEE)Residential Delivery']) && get_option('wc_settings_wwe_residential_delivery') !='yes') {
                    $resi_key = array_search('R', $la_val['label_sufex'], true);
                    if ($resi_key == false) {
                        $la_val['label_sufex'][] = 'R';
                    }
                }
                $la_val['append_label'] = $this->la_label_append;
                $la_val['id'] = $lad_rates_id;
                if($lfg_exist && get_option('wc_settings_wwe_lift_gate_delivery') != 'yes') {
                    $la_val['meta_data']['en_fdo_meta_data']['accessorials']['liftgate'] = false;
                    // check liftgate surcharges fee for legacy and new API
                    $lfg_fee = isset($la_val['surcharges']['(FEE)Liftgate Delivery']) ? $la_val['surcharges']['(FEE)Liftgate Delivery'] : (isset($la_val['surcharges']['LIFTGATE DELIVERY']) ? $la_val['surcharges']['LIFTGATE DELIVERY'] : 0);
                    $la_val['cost'] = $la_val['cost'] - $lfg_fee;
                    $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] - $lfg_fee;
                }
                $la_val['cost'] = $la_val['cost'] + $this->limited_access_value;
                $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + $this->limited_access_value;
            }

            $limited_access[] = $la_val;
        }
        $simple_rates = $limited_access;
        return array_merge($rates, $simple_rates);
    }
    /**
     * add limited access for option and liftgate option
     */
    public function limited_access_data_option_liftgate($simple_rates, $rates, $simple_quotes, $quote, $liftgate_delivery){
        $limited_access = [];
        foreach($simple_rates as $la_key => $la_val) {
            $la_val = $this->limited_access_residential($la_val, $simple_quotes, $quote);
            empty($la_val['label_sufex']) ? $la_val['label_sufex'] = ['LA'] : array_push($la_val['label_sufex'], 'LA');
            // check liftgate surcharges index for legacy and new API
            $lfg_exist = isset($la_val['surcharges']['(FEE)Liftgate Delivery']) || isset($la_val['surcharges']['LIFTGATE DELIVERY']);
            if($lfg_exist && isset($la_val['surcharges']['(FEE)Residential Delivery'])) {
                $resi_key = array_search('L', $la_val['label_sufex'], true);
                if ($resi_key !== false) {
                    unset($la_val['label_sufex'][$resi_key]);
                }
                // check liftgate surcharges fee for legacy and new API
                $lfg_fee = isset($la_val['surcharges']['(FEE)Liftgate Delivery']) ? $la_val['surcharges']['(FEE)Liftgate Delivery'] : (isset($la_val['surcharges']['LIFTGATE DELIVERY']) ? $la_val['surcharges']['LIFTGATE DELIVERY'] : 0);
                $la_val['cost'] = $la_val['cost'] - $lfg_fee;
                $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] - $lfg_fee;

            }else {
                $resi_key = array_search('R', $la_val['label_sufex'], true);
                if ($resi_key !== false) {
                    unset($la_val['label_sufex'][$resi_key]);
                }
            }

            $this->la_label_sufex = $la_val['label_sufex'];
            $this->la_label_append = $this->label_with_liftgate;
            $la_val['append_label'] = $this->label_with_liftgate;
            $la_val['id'] = $la_val['id'].$this->limited_liftgate;
            $la_val['cost'] = $la_val['cost'] + $this->limited_access_value;
            $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] + $this->limited_access_value;
            $limited_access[] = $la_val;
        }
        $simple_rates = $limited_access;
        return array_merge($rates, $simple_rates);
    }
    /**
     * add limited access for multishipment
     */
    public function add_limited_access_multishipment($quote, $simple_quotes, $Ltl_Freight_Quotes, $is_prevent_resi_custom_exists, $key, $index, $limited_access_cost, $handling_fee, $quote_settings, $ltl_package, $minPrices, $en_fdo_meta_data)
    {
        $limited_access_with_liftgate = [];
        $limited_cost_data = [];
        $this->limited_cost = 0;
        $this->quote_settings = $quote_settings;
        $liftgate_delivery_option = isset($this->quote_settings['liftgate_delivery_option'])? $this->quote_settings['liftgate_delivery_option'] : '';
        $simple_rates = $Ltl_Freight_Quotes->calculate_quotes($quote, $this->quote_settings);
        // Excluded accessorials
        $en_accessorial_excluded = apply_filters('en_accessorial_excluded', []);
        if ($this->limited_access_type == "option") {
            $limited_cost_data = $this->add_limited_aceess_multiship_option($quote_settings, $Ltl_Freight_Quotes, $simple_rates, $liftgate_delivery_option, $key, $is_prevent_resi_custom_exists, $index, $handling_fee, $simple_quotes, $quote, $ltl_package, $minPrices, $en_fdo_meta_data, $this->limited_cost);
        }
        if ($this->limited_access_type == "option" &&
            $liftgate_delivery_option == "yes" &&
            !in_array('liftgateResidentialExcluded', $en_accessorial_excluded)) {
            $limited_access_with_liftgate = $this->add_limited_aceess_multiship_option_liftgate($quote_settings, $Ltl_Freight_Quotes, $simple_rates, $liftgate_delivery_option, $key, $is_prevent_resi_custom_exists, $index, $handling_fee, $simple_quotes, $quote, $ltl_package, $minPrices, $en_fdo_meta_data, $this->limited_cost);
        }
        return [
            'limited_cost_data' => $limited_cost_data,
            'limited_access_with_liftgate' => $limited_access_with_liftgate,
            'la_label_append' => $this->la_label_append,
            'la_label' => $this->la_label_sufex,
        ];
    }

    /**
    *add limited acees for multishipment option
     */
    public function add_limited_aceess_multiship_option($quote_settings, $Ltl_Freight_Quotes, $simple_rates, $liftgate_delivery_option, $key, $is_prevent_resi_custom_exists, $index, $handling_fee, $simple_quotes, $quote, $ltl_package, $minPrices, $en_fdo_meta_data, $limited_cost) {

        $simple_rates = $Ltl_Freight_Quotes->calculate_quotes($quote, $quote_settings);
        $la_rates = reset($simple_rates);
        // check liftgate surcharges index for legacy and new API
        $lfg_exist = isset($la_rates['surcharges']['(FEE)Liftgate Delivery']) || isset($la_rates['surcharges']['LIFTGATE DELIVERY']);
        if ($lfg_exist && $liftgate_delivery_option == "yes" && (!empty($simple_quotes) || !empty($quote))) {
            // check liftgate surcharges fee for legacy and new API
            $lfg_fee = isset($la_rates['surcharges']['(FEE)Liftgate Delivery']) ? $la_rates['surcharges']['(FEE)Liftgate Delivery'] : (isset($la_rates['surcharges']['LIFTGATE DELIVERY']) ? $la_rates['surcharges']['LIFTGATE DELIVERY'] : 0);
            $la_rates['cost'] = $la_rates['cost'] - $lfg_fee;
            $la_rates['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_rates['meta_data']['en_fdo_meta_data']['rate']['cost'] - $lfg_fee;

            if(isset($la_rates['meta_data']['en_fdo_meta_data']['accessorials']) &&
                is_array($la_rates['meta_data']['en_fdo_meta_data']['accessorials'])){
                $la_rates['meta_data']['en_fdo_meta_data']['accessorials']['liftgate'] = false;
            }
        }
        $la_rates = $this->limited_access_residential($la_rates, $simple_quotes, $quote);
        $la_rates_id = !empty($la_rates['code']) && is_string($la_rates['code']) ? $la_rates['code'].$this->limited_access_key : $this->limited_access_key;
        $la_rates = $this->limited_access_label($la_rates, $this->la_label_sufex, $this->la_label_append, $la_rates_id);
        $la_rates = $this->limited_access_label_sfx($la_rates);

        if ($lfg_exist && get_option('en_woo_addons_liftgate_with_auto_residential') == "yes" &&
            isset($la_rates['surcharges']['(FEE)Residential Delivery'])) {
            $la_rates['label_sfx_arr'][] = 'L';
        }
        if(get_option('wc_settings_wwe_residential_delivery') == 'yes') {
            $resi_key = array_search('R', $la_rates['label_sfx_arr'], true);
            if ($resi_key !== false) {
                unset($la_rates['label_sfx_arr'][$resi_key]);
            }
        }
        $minPrices['WWE_WLA'][$key] = $la_rates;

        if($is_prevent_resi_custom_exists && isset($ltl_package[$index]['packaging_fee_value'])){
            $la_rates['cost'] = apply_filters('en_prevent_resi_add_packaging_fee_to_rates', $la_rates['cost'], $ltl_package[$index]['packaging_fee_value']);
        }

        // FDO
        $en_fdo_meta_data['WWE_WLA'][$key] = (isset($la_rates['meta_data']['en_fdo_meta_data'])) ? $la_rates['meta_data']['en_fdo_meta_data'] : [];

        $la_cost = (isset($la_rates['cost'])) ? $la_rates['cost'] : 0;
        $this->la_label = (isset($la_rates['label_sfx_arr'])) ? $la_rates['label_sfx_arr'] : [];
        $this->la_append_label = (isset($la_rates['append_label'])) ? $la_rates['append_label'] : "";
        $this->la_label_sufex = $this->la_label;
        $this->limited_cost = $this->la_costs;

        $this->la_costs = $this->calculate_markup_and_handling_fee($la_rates, $la_cost, $this->limited_cost, $handling_fee);

        return ['limited_cost' => $this->la_costs, 'minPrices' => $minPrices, 'en_fdo_meta_data' => $en_fdo_meta_data];
    }
    /**
     *add limited acees for multishipment option
     */
    public function add_limited_aceess_multiship_option_liftgate($quote_settings, $Ltl_Freight_Quotes, $simple_rates, $liftgate_delivery_option, $key, $is_prevent_resi_custom_exists, $index, $handling_fee, $simple_quotes, $quote, $ltl_package, $minPrices, $en_fdo_meta_data, $limited_cost) {
        $simple_rates = $Ltl_Freight_Quotes->calculate_quotes($quote, $quote_settings);
        $lal_rates = reset($simple_rates);
        $lal_rates = $this->limited_access_residential($lal_rates, $simple_quotes, $quote);
        $lal_rates_id = !empty($lal_rates['code']) && is_string($lal_rates['code']) ? $lal_rates['code'].$this->limited_access_liftgate : $this->limited_access_liftgate;
        $this->la_label_append = $this->label_with_liftgate;
        $lal_rates = $this->limited_access_label($lal_rates, $this->la_label_sufex, $this->la_label_append, $lal_rates_id);

        $lal_rates['label_sfx_arr'] = ['LA', 'L'];
        $lal_rates['label_sufex'] = ['LA', 'L'];
        // check liftgate surcharges index for legacy and new API
        $lfg_exist = isset($lal_rates['surcharges']['(FEE)Liftgate Delivery']) || isset($lal_rates['surcharges']['LIFTGATE DELIVERY']);
        if ($lfg_exist && isset($lal_rates['surcharges']['(FEE)Residential Delivery'])) {
            $lal_rates['label_sfx_arr'][] = 'R';
            $lal_rates['label_sufex'][] = 'R';
        }
        $minPrices['WWE_WLAL'][$key] = $lal_rates;

        if($is_prevent_resi_custom_exists && isset($ltl_package[$index]['packaging_fee_value'])){
            $lal_rates['cost'] = apply_filters('en_prevent_resi_add_packaging_fee_to_rates', $lal_rates['cost'], $ltl_package[$index]['packaging_fee_value']);
        }
        // FDO
        $en_fdo_meta_data['WWE_WLAL'][$key] = (isset($lal_rates['meta_data']['en_fdo_meta_data'])) ? $lal_rates['meta_data']['en_fdo_meta_data'] : [];
        $lal_cost = (isset($lal_rates['cost'])) ? $lal_rates['cost'] : 0;
        $this->la_label = (isset($lal_rates['label_sufex'])) ? $lal_rates['label_sufex']: [];
        $this->la_append_label = (isset($lal_rates['append_label'])) ? $lal_rates['append_label'] : "";
        $this->limited_cost = $this->lal_costs;

        $this->lal_costs = $this->calculate_markup_and_handling_fee($lal_rates, $lal_cost, $this->limited_cost, $handling_fee);

        return ['limited_cost' => $this->lal_costs,
            'minPrices' => $minPrices,
            'en_fdo_meta_data' => $en_fdo_meta_data,
            'label_arr' => $this->la_label,
            'label_append' => $this->la_append_label
            ];
    }
    /**
     * markup and hangling fee
     */
    public function calculate_markup_and_handling_fee($la_rates, $la_cost, $limited_cost, $handling_fee) {

        // Pricing per product
        $pricing_per_product = (isset($la_rates['pricing_per_product'])) ? $la_rates['pricing_per_product'] : 0;

        // Pricing per product
        $la_cost = $this->WC_speedfreight_Shipping_Method->calculate_markup($la_cost, $pricing_per_product);

        // product level markup
        if(!empty($la_rates['product_level_markup'])){
            $la_cost = $this->WC_speedfreight_Shipping_Method->add_handling_fee($la_cost, $la_rates['product_level_markup']);
        }
        
        // origin level markup
        if(!empty($la_rates['origin_markup'])){
            $la_cost = $this->WC_speedfreight_Shipping_Method->add_handling_fee($la_cost, $la_rates['origin_markup']);
        }

        $this->limited_cost += $this->WC_speedfreight_Shipping_Method->add_handling_fee($la_cost, $handling_fee);
        return $this->limited_cost;
    }
    /**
     * Remove limited access accesorials
     */
    public function remove_limited_access_accesorials($rates) {
        if(isset($rates['meta_data']['en_fdo_meta_data']['la_residential_status'], $rates['meta_data']['en_fdo_meta_data']['accessorials']['limited_access']) && $rates['meta_data']['en_fdo_meta_data']['la_residential_status'] == 'r') {
            $rates['meta_data']['en_fdo_meta_data']['accessorials']['limited_access'] = false;
        }
        return $rates;
    }
    /**
     *  limited access residential
     */
    public function limited_access_residential($la_val, $simple_quotes, $quote){
        if(isset($la_val['meta_data']['en_fdo_meta_data']['accessorials']) &&
            is_array($la_val['meta_data']['en_fdo_meta_data']['accessorials'])){
            $la_val['meta_data']['en_fdo_meta_data']['accessorials']['limited_access'] = 1;
        }
        return $la_val;
    }
    /**
     *  limited access liftgate
     */
    public function limited_access_liftgate($la_val, $simple_quotes, $quote){
        // check liftgate surcharges index for legacy and new API
        $lfg_exist = isset($la_val['surcharges']['(FEE)Liftgate Delivery']) || isset($la_val['surcharges']['LIFTGATE DELIVERY']);

        if ($lfg_exist && (!empty($simple_quotes) || !empty($quote))) {
            // check liftgate surcharges fee for legacy and new API
            $lfg_fee = isset($la_val['surcharges']['(FEE)Liftgate Delivery']) ? $la_val['surcharges']['(FEE)Liftgate Delivery'] : (isset($la_val['surcharges']['LIFTGATE DELIVERY']) ? $la_val['surcharges']['LIFTGATE DELIVERY'] : 0);
            $la_val['cost'] = $la_val['cost'] - $lfg_fee;
            $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_val['meta_data']['en_fdo_meta_data']['rate']['cost'] - $lfg_fee;

            if(isset($la_val['meta_data']['en_fdo_meta_data']['accessorials']) &&
                is_array($la_val['meta_data']['en_fdo_meta_data']['accessorials'])){
                $la_val['meta_data']['en_fdo_meta_data']['accessorials']['liftgate'] = false;
            }
        }
        return $la_val;
    }
    /**
     *  limited access label
     */
    public function limited_access_label($la_rates, $la_label_sufex, $la_label_append, $la_rates_id) {
        $la_rates['label_sufex'] = $la_label_sufex;
        $la_rates['append_label'] = $la_label_append;
        $la_rates['id'] = $la_rates_id;
        $la_rates['cost'] = $la_rates['cost'] + $this->limited_access_value;
        $la_rates['meta_data']['en_fdo_meta_data']['rate']['cost'] = $la_rates['meta_data']['en_fdo_meta_data']['rate']['cost'] + $this->limited_access_value;

        return $la_rates;
    }
    /**
     *  limited access accessortials
     */
    public function limited_access_label_sfx($la_rates) {
        empty($la_rates['label_sfx_arr']) ? $la_rates['label_sfx_arr'] = ['LA'] : array_push($la_rates['label_sfx_arr'], 'LA');

        $resi_key = array_search('L', $la_rates['label_sfx_arr'], true);
        if ($resi_key !== false) {
            unset($la_rates['label_sfx_arr'][$resi_key]);
        }
        return $la_rates;
    }
}