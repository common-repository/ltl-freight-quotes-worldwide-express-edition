<?php
/**
 * WWE LTL Carriers
 *
 * @package     WWE LTL Quotes
 * @author      Eniture-Technology
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class wwe_ltl_carriers
 */
class wwe_ltl_carriers
{
    /**
     * Carriers
     * @global $wpdb
     */
    function carriers()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;
        $table_name = WWE_CARRIERS;
        $installed_carriers = $wpdb->get_results("SELECT COUNT(*) AS carriers FROM " . $table_name);
        if ($installed_carriers[0]->carriers < 1) {
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'AACT',
                'speed_freight_carrierName' => 'AAA Cooper Transportation',
                'carrier_logo' => 'aact.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'ABFS',
                'speed_freight_carrierName' => 'ABF Freight System, Inc',
                'carrier_logo' => 'abfs.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'AMAP',
                'speed_freight_carrierName' => 'AMA Transportation Company Inc',
                'carrier_logo' => 'amap.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'APXT',
                'speed_freight_carrierName' => 'APEX XPRESS',
                'carrier_logo' => 'apxt.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'ATMR',
                'speed_freight_carrierName' => 'Atlas Motor Express',
                'carrier_logo' => 'atmr.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'BCKT',
                'speed_freight_carrierName' => 'Becker Trucking Inc',
                'carrier_logo' => 'bckt.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'BEAV',
                'speed_freight_carrierName' => 'Beaver Express Service, LLC',
                'carrier_logo' => 'beav.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'BTVP',
                'speed_freight_carrierName' => 'Best Overnite Express',
                'carrier_logo' => 'btvp.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CAZF',
                'speed_freight_carrierName' => 'Central Arizona Freight Lines',
                'carrier_logo' => 'cazf.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CENF',
                'speed_freight_carrierName' => 'Central Freight Lines, Inc',
                'carrier_logo' => 'cenf.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CLNI',
                'speed_freight_carrierName' => 'Clear Lane Freight Systems',
                'carrier_logo' => 'clni.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CNWY',
                'speed_freight_carrierName' => 'Con-Way',
                'carrier_logo' => 'cnwy.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CPCD',
                'speed_freight_carrierName' => 'Cape Cod Express',
                'carrier_logo' => 'cpcd.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CTII',
                'speed_freight_carrierName' => 'Central Transport',
                'carrier_logo' => 'ctii.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CXRE',
                'speed_freight_carrierName' => 'Cal State Express',
                'carrier_logo' => 'cxre.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'DAFG',
                'speed_freight_carrierName' => 'Dayton Freight',
                'carrier_logo' => 'dafg.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'DDPP',
                'speed_freight_carrierName' => 'Dedicated Delivery Professionals',
                'carrier_logo' => 'ddpp.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'DHRN',
                'speed_freight_carrierName' => 'Dohrn Transfer Company',
                'carrier_logo' => 'dhrn.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'DPHE',
                'speed_freight_carrierName' => 'Dependable Highway Express',
                'carrier_logo' => 'dphe.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'DTST',
                'speed_freight_carrierName' => 'DATS Trucking Inc',
                'carrier_logo' => 'dtst.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'DUBL',
                'speed_freight_carrierName' => 'Dugan Truck Lines',
                'carrier_logo' => 'dubl.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'DYLT',
                'speed_freight_carrierName' => 'Daylight Transport',
                'carrier_logo' => 'dylt.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'EXLA',
                'speed_freight_carrierName' => 'Estes Express Lines',
                'carrier_logo' => 'exla.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'FCSY',
                'speed_freight_carrierName' => 'Frontline Freight Inc',
                'carrier_logo' => 'fcsy.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'FLAN',
                'speed_freight_carrierName' => 'Flo Trans',
                'carrier_logo' => 'flan.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'FTSC',
                'speed_freight_carrierName' => 'Fort Transportation',
                'carrier_logo' => 'ftsc.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'FWDN',
                'speed_freight_carrierName' => 'Forward Air, Inc',
                'carrier_logo' => 'fwdn.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'GLDF',
                'speed_freight_carrierName' => 'Gold Coast Freightways',
                'carrier_logo' => 'gldf.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'HMES',
                'speed_freight_carrierName' => 'Holland',
                'carrier_logo' => 'hmes.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'LAXV',
                'speed_freight_carrierName' => 'Land Air Express Of New England',
                'carrier_logo' => 'laxv.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'LKVL',
                'speed_freight_carrierName' => 'Lakeville Motor Express Inc',
                'carrier_logo' => 'lkvl.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'MIDW',
                'speed_freight_carrierName' => 'Midwest Motor Express',
                'carrier_logo' => 'midw.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'NEBT',
                'speed_freight_carrierName' => 'Nebraska Transport',
                'carrier_logo' => 'nebt.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'NEMF',
                'speed_freight_carrierName' => 'New England Motor Freight',
                'carrier_logo' => 'nemf.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'NOPK',
                'speed_freight_carrierName' => 'North Park Transportation Co',
                'carrier_logo' => 'nopk.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'NPME',
                'speed_freight_carrierName' => 'New Penn Motor Express',
                'carrier_logo' => 'npme.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'OAKH',
                'speed_freight_carrierName' => 'Oak Harbor Freight Lines',
                'carrier_logo' => 'oakh.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'ODFL',
                'speed_freight_carrierName' => 'Old Dominion',
                'carrier_logo' => 'odfl.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'PITD',
                'speed_freight_carrierName' => 'Pitt Ohio Express, LLC',
                'carrier_logo' => 'pitd.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'PMLI',
                'speed_freight_carrierName' => 'Pace Motor Lines, Inc',
                'carrier_logo' => 'pmli.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'PNII',
                'speed_freight_carrierName' => 'ProTrans International',
                'carrier_logo' => 'pnii.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'PYLE',
                'speed_freight_carrierName' => 'A Duie PYLE',
                'carrier_logo' => 'pyle.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'RDFS',
                'speed_freight_carrierName' => 'Roadrunner Transportation Services',
                'carrier_logo' => 'rdfs.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'RDWY',
                'speed_freight_carrierName' => 'YRC',
                'carrier_logo' => 'rdwy.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'RETL',
                'speed_freight_carrierName' => 'USF Reddaway',
                'carrier_logo' => 'retl.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'RJWI',
                'speed_freight_carrierName' => 'RJW Transport',
                'carrier_logo' => 'rjwi.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'RLCA',
                'speed_freight_carrierName' => 'R & L Carriers Inc',
                'carrier_logo' => 'rlca.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'ROSI',
                'speed_freight_carrierName' => 'Roseville Motor Express',
                'carrier_logo' => 'rosi.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'RXIC',
                'speed_freight_carrierName' => 'Ross Express',
                'carrier_logo' => 'rxic.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'SAIA',
                'speed_freight_carrierName' => 'SAIA',
                'carrier_logo' => 'saia.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'SEFL',
                'speed_freight_carrierName' => 'Southeastern Freight Lines',
                'carrier_logo' => 'sefl.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'SHIF',
                'speed_freight_carrierName' => 'Shift Freight',
                'carrier_logo' => 'shif.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'SMTL',
                'speed_freight_carrierName' => 'Southwestern Motor Transport',
                'carrier_logo' => 'smtl.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'STDF',
                'speed_freight_carrierName' => 'Standard Forwarding Company Inc',
                'carrier_logo' => 'stdf.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'SVSE',
                'speed_freight_carrierName' => 'SuperVan Service Co. Inc',
                'carrier_logo' => 'svse.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'UPGF',
                'speed_freight_carrierName' => 'TForce Freight',
                'carrier_logo' => 'tforce.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'WARD',
                'speed_freight_carrierName' => 'Ward Trucking',
                'carrier_logo' => 'ward.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'WEBE',
                'speed_freight_carrierName' => 'West Bend Transit',
                'carrier_logo' => 'webe.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CGOJ',
                'speed_freight_carrierName' => 'Cargomatic ',
                'carrier_logo' => 'cargomatic.png',
                'carrier_status' => '1'
            ));

            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'WTVA',
                'speed_freight_carrierName' => 'Wilson Trucking Corporation',
                'carrier_logo' => 'wtva.png',
                'carrier_status' => '1'
            ));

            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'AVRT',
                'speed_freight_carrierName' => 'Averitt Express, Inc',
                'carrier_logo' => 'averitt.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'BRTC',
                'speed_freight_carrierName' => 'BC Freightways',
                'carrier_logo' => 'brtc.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CTBV',
                'speed_freight_carrierName' => 'CTBV Custom Companies',
                'carrier_logo' => 'cbtv.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'MTJG',
                'speed_freight_carrierName' => 'MTJG Moran Transportation',
                'carrier_logo' => 'mtjg.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'CCYQ',
                'speed_freight_carrierName' => 'CrossCountry Freight Solutions',
                'carrier_logo' => 'CCYQ.png',
                'carrier_status' => '1'
            ));
            $wpdb->insert(
                $table_name, array(
                'speed_freight_carrierSCAC' => 'MTVL',
                'speed_freight_carrierName' => 'GLS-US formerly Mountain Valley Express',
                'carrier_logo' => 'gls.png',
                'carrier_status' => '1'
            ));
        }
        $ccyq_carrier = $wpdb->get_results("SELECT COUNT(*) AS carrier FROM " . WWE_CARRIERS . " where speed_freight_carrierSCAC = 'CCYQ'");
        if ($ccyq_carrier[0]->carrier < 1) {
            $wpdb->insert(
                $table_name, array(
                    'speed_freight_carrierSCAC' => 'CCYQ',
                    'speed_freight_carrierName' => 'CrossCountry Freight Solutions',
                    'carrier_logo' => 'CCYQ.png',
                    'carrier_status' => '1'
                )
            );
        }

        $mtjg_carrier = $wpdb->get_results("SELECT COUNT(*) AS carrier FROM " . WWE_CARRIERS . " where speed_freight_carrierSCAC = 'MTJG'");
        if ($mtjg_carrier[0]->carrier < 1) {
            $wpdb->insert(
                $table_name, array(
                    'speed_freight_carrierSCAC' => 'MTJG',
                    'speed_freight_carrierName' => 'MTJG Moran Transportation',
                    'carrier_logo' => 'mtjg.png',
                    'carrier_status' => '1'
                )
            );
        }
        $gls_carrier = $wpdb->get_results("SELECT COUNT(*) AS carrier FROM " . WWE_CARRIERS . " where speed_freight_carrierSCAC = 'MTVL'");
        if ($gls_carrier[0]->carrier < 1) {
            $wpdb->insert(
                $table_name, array(
                    'speed_freight_carrierSCAC' => 'MTVL',
                    'speed_freight_carrierName' => 'GLS-US formerly Mountain Valley Express',
                    'carrier_logo' => 'gls.png',
                    'carrier_status' => '1'
                )
            );
        }
    }

    /**
     * Create LTL Class
     */
    function create_ltl_class()
    {

        wp_insert_term(
            'LTL Freight', 'product_shipping_class', array(
                'description' => 'The plugin is triggered to provide an LTL freight quote when the shopping cart contains an item that has a designated shipping class. Shipping class? is a standard WooCommerce parameter not to be confused with freight class? or the NMFC classification system.',
                'slug' => 'ltl_freight'
            )
        );
    }

}
