<?php
/**
 * WWE LTL Tab Class
 *
 * @package     WWE LTL Quotes
 * @author      Eniture-Technology
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WC_ltl_Settings_tabs
 */
class WC_ltl_Settings_tabs extends WC_Settings_Page
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = 'wwe_quests';
        add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
        add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));
        add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
        add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
    }

    /**
     * Add Setting Tab
     * @param $settings_tabs
     * @return array
     */
    public function add_settings_tab($settings_tabs)
    {
        $settings_tabs[$this->id] = __('Speedfreight', 'woocommerce-settings-wwe_quetes');
        return $settings_tabs;
    }

    /**
     * Get Section
     * @return array
     */
    public function get_sections()
    {

        $sections = array(
            '' => __('Connection Settings', 'woocommerce-settings-wwe_quetes'),
            'section-1' => __('Carriers', 'woocommerce-settings-wwe_quetes'),
            'section-2' => __('Quote Settings', 'woocommerce-settings-wwe_quetes'),
            'section-3' => __('Warehouses', 'woocommerce-settings-wwe_quetes'),
            'shipping-rules' => __('Shipping Rules', 'woocommerce-settings-wwe_quetes'),
            'section-5' => __('FreightDesk Online', 'woocommerce-settings-wwe_quetes'),
            'section-6' => __('Validate Addresses', 'woocommerce-settings-wwe_quetes'),
            'section-4' => __('User Guide', 'woocommerce-settings-wwe_quetes'),
        );

        // Logs data
        $enable_logs = get_option('en_enable_logs');
        if ($enable_logs == 'yes') {
            $sections['en-logs'] = 'Logs';
        }
        $sections = apply_filters('en_woo_addons_sections', $sections, en_woo_plugin_wwe_quests);
        // Standard Packaging
        $sections = apply_filters('en_woo_pallet_addons_sections', $sections, en_woo_plugin_wwe_quests);
        return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
    }

    /**
     * Warehouses
     */
    public function ltl_warehouse()
    {
        require_once 'warehouse-dropship/wild/warehouse/wwe_ltl_warehouse_template.php';
        require_once 'warehouse-dropship/wild/dropship/wwe_ltl_dropship_template.php';
    }

    /**
     * User Guide
     */
    public function ltl_user_guide()
    {

        include_once('template/guide.php');
    }

    /**
     * Setting Tab
     * @return array
     */
    public function ltl_section_setting_tab()
    {
        $default_api_endpoint = !empty(get_option('wc_settings_wwe_speed_freight_username')) ? 'wwe_ltl_old_api' : 'wwe_ltl_new_api';

        $settings = array(
            'section_title_wwe' => array(
                'name' => __('', 'woocommerce-settings-wwe_quetes'),
                'type' => 'title',
                'desc' => '<br> ',
                'id' => 'wc_settings_wwe_title_section_connection',
            ),
            'api_endpoint_wwe_ltl' => array(
                'name' => __('Which API will you connect to? ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'select',
                'default' => $default_api_endpoint,
                'id' => 'api_endpoint_wwe_ltl',
                'options' => array(
                    'wwe_ltl_old_api' => __('Legacy API', 'Legacy API'),
                    'wwe_ltl_new_api' => __('New API', 'New API'),
                )
            ),
            // New API
            'wc_settings_wwe_client_id' => array(
                'name' => __('Client ID ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_client_id',
                'class' => 'wwe_ltl_new_api_field'
            ),
            'wc_settings_wwe_client_secret' => array(
                'name' => __('Client Secret ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_client_secret',
                'class' => 'wwe_ltl_new_api_field'
            ),
            'speed_freight_new_username_wwe' => array(
                'name' => __('Username ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_new_speed_freight_username',
                'class' => 'wwe_ltl_new_api_field'
            ),
            'speed_freight_new_password_wwe' => array(
                'name' => __('Password ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_new_speed_freight_password',
                'class' => 'wwe_ltl_new_api_field'
            ),
            'world_wide_express_account_number_wwe' => array(
                'name' => __('Account Number ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_world_wide_express_account_number',
                'class' => 'wwe_ltl_old_api_field'
            ),
            'speed_freight_username_wwe' => array(
                'name' => __('Username ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_speed_freight_username',
                'class' => 'wwe_ltl_old_api_field'
            ),
            'speed_freight_password_wwe' => array(
                'name' => __('Password ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_speed_freight_password',
                'class' => 'wwe_ltl_old_api_field'
            ),
            'authentication_key_wwe' => array(
                'name' => __('Authentication Key ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_authentication_key',
                'class' => 'wwe_ltl_old_api_field'
            ),
            'plugin_licence_key' => array(
                'name' => __('Eniture API Key ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'text',
                'desc' => __('Obtain a Eniture API Key from <a href="https://eniture.com/woocommerce-worldwide-express-ltl-freight/" target="_blank" >eniture.com </a>', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_licence_key'
            ),
            'save_wwe_buuton' => array(
                'name' => __('Save Button ', 'woocommerce-settings-wwe_quetes'),
                'type' => 'button',
                'desc' => __('', 'woocommerce-settings-wwe_quetes'),
                'id' => 'wc_settings_wwe_button'
            ),
            'section_end_wwe' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_wwe_end-section_connection'
            ),
        );
        return $settings;
    }

    /**
     * Get Settings
     * @param $section
     * @return array
     * @global $wpdb
     */
    public function get_settings($section = null)
    {
        ob_start();
        $settings = [];
        switch ($section) {
            case 'section-0' :
                echo '<div class="ltl_connection_section_class">';
                $settings = $this->ltl_section_setting_tab();
                break;
            case 'section-1':
                echo '<div class="carrier_section_class">';
                ?>
                <div class="carrier_section_class wrap woocommerce">
                    <p>
                        Identifies which carriers are included in the quote response, not what is displayed in the
                        shopping cart. Identify what displays in the shopping cart in the Quote Settings. For example,
                        you may include quote responses from all carriers, but elect to only show the cheapest three in
                        the shopping cart. <br> <br>
                        Not all carriers service all origin and destination points. If a carrier doesn`t service the
                        ship to address, it is automatically omitted from the quote response. Consider conferring with
                        your Worldwide Express representative if you`d like to narrow the number of carrier responses.
                        <br> <br> <br>
                    </p>
                    <table>
                        <tbody>
                        <thead>
                        <tr class="WWE_even_odd_class">
                            <th class="WWE_carrier_carrier">Carrier Name</th>
                            <th class="WWE__carrier_logo">Logo</th>
                            <th class="WWE_carrier_include"><input type="checkbox" name="include_all"
                                                                   class="include_all"/></th>
                        </tr>
                        </thead>
                        <?php
                        global $wpdb;
                        $all_freight_array = [];
                        $count_carrier = 1;
                        $ltl_freight_all = $wpdb->get_results('SELECT * FROM ' . WWE_CARRIERS . ' group by speed_freight_carrierSCAC order by speed_freight_carrierName ASC');
                        foreach ($ltl_freight_all as $ltl_freight_value):
                            ?>
                            <tr <?php
                            if ($count_carrier % 2 == 0) {

                                echo 'class="WWE_even_odd_class"';
                            }
                            ?> >

                                <td class="WWE_carrier_Name_td">
                                    <?php echo $ltl_freight_value->speed_freight_carrierName; ?>
                                </td>
                                <td>
                                    <img src="<?php echo plugins_url('Carrier_Logos/' . $ltl_freight_value->carrier_logo, __FILE__) ?> ">
                                </td>
                                <td>
                                    <input <?php
                                    if ($ltl_freight_value->carrier_status == '1') {
                                        echo 'checked="checked"';
                                    }
                                    ?>
                                            name="<?php echo $ltl_freight_value->speed_freight_carrierSCAC . $ltl_freight_value->id; ?>"
                                            class="carrier_check"
                                            id="<?php echo $ltl_freight_value->speed_freight_carrierSCAC . $ltl_freight_value->id; ?>"
                                            type="checkbox">
                                </td>
                            </tr>
                            <?php
                            $count_carrier++;
                        endforeach;
                        ?>
                        <input name="action" value="save_carrier_status" type="hidden"/>
                        </tbody>
                    </table>
                </div>
                <?php
                break;

            case 'section-2':

                $disable_hold_at_terminal = "";
                $hold_at_terminal_package_required = "";

                $action_hold_at_terminal = apply_filters('wwe_quests_quotes_plans_suscription_and_features', 'hold_at_terminal');
                if (is_array($action_hold_at_terminal)) {
                    $disable_hold_at_terminal = "disabled_me";
                    $hold_at_terminal_package_required = apply_filters('wwe_quests_plans_notification_link', $action_hold_at_terminal);
                }

                // Cuttoff Time
                $wwe_lfq_disable_cutt_off_time_ship_date_offset = "";
                $wwe_lfq_cutt_off_time_package_required = "";                
                //  Check the cutt of time & offset days plans for disable input fields
                $wwe_lfq_action_cutOffTime_shipDateOffset = apply_filters('wwe_quests_quotes_plans_suscription_and_features', 'wwe_lfq_cutt_off_time');
                if (is_array($wwe_lfq_action_cutOffTime_shipDateOffset)) {
                    $wwe_lfq_disable_cutt_off_time_ship_date_offset = "disabled_me";
                    $wwe_lfq_cutt_off_time_package_required = apply_filters('wwe_quests_plans_notification_link', $wwe_lfq_action_cutOffTime_shipDateOffset);
                }

                $ltl_enable = get_option('en_plugins_return_LTL_quotes');
                $weight_threshold_class = $ltl_enable == 'yes' ? 'show_en_weight_threshold_lfq' : 'hide_en_weight_threshold_lfq';
                $weight_threshold = get_option('en_weight_threshold_lfq');
                $weight_threshold = isset($weight_threshold) && $weight_threshold > 0 ? $weight_threshold : 150;

                echo '<div class="quote_section_class_ltl">';
                $settings = array(
                    'section_title_quote' => array(
                        'title' => __('', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'wc_settings_wwe_section_title_quote'
                    ),
                    'rating_method_wwe' => array(
                        'name' => __('Rating Method ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'select',
                        'desc' => __('Displays only the cheapest returned Rate.', 'woocommerce-settings-wwe_quetes'),
                        'id' => 'wc_settings_wwe_rate_method',
                        'options' => array(
                            'Cheapest' => __('Cheapest', 'Cheapest'),
                            'cheapest_options' => __('Cheapest Options', 'cheapest_options'),
                            'average_rate' => __('Average Rate', 'average_rate')
                        )
                    ),
                    'number_of_options_wwe' => array(
                        'name' => __('Number Of Options ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'select',
                        'default' => '3',
                        'desc' => __('Number of options to display in the shopping cart.', 'woocommerce-settings-wwe_quetes'),
                        'id' => 'wc_settings_wwe_Number_of_options',
                        'options' => array(
                            '1' => __('1', '1'),
                            '2' => __('2', '2'),
                            '3' => __('3', '3'),
                            '4' => __('4', '4'),
                            '5' => __('5', '5'),
                            '6' => __('6', '6'),
                            '7' => __('7', '7'),
                            '8' => __('8', '8'),
                            '9' => __('9', '9'),
                            '10' => __('10', '10')
                        )
                    ),
                    'label_as_wwe' => array(
                        'name' => __('Label As ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'desc' => __('What The User Sees During Checkout, e.g "Freight" Leave Blank to Display The Carrier Name.', 'woocommerce-settings-wwe_quetes'),
                        'id' => 'wc_settings_wwe_label_as'
                    ),
                    'price_sort_wwe_ltl' => array(
                        'name' => __("Don't sort shipping methods by price  ", 'woocommerce-settings-wwe_ltl_quotes'),
                        'type' => 'checkbox',
                        'desc' => 'By default, the plugin will sort all shipping methods by price in ascending order.',
                        'id' => 'shipping_methods_do_not_sort_by_price'
                    ),
                    //** Start Delivery Estimate Options - Cuttoff Time
                    'service_wwe_lfq_estimates_title' => array(
                        'name' => __('Delivery Estimate Options ', 'woocommerce-settings-en_woo_addons_packages_quotes'),
                        'type' => 'text',
                        'desc' => '',
                        'id' => 'service_wwe_lfq_estimates_title'
                    ),
                    'wwe_lfq_show_delivery_estimates_options_radio' => array(
                        'name' => __("", 'woocommerce-settings-wwe_lfq'),
                        'type' => 'radio',
                        'default' => 'dont_show_estimates',
                        'options' => array(
                            'dont_show_estimates' => __("Don't display delivery estimates.", 'woocommerce'),
                            'delivery_days' => __("Display estimated number of days until delivery.", 'woocommerce'),
                            'delivery_date' => __("Display estimated delivery date.", 'woocommerce'),
                        ),
                        'id' => 'wwe_lfq_delivery_estimates',
                        'class' => 'wwe_lfq_dont_show_estimate_option',
                    ),
                    //** End Delivery Estimate Options
                    //**Start: Cut Off Time & Ship Date Offset
                    'cutOffTime_shipDateOffset_wwe_lfq_freight' => array(
                        'name' => __('Cut Off Time & Ship Date Offset ', 'woocommerce-settings-en_woo_addons_packages_quotes'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'desc' => $wwe_lfq_cutt_off_time_package_required,
                        'id' => 'wwe_lfq_freight_cutt_off_time_ship_date_offset'
                    ),
                    'orderCutoffTime_wwe_lfq_freight' => array(
                        'name' => __('Order Cut Off Time ', 'woocommerce-settings-wwe_lfq_freight_freight_orderCutoffTime'),
                        'type' => 'text',
                        'placeholder' => '-- : -- --',
                        'desc' => 'Enter the cut off time (e.g. 2.00) for the orders. Orders placed after this time will be quoted as shipping the next business day.',
                        'id' => 'wwe_lfq_freight_order_cut_off_time',
                        'class' => $wwe_lfq_disable_cutt_off_time_ship_date_offset,
                    ),
                    'shipmentOffsetDays_wwe_lfq_freight' => array(
                        'name' => __('Fullfillment Offset Days ', 'woocommerce-settings-wwe_lfq_freight_shipment_offset_days'),
                        'type' => 'text',
                        'desc' => 'The number of days the ship date needs to be moved to allow the processing of the order.',
                        'placeholder' => 'Fullfillment Offset Days, e.g. 2',
                        'id' => 'wwe_lfq_freight_shipment_offset_days',
                        'class' => $wwe_lfq_disable_cutt_off_time_ship_date_offset,
                    ),
                    'all_shipment_days_wwe_lfq' => array(
                        'name' => __("What days do you ship orders?", 'woocommerce-settings-wwe_lfq_quotes'),
                        'type' => 'checkbox',
                        'desc' => 'Select All',
                        'class' => "all_shipment_days_wwe_lfq $wwe_lfq_disable_cutt_off_time_ship_date_offset",
                        'id' => 'all_shipment_days_wwe_lfq'
                    ),
                    'monday_shipment_day_wwe_lfq' => array(
                        'name' => __("", 'woocommerce-settings-wwe_lfq_quotes'),
                        'type' => 'checkbox',
                        'desc' => 'Monday',
                        'class' => "wwe_lfq_shipment_day $wwe_lfq_disable_cutt_off_time_ship_date_offset",
                        'id' => 'monday_shipment_day_wwe_lfq'
                    ),
                    'tuesday_shipment_day_wwe_lfq' => array(
                        'name' => __("", 'woocommerce-settings-wwe_lfq_quotes'),
                        'type' => 'checkbox',
                        'desc' => 'Tuesday',
                        'class' => "wwe_lfq_shipment_day $wwe_lfq_disable_cutt_off_time_ship_date_offset",
                        'id' => 'tuesday_shipment_day_wwe_lfq'
                    ),
                    'wednesday_shipment_day_wwe_lfq' => array(
                        'name' => __("", 'woocommerce-settings-wwe_lfq_quotes'),
                        'type' => 'checkbox',
                        'desc' => 'Wednesday',
                        'class' => "wwe_lfq_shipment_day $wwe_lfq_disable_cutt_off_time_ship_date_offset",
                        'id' => 'wednesday_shipment_day_wwe_lfq'
                    ),
                    'thursday_shipment_day_wwe_lfq' => array(
                        'name' => __("", 'woocommerce-settings-wwe_lfq_quotes'),
                        'type' => 'checkbox',
                        'desc' => 'Thursday',
                        'class' => "wwe_lfq_shipment_day $wwe_lfq_disable_cutt_off_time_ship_date_offset",
                        'id' => 'thursday_shipment_day_wwe_lfq'
                    ),
                    'friday_shipment_day_wwe_lfq' => array(
                        'name' => __("", 'woocommerce-settings-wwe_lfq_quotes'),
                        'type' => 'checkbox',
                        'desc' => 'Friday',
                        'class' => "wwe_lfq_shipment_day $wwe_lfq_disable_cutt_off_time_ship_date_offset",
                        'id' => 'friday_shipment_day_wwe_lfq'
                    ),
                    'show_delivery_estimate_wwe' => array(
                        'title' => __('', 'woocommerce'),
                        'name' => __('', 'woocommerce-settings-wwe_lfq_quotes'),
                        'desc' => '',
                        'id' => 'wwe_lfq_show_delivery_estimates',
                        'css' => '',
                        'default' => '',
                        'type' => 'title',
                    ),
                    //**End: Cut Off Time & Ship Date Offset
                    'Services_to_include_in_quoted_price_wwe' => array(
                        'title' => __('', 'woocommerce'),
                        'name' => __('', 'woocommerce-settings-wwe_quetes'),
                        'desc' => '',
                        'id' => 'woocommerce_wwe_specific_Qurt_Price',
                        'css' => '',
                        'default' => '',
                        'type' => 'title'
                    ),
                    'residential_delivery_options_label' => array(
                        'name' => __('Residential Delivery', 'woocommerce-settings-wwe_small_packages_quotes'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'id' => 'residential_delivery_options_label'
                    ),
                    'residential_delivery_wwe' => array(
                        'name' => __('Always quote as residential delivery ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'checkbox',
                        'desc' => '',
                        'id' => 'wc_settings_wwe_residential_delivery'
                    ),
                    // Auto-detect residential addresses notification
                    'avaibility_auto_residential' => array(
                        'name' => __('Auto-detect residential addresses', 'woocommerce-settings-wwe_small_packages_quotes'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'desc' => "Click <a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/'>here</a> to add the Residential Address Detection module. (<a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/#documentation'>Learn more</a>)",
                        'id' => 'avaibility_auto_residential'
                    ),
                    'liftgate_delivery_options_label' => array(
                        'name' => __('Lift Gate Delivery ', 'woocommerce-settings-en_woo_addons_packages_quotes'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'id' => 'liftgate_delivery_options_label'
                    ),
                    'lift_gate_delivery_wwe' => array(
                        'name' => __('Always quote lift gate delivery ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'checkbox',
                        'desc' => '',
                        'id' => 'wc_settings_wwe_lift_gate_delivery',
                        'class' => 'accessorial_service checkbox_fr_add',
                    ),
                    'wwe_quests_liftgate_delivery_as_option' => array(
                        'name' => __('Offer lift gate delivery as an option ', 'woocommerce-settings-wwe_freight'),
                        'type' => 'checkbox',
                        'desc' => __('', 'woocommerce-settings-fedex_freight'),
                        'id' => 'wwe_quests_liftgate_delivery_as_option',
                        'class' => 'accessorial_service checkbox_fr_add',
                    ),
                    // Use my liftgate notification
                    'avaibility_lift_gate' => array(
                        'name' => __('Always include lift gate delivery when a residential address is detected', 'woocommerce-settings-wwe_small_packages_quotes'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'desc' => "Click <a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/'>here</a> to add the Residential Address Detection module. (<a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/#documentation'>Learn more</a>)",
                        'id' => 'avaibility_lift_gate'
                    ),
                    // start notify delivery
                    'notify_delivery_options_label' => array(
                        'name' => __('Notify Before Delivery ', 'woocommerce-settings-en_woo_addons_packages_quotes'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'id' => 'liftgate_delivery_options_label'
                    ),
                    'wwe_quests_notify_delivery_as_option' => array(
                        'name' => __('Always notify before delivery ', 'woocommerce-settings-fedex_freight'),
                        'type' => 'checkbox',
                        'desc' => __('', 'woocommerce-settings-fedex_freight'),
                        'id' => 'wwe_quests_notify_delivery_as_option',
                        'class' => 'accessorial_service checkbox_fr_add',
                    ),
                    // end notify delivery
                    'speed_freight_limited_access_delivery_label' => [
                        'name' => __("Limited Access Delivery", 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'desc' => '',
                        'id' => 'speed_freight_limited_access_delivery_label'
                    ],
                    'speed_freight_limited_access_delivery' => [
                        'name' => __("Always quote limited access delivery", 'woocommerce-settings-wwe_quetes'),
                        'type' => 'checkbox',
                        'id' => 'speed_freight_limited_access_delivery',
                        'class' => "limited_access_add",
                    ],
                    'speed_freight_limited_access_delivery_as_option' => [
                        'name' => __("Offer limited access delivery as an option", 'woocommerce-settings-wwe_quetes'),
                        'type' => 'checkbox',
                        'id' => 'speed_freight_limited_access_delivery_as_option',
                        'class' => "limited_access_add",
                    ],
                    'speed_freight_limited_access_delivery_fee' => [
                        'name' => __("Limited access delivery fee", 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'id' => 'speed_freight_limited_access_delivery_fee',
                        'class' => "",
                    ],
                    // Start Hot At Terminal
                    'wwe_ltl_hold_at_terminal_checkbox_status' => array(
                        'name' => __('Hold At Terminal', 'woocommerce-settings-fedex_small'),
                        'type' => 'checkbox',
                        'desc' => 'Offer Hold At Terminal as an option ' . $hold_at_terminal_package_required,
                        'class' => $disable_hold_at_terminal,
                        'id' => 'wwe_ltl_hold_at_terminal_checkbox_status',
                    ),
                    'wwe_ltl_hold_at_terminal_fee' => array(
                        'name' => __('', 'ground-transit-settings-ground_transit'),
                        'type' => 'text',
                        'desc' => 'Adjust the price of the Hold At Terminal option.Enter an amount, e.g. 3.75, or a percentage, e.g. 5%.  Leave blank to use the price returned by the carrier.',
                        'class' => $disable_hold_at_terminal,
                        'id' => 'wwe_ltl_hold_at_terminal_fee'
                    ),
                    //insurance dropdown
                    'wc_settings_wwe_insurance' => array(
                        'name' => __('Insurance Category List', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'select',
                        'default' => 'general_merchandise',
                        'id' => 'wc_settings_wwe_insurance',
                        'options' => array(
                            'general_merchandise' => __('General Merchandise', 'general_merchandise'),
                            'commercial_electronics' => __('Commercial Electronics (Audio; Computer: Hardware, Servers, Parts & Accessories)', 'commercial_electronics'),
                            'consumer_electronics' => __('Consumer Electronics (laptops, cellphones, PDAs, iPads, tablets, notebooks, etc.)', 'consumer_electronics'),
                            'fragile_goods' => __('Fragile Goods (Glass, Ceramic, Porcelain, etc.)', 'fragile_goods'),
                            'Furniture' => __('Furniture (Pianos, Glassware, Tableware, Outdoor Furniture)', 'Furniture'),
                            'Machinery' => __('Machinery, Appliances and Equipment (Medical, Restaurant, Industrial, Scientific)', 'Machinery'),
                            'Miscellaneous' => __('Miscellaneous / Other / Mixed', 'Miscellaneous'),
                            'Beverages' => __('Non-Perishable Foods / Beverages / Commodities / Vitamins', 'Beverages'),
                            'Radioactive' => __('Radioactive / Hazardous / Restricted or Controlled Items', 'Radioactive'),
                            'sewing_machines' => __('Sewing Machines, Equipment and Accessories', 'sewing_machines'),
                            'Wine' => __('Wine / Spirits / Alcohol / Beer', 'Wine'),
                        )
                    ),
                    // Handling Weight
                    'wwe_label_handling_unit' => array(
                        'name' => __('Handling Unit ', 'wwe_freight_wc_settings'),
                        'type' => 'text',
                        'class' => 'hidden',
                        'id' => 'wwe_label_handling_unit'
                    ),
                    'wwe_freight_handling_weight' => array(
                        'name' => __('Weight of Handling Unit  ', 'wwe_freight_wc_settings'),
                        'type' => 'text',
                        'desc' => 'Enter in pounds the weight of your pallet, skid, crate or other type of handling unit.',
                        'id' => 'wwe_freight_handling_weight'
                    ),
                    // max Handling Weight
                    'wwe_freight_maximum_handling_weight' => array(
                        'name' => __('Maximum Weight per Handling Unit  ', 'wwe_freight_wc_settings'),
                        'type' => 'text',
                        'desc' => 'Enter in pounds the maximum weight that can be placed on the handling unit.',
                        'id' => 'wwe_freight_maximum_handling_weight'
                    ),
                    'hand_free_mark_up_wwe' => array(
                        'name' => __('Handling Fee / Markup ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'desc' => 'Amount excluding tax. Enter an amount, e.g 3.75, or a percentage, e.g, 5%. Leave blank to disable.',
                        'id' => 'wc_settings_wwe_hand_free_mark_up'
                    ),
                    'en_enable_logs' => [
                        'name' => __("Enable Logs  ", 'woocommerce-settings-wwe_quetes'),
                        'type' => 'checkbox',
                        'desc' => __("When checked, the Logs page will contain up to 25 of the most recent transactions.", 'woocommerce-settings-wwe_quetes'),
                        'id' => 'en_enable_logs'
                    ],

                    //Ignore items with the following Shipping Class(es) By (K)
                    'en_ignore_items_through_freight_classification' => array(
                        'name' => __('Ignore items with the following Shipping Class(es)', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'desc' => "Enter the <a target='_blank' href = '" . get_admin_url() . "admin.php?page=wc-settings&tab=shipping&section=classes'>Shipping Slug</a> you'd like the plugin to ignore. Use commas to separate multiple Shipping Slug.",
                        'id' => 'en_ignore_items_through_freight_classification'
                    ),
                    'allow_for_own_arrangment_wwe' => array(
                        'name' => __('Allow For Own Arrangement ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'checkbox',
                        'desc' => __('<span class="description">Adds an option in the shipping cart for users to indicate that they will make and pay for their own LTL shipping arrangements.</span>', 'woocommerce-settings-wwe_quetes'),
                        'id' => 'wc_settings_wwe_allow_for_own_arrangment'
                    ),
                    'text_for_own_arrangment_wwe' => array(
                        'name' => __('Text For Own Arrangement ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'desc' => '',
                        'default' => "I'll arrange my own freight",
                        'id' => 'wc_settings_wwe_text_for_own_arrangment'
                    ),
                    'allow_other_plugins' => array(
                        'name' => __('Show WooCommerce Shipping Options ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'select',
                        'default' => '3',
                        'desc' => __('Enabled options on WooCommerce Shipping page are included in quote results.', 'woocommerce-settings-wwe_quetes'),
                        'id' => 'wc_settings_wwe_allow_other_plugins',
                        'options' => array(
                            'yes' => __('YES', 'YES'),
                            'no' => __('NO', 'NO'),
                        )
                    ),
                    'return_LTL_quotes_wwe' => array(
                        'name' => __("Return LTL quotes when an order parcel shipment weight exceeds the weight threshold ", 'woocommerce-settings-wwe_quetes'),
                        'type' => 'checkbox',
                        'desc' => "<span class='description' >When checked, the LTL Freight Quote will return quotes when an order's total weight exceeds the weight threshold (the maximum permitted by WWE and UPS), even if none of the products have settings to indicate that it will ship LTL Freight. To increase the accuracy of the returned quote(s), all products should have accurate weights and dimensions. </span>",
                        'id' => 'en_plugins_return_LTL_quotes'
                    ),
                    // Weight threshold for LTL freight
                    'en_weight_threshold_lfq' => [
                        'name' => __('Weight threshold for LTL Freight Quotes ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'default' => $weight_threshold,
                        'class' => $weight_threshold_class,
                        'id' => 'en_weight_threshold_lfq'
                    ],
                    'en_suppress_parcel_rates' => array(
                        'name' => __("", 'woocommerce-settings-wwe_quetes'),
                        'type' => 'radio',
                        'default' => 'display_parcel_rates',
                        'options' => array(
                            'display_parcel_rates' => __("Continue to display parcel rates when the weight threshold is met.", 'woocommerce'),
                            'suppress_parcel_rates' => __("Suppress parcel rates when the weight threshold is met.", 'woocommerce'),
                        ),
                        'class' => 'en_suppress_parcel_rates',
                        'id' => 'en_suppress_parcel_rates',
                    ),
                    'error_management_label_wwe_ltl' => array(
                        'name' => __('Error management ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'text',
                        'id' => 'error_management_label_wwe_ltl',
                        'class' => 'hidden',
                    ),
                    'error_management_settings_wwe_ltl' => array(
                        'name' => __('', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'radio',
                        'default' => 'quote_shipping',
                        'options' => array(
                            'quote_shipping' => __('Quote shipping using known shipping parameters, even if other items are missing shipping parameters.', 'woocommerce'),
                            'dont_quote_shipping' => __('Don\'t quote shipping if one or more items are missing the required shipping parameters.', 'woocommerce'),
                        ),
                        'id' => 'error_management_settings_wwe_ltl',
                    ),
                    'unable_retrieve_shipping_clear_wwe' => array(
                        'title' => __('', 'woocommerce'),
                        'name' => __('', 'woocommerce-settings-wwe-quotes'),
                        'desc' => '',
                        'id' => 'unable_retrieve_shipping_clear_wwe',
                        'css' => '',
                        'default' => '',
                        'type' => 'title',
                    ),
                    'unable_retrieve_shipping_wwe' => array(
                        'name' => __('Checkout options if the plugin fails to return a rate ', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'title',
                        'desc' => '<span>When the plugin is unable to retrieve shipping quotes and no other shipping options are provided by an alternative source:</span>',
                        'id' => 'wc_settings_unable_retrieve_shipping_wwe',
                    ),
                    'pervent_checkout_proceed_wwe' => array(
                        'name' => __('', 'woocommerce-settings-wwe_quetes'),
                        'type' => 'radio',
                        'id' => 'pervent_checkout_proceed_wwe_packages',
                        'options' => array(
                            'backup_rates' => __('', 'woocommerce'),
                            'allow' => __('', 'woocommerce'),
                            'prevent' => __('', 'woocommerce'),
                        ),
                        'id' => 'wc_pervent_proceed_checkout_eniture',
                    ),
                    'section_end_quote' => array(
                        'type' => 'sectionend',
                        'id' => 'wc_settings_quote_section_end'
                    )
                );
                break;

            case 'section-3' :
                $this->ltl_warehouse();
                $settings = [];
                break;

            case 'shipping-rules':
                $this->shipping_rules_section();
                $settings = [];
                break;

            case 'section-4' :
                $this->ltl_user_guide();
                $settings = [];
                break;

            case 'section-5' :
                $this->freightdesk_online_section();
                $settings = [];
                break;

            case 'section-6' :
                $this->validate_addresses_section();
                $settings = [];
                break;

            case 'en-logs' :
                require_once 'logs/en-logs.php';
                $settings = [];
                break;

            default:
                echo '<div class="ltl_connection_section_class">';
                $settings = $this->ltl_section_setting_tab();
                break;
        }

        $settings = apply_filters('en_woo_addons_settings', $settings, $section, en_woo_plugin_wwe_quests);
        // Standard Packaging
        $settings = apply_filters('en_woo_pallet_addons_settings', $settings, $section, en_woo_plugin_wwe_quests);
        $settings = $this->avaibility_addon($settings);
        return apply_filters('woocommerce-settings-wwe_quetes', $settings, $section);
    }

    /**
     * avaibility_addon
     * @param array type $settings
     * @return array type
     */
    function avaibility_addon($settings)
    {
        if (is_plugin_active('residential-address-detection/residential-address-detection.php')) {
            unset($settings['avaibility_lift_gate']);
            unset($settings['avaibility_auto_residential']);
        }

        return $settings;
    }

    /**
     * Output
     * @global $current_section
     */
    public function output()
    {
        global $current_section;
        $settings = $this->get_settings($current_section);
        WC_Admin_Settings::output_fields($settings);
    }

    /**
     * Save
     * @global $current_section
     */
    public function save()
    {
        global $current_section;
        if ($current_section != 'section-1') {
            $settings = $this->get_settings($current_section);
            // Cuttoff Time
            if (isset($_POST['wwe_lfq_freight_order_cut_off_time']) && $_POST['wwe_lfq_freight_order_cut_off_time'] != '') {
                $time_24_format = $this->wwe_lfq_get_time_in_24_hours($_POST['wwe_lfq_freight_order_cut_off_time']);
                $_POST['wwe_lfq_freight_order_cut_off_time'] = $time_24_format;
            }

            if (isset($_POST['eniture_backup_rates']) && !empty($_POST['eniture_backup_rates'])) {
                update_option('eniture_backup_rates', $_POST['eniture_backup_rates']);
            }
    
            if (isset($_POST['eniture_backup_rates_amount']) && !empty($_POST['eniture_backup_rates_amount'])) {
                update_option('eniture_backup_rates_amount', $_POST['eniture_backup_rates_amount']);
            }    

            WC_Admin_Settings::save_fields($settings);
        }
    }

    /**
     * Cuttoff Time
     * @param $timeStr
     * @return false|string
     */
    public function wwe_lfq_get_time_in_24_hours($timeStr)
    {
        $cutOffTime = explode(' ', $timeStr);
        $hours = $cutOffTime[0];
        $separator = $cutOffTime[1];
        $minutes = $cutOffTime[2];
        $meridiem = $cutOffTime[3];
        $cutOffTime = "{$hours}{$separator}{$minutes} $meridiem";
        return date("H:i", strtotime($cutOffTime));
    }

    /**
     * FreightDesk Online section
     */
    public function freightdesk_online_section()
    {

        include_once('fdo/freightdesk-online-section.php');
    }

    /**
     * Validate Addresses Section
     */
    public function validate_addresses_section()
    {

        include_once('fdo/validate-addresses-section.php');
    }

    public function shipping_rules_section() 
    {
        include_once plugin_dir_path(__FILE__) . 'shipping-rules/shipping-rules-template.php';
    }

}

return new WC_ltl_Settings_tabs();
