<?php

/**
 * Product detail page.
 */

/**
 * Add and show simple and variable products.
 * Class EnWweFreightProductDetail
 * @package EnWweFreightProductDetail
 */
if (!class_exists('EnWweFreightProductDetail')) {

    class EnWweFreightProductDetail
    {
        // Hazardous
        public $hazardous_disabled_plan = '';
        public $hazardous_plan_required = '';
        public $is_woo_paymant_installed = false;

        /**
         * Hook for call.
         * EnWweFreightProductDetail constructor.
         */
        public function __construct()
        {
            add_filter('en_app_common_plan_status', [$this, 'en_wwe_freight_plan_status'], 10, 1);

            // Check compatible with optimized product fields methods.
            add_filter('en_compatible_optimized_product_options', [$this, 'en_compatible_other_eniture_plugins']);

            if (!has_filter('En_Plugins_dropship_filter') &&
                !has_filter('En_Plugins_variable_freight_classification_filter')) {
                $this->check_if_woo_payment_and_subscription_plugin_installed();
                // Add simple product fields
                add_action('woocommerce_product_options_shipping', [$this, 'en_show_product_fields'], 101, 3);
                add_action('woocommerce_process_product_meta', [$this, 'en_save_product_fields'], 101, 1);

                // Add variable product fields.
                add_action('woocommerce_product_after_variable_attributes', [$this, 'en_show_product_fields_variable'], 101, 3);
                add_action('woocommerce_save_product_variation', [$this, 'en_save_product_fields'], 101, 1);

                // Check compatible with our old eniture plugins.
                add_filter('En_Plugins_dropship_filter', [$this, 'en_compatible_other_eniture_plugins']);
                add_filter('En_Plugins_variable_freight_classification_filter', [$this, 'en_compatible_other_eniture_plugins']);
            }
        }

        /**
         * Transportation insight plan status
         * @param array $plan_status
         * @return array
         */
        public function en_wwe_freight_plan_status($plan_status)
        {
            $en_plugin_name = 'WooCommerce WWE LTL Quotes';

            // Hazardous plan status
            $plan_required = '0';
            $hazardous_material_status = $en_plugin_name . ': Enabled.';
            $hazardous_material = apply_filters("wwe_quests_quotes_plans_suscription_and_features", 'hazardous_material');
            if (is_array($hazardous_material)) {
                $plan_required = '1';
                $hazardous_material_status = $en_plugin_name . ': Upgrade to Standard Plan to enable.';
            }

            $plan_status['hazardous_material']['wwe_freight'][] = 'wwe_freight';
            $plan_status['hazardous_material']['plan_required'][] = $plan_required;
            $plan_status['hazardous_material']['status'][] = $hazardous_material_status;

            // Nesting plan status
            $plan_required = '0';
            $nesting_material_status = $en_plugin_name . ': Enabled.';
            $nesting_material = apply_filters("wwe_quests_quotes_plans_suscription_and_features", 'nested_material');
            if (is_array($nesting_material)) {
                $plan_required = '1';
                $nesting_material_status = $en_plugin_name . ': Upgrade to Advanced Plan to enable.';
            }

            $plan_status['nesting']['wwe_freight'][] = 'wwe_freight';
            $plan_status['nesting']['plan_required'][] = $plan_required;
            $plan_status['nesting']['status'][] = $nesting_material_status;

            return $plan_status;
        }

        /**
         * Restrict to show duplicate fields on product detail page.
         */
        public function en_compatible_other_eniture_plugins()
        {
            return true;
        }

        /**
         * Show product fields simple product.
         * @param array $loop
         * @param array $variation_data
         * @param array $variation
         */
        public function en_show_product_fields($loop, $variation_data = [], $variation = [])
        {
            if ($this->is_woo_paymant_installed) {
                echo '</div>';
                    $this->create_en_custom_product_fields($variation);
                echo '<div>';
            }else{
                $this->create_en_custom_product_fields($variation);
            }
            
        }
        /**
         * Method to create product fields
         */
        public function create_en_custom_product_fields($variation, $is_variable = false){
            $postId = (isset($variation->ID)) ? $variation->ID : get_the_ID();
            $this->en_custom_product_fields($postId, $is_variable);
        }

        /**
         * Show product fields in variation product.
         * @param array $loop
         * @param array $variation_data
         * @param array $variation
         */
        public function en_show_product_fields_variable($loop, $variation_data = [], $variation = [])
        {
            $this->create_en_custom_product_fields($variation, true);
        }

        /**
         * Save the simple product fields.
         * @param int $postId
         */
        public function en_save_product_fields($postId)
        {
            if (isset($postId) && $postId > 0) {
                $en_product_fields = $this->en_product_fields_arr();

                foreach ($en_product_fields as $key => $custom_field) {
                    $custom_field = (isset($custom_field['id'])) ? $custom_field['id'] : '';
                    $en_updated_product = (isset($_POST[$custom_field][$postId])) ? sanitize_text_field($_POST[$custom_field][$postId]) : '';
                    $en_updated_product = $custom_field == '_dropship_location' ?
                        (maybe_serialize(is_array($en_updated_product) ? array_map('intval', $en_updated_product) : $en_updated_product)) : esc_attr($en_updated_product);
                    update_post_meta($postId, $custom_field, $en_updated_product);
                }
            }
        }

        /**
         * Static values for freight classification
         * @return array
         */
        public function en_freight_classification()
        {
            $classification = [
                '0' => __('No Freight Class', 'woocommerce'),
                '50' => __('50', 'woocommerce'),
                '55' => __('55', 'woocommerce'),
                '60' => __('60', 'woocommerce'),
                '65' => __('65', 'woocommerce'),
                '70' => __('70', 'woocommerce'),
                '77.5' => __('77.5', 'woocommerce'),
                '85' => __('85', 'woocommerce'),
                '92.5' => __('92.5', 'woocommerce'),
                '100' => __('100', 'woocommerce'),
                '110' => __('110', 'woocommerce'),
                '125' => __('125', 'woocommerce'),
                '150' => __('150', 'woocommerce'),
                '175' => __('175', 'woocommerce'),
                '200' => __('200', 'woocommerce'),
                '225' => __('225', 'woocommerce'),
                '250' => __('250', 'woocommerce'),
                '300' => __('300', 'woocommerce'),
                '400' => __('400', 'woocommerce'),
                '500' => __('500', 'woocommerce'),
                'DensityBased' => __('Density Based', 'woocommerce')
            ];
            return $classification;
        }

        /**
         * Created dropship list get from db
         * @return array
         */
        public function en_dropship_list()
        {
            $dropship = $this->get_data(['location' => 'dropship']);
            $en_dropship_list = [];
            foreach ($dropship as $list) {
                $en_nickname = (isset($list['nickname']) && strlen($list['nickname']) > 0) ? $list['nickname'] . ' - ' : '';
                $en_country = (isset($list['country']) && strlen($list['country']) > 0) ? '(' . $list['country'] . ')' : '';
                $en_zip = (isset($list['zip']) && strlen($list['zip']) > 0) ? $list['zip'] : '';
                $en_city = (isset($list['city']) && strlen($list['city']) > 0) ? $list['city'] : '';
                $en_state = (isset($list['state']) && strlen($list['state']) > 0) ? $list['state'] : '';
                $location = "$en_nickname $en_zip, $en_city, $en_state $en_country";
                $en_dropship_list[$list['id']] = $location;
            }

            return $en_dropship_list;
        }

        /**
         * Get dropship list
         * @param array $en_location_details
         * @return array|object|null
         */
        public static function get_data($en_location_details = [])
        {
            global $wpdb;

            $en_where_clause_str = '';
            $en_where_clause_param = [];
            if (isset($en_location_details) && !empty($en_location_details)) {

                foreach ($en_location_details as $index => $value) {
                    $en_where_clause_str .= (strlen($en_where_clause_str) > 0) ? ' AND ' : '';
                    $en_where_clause_str .= $index . ' = %s ';
                    $en_where_clause_param[] = $value;
                }

                $en_where_clause_str = (strlen($en_where_clause_str) > 0) ? ' WHERE ' . $en_where_clause_str : '';
            }

            $en_table_name = $wpdb->prefix . 'warehouse';
            $sql = $wpdb->prepare("SELECT * FROM $en_table_name $en_where_clause_str", $en_where_clause_param);
            return (array)$wpdb->get_results($sql, ARRAY_A);
        }

        /**
         * Product Fields Array
         * @return array
         */
        public function en_product_fields_arr()
        {
            $en_product_fields = [
                [
                    'type' => 'checkbox',
                    'id' => '_enable_dropship',
                    'class' => '_enable_dropship',
                    'line_item' => 'location',
                    'label' => 'Enable Drop Ship Location',
                ],
                [
                    'type' => 'dropdown',
                    'id' => '_dropship_location',
                    'class' => '_dropship_location short',
                    'line_item' => 'locationId',
                    'label' => 'Drop ship location',
                    'options' => $this->en_dropship_list()
                ],
                [
                    'type' => 'dropdown',
                    'id' => '_ltl_freight',
                    'class' => '_ltl_freight short',
                    'line_item' => 'lineItemClass',
                    'label' => 'Freight classification',
                    'options' => $this->en_freight_classification(),
                ],
                [
                    'type' => 'input_field',
                    'id' => '_en_product_markup',
                    'class' => '_en_product_markup short',
                    'label' => __( 'Markup', 'woocommerce' ),
                    'placeholder' => 'e.g Currency 1.00 or percentage 5%',
                    'description' => "Increases the amount of the returned quote by a specified amount prior to displaying it in the shopping cart. The number entered will be interpreted as dollars and cents unless it is followed by a % sign. For example, entering 5.00 will cause $5.00 to be added to the quotes. Entering 5% will cause 5 percent of the item's price to be added to the shipping quotes."
                ],
                [
                    'type' => 'checkbox',
                    'id' => '_hazardousmaterials',
                    'line_item' => 'isHazmatLineItem',
                    'class' => '_en_hazardous_material ' . $this->hazardous_disabled_plan,
                    'label' => 'Hazardous material',
                    'plans' => 'hazardous_material',
                    'description' => $this->hazardous_plan_required,
                ]
            ];
            // Micro Warehouse
            $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
            if (stripos(implode($all_plugins), 'micro-warehouse-shipping.php') || is_plugin_active_for_network('micro-warehouse-shipping-for-woocommerce/micro-warehouse-shipping.php')) {
                $en_product_fields = array_slice($en_product_fields, 2);
            }
            // We can use hook for add new product field from other plugin add-on
            $en_product_fields = apply_filters('en_product_fields', $en_product_fields);
            return $en_product_fields;
        }

        /**
         * Common plans status
         */
        public function en_app_common_plan_status()
        {
            $plan_status = apply_filters('en_app_common_plan_status', []);

            // Hazardous plan status
            if (isset($plan_status['hazardous_material'])) {
                if (!in_array(0, $plan_status['hazardous_material']['plan_required'])) {
                    $this->hazardous_disabled_plan = 'disabled_me';
                    $this->hazardous_plan_required = apply_filters("wwe_quests_plans_notification_link", [2, 3]);
                } elseif (isset($plan_status['hazardous_material']['status'])) {
                    $this->hazardous_plan_required = implode(" <br>", $plan_status['hazardous_material']['status']);
                }
            }
        }

        /**
         * Show Product Fields
         * @param int $postId
         */
        public function en_custom_product_fields($postId, $is_variable = false)
        {
            $this->en_app_common_plan_status();
            $en_product_fields = $this->en_product_fields_arr();

            // Check compatability hazardous materials with other plugins.
            if (class_exists("UpdateProductDetailOption")) {
                array_pop($en_product_fields);
            }

            foreach ($en_product_fields as $key => $custom_field) {
                $en_field_type = (isset($custom_field['type'])) ? $custom_field['type'] : '';
                $en_action_function_name = 'en_product_' . $en_field_type;

                if (method_exists($this, $en_action_function_name)) {
                    echo ($this->is_woo_paymant_installed && $is_variable) ? '<div>' : '';
                        $this->$en_action_function_name($custom_field, $postId);
                    echo ($this->is_woo_paymant_installed && $is_variable) ? '</div>' : '';
                }
            }
        }

        /**
         * Dynamic checkbox field show on product detail page
         * @param array $custom_field
         * @param int $postId
         */
        public function en_product_checkbox($custom_field, $postId)
        {
            $custom_checkbox_field = [
                'id' => $custom_field['id'] . '[' . $postId . ']',
                'value' => get_post_meta($postId, $custom_field['id'], true),
                'label' => $custom_field['label'],
                'class' => $custom_field['class'],
            ];

            if (isset($custom_field['description'])) {
                $custom_checkbox_field['description'] = $custom_field['description'];
            }
            echo '<div>';
                woocommerce_wp_checkbox($custom_checkbox_field);
            echo '</div>';
        }

        /**
         * Dynamic dropdown field show on product detail page
         * @param array $custom_field
         * @param int $postId
         */
        public function en_product_dropdown($custom_field, $postId)
        {
            $get_meta = get_post_meta($postId, $custom_field['id'], true);
            $assigned_option = is_serialized($get_meta) ? maybe_unserialize($get_meta) : $get_meta;
            $custom_dropdown_field = [
                'id' => $custom_field['id'] . '[' . $postId . ']',
                'label' => $custom_field['label'],
                'class' => $custom_field['class'],
                'value' => $assigned_option,
                'options' => $custom_field['options']
            ];

            woocommerce_wp_select($custom_dropdown_field);
        }

        /**
         * Dynamic input field show on product detail page
         * @param array $custom_field
         * @param int $postId
         */
        public function en_product_input_field($custom_field, $postId)
        {
            $placeholder = (isset($custom_field['placeholder'])) ? $custom_field['placeholder'] : $custom_field['label'];
            $custom_input_field = [
                'id' => $custom_field['id'] . '[' . $postId . ']',
                'label' => $custom_field['label'],
                'class' => $custom_field['class'],
                'placeholder' => $placeholder,
                'value' => get_post_meta($postId, $custom_field['id'], true)
            ];

            if (isset($custom_field['description'])) {
                $custom_input_field['desc_tip'] = true;
                $custom_input_field['description'] = $custom_field['description'];
            }

            woocommerce_wp_text_input($custom_input_field);
        }

        /**
         * This function checks either woocommerce Paymnet or Subscription plugins installed or not
         * and update class variable respectively
         */
        public function check_if_woo_payment_and_subscription_plugin_installed()
        {
            if (in_array('woocommerce-payments/woocommerce-payments.php', apply_filters('active_plugins', get_option('active_plugins'))) || is_plugin_active_for_network('woocommerce-payments/woocommerce-payments.php')
            || in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins'))) || is_plugin_active_for_network('woocommerce-subscriptions/woocommerce-subscriptions.php')
            || in_array('woo-gutenberg-products-block/woocommerce-gutenberg-products-block.php', apply_filters('active_plugins', get_option('active_plugins'))) || is_plugin_active_for_network('woo-gutenberg-products-block/woocommerce-gutenberg-products-block.php')) {
                $this->is_woo_paymant_installed = true;
            }
        }
    }

    new EnWweFreightProductDetail();
}
