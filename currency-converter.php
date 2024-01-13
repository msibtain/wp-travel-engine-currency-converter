<?php
/*
Plugin Name: WP Travel Engine Currency Converter
Plugin URI: https://innovisionlab.com
Description: WP Travel Engine Currency Converter
Author: innovisionlab
Version: 1.0.0
Author URI: https://innovisionlab.com
*/

if (!class_exists('\GeoIp2\Database\Reader')) {
    require_once __DIR__ . '/geoip2/vendor/autoload.php';
}

use GeoIp2\Database\Reader;

class CurrencyConverter
{
    private $currency;
    private $currency_symbol;
    private $cc_to_cr;
    private $userCountry;
    private $cc_enabled = true;

    function __construct()
    {
        $this->cc_to_cr = [
            'NZ' => 'NZD',
            'CK' => 'NZD',
            'NU' => 'NZD',
            'PN' => 'NZD',
            'TK' => 'NZD',
            'AU' => 'AUD',
            'CX' => 'AUD',
            'CC' => 'AUD',
            'HM' => 'AUD',
            'KI' => 'AUD',
            'NR' => 'AUD',
            'NF' => 'AUD',
            'TV' => 'AUD',
            'AS' => 'EUR',
            'AD' => 'EUR',
            'AT' => 'EUR',
            'BE' => 'EUR',
            'FI' => 'EUR',
            'FR' => 'EUR',
            'GF' => 'EUR',
            'TF' => 'EUR',
            'DE' => 'EUR',
            'GR' => 'EUR',
            'GP' => 'EUR',
            'IE' => 'EUR',
            'IT' => 'EUR',
            'LU' => 'EUR',
            'MQ' => 'EUR',
            'YT' => 'EUR',
            'MC' => 'EUR',
            'NL' => 'EUR',
            'PT' => 'EUR',
            'RE' => 'EUR',
            'WS' => 'EUR',
            'SM' => 'EUR',
            'SI' => 'EUR',
            'ES' => 'EUR',
            'VA' => 'EUR',
            'GS' => 'GBP',
            'GB' => 'GBP',
            'JE' => 'GBP',
            'IO' => 'USD',
            'GU' => 'USD',
            'MH' => 'USD',
            'FM' => 'USD',
            'MP' => 'USD',
            'PW' => 'USD',
            'PR' => 'USD',
            'TC' => 'USD',
            'US' => 'USD',
            'UM' => 'USD',
            'VG' => 'USD',
            'VI' => 'USD',
            'HK' => 'HKD',
            'CA' => 'CAD',
            'JP' => 'JPY',
            'AF' => 'AFN',
            'AL' => 'ALL',
            'DZ' => 'DZD',
            'AI' => 'XCD',
            'AG' => 'XCD',
            'DM' => 'XCD',
            'GD' => 'XCD',
            'MS' => 'XCD',
            'KN' => 'XCD',
            'LC' => 'XCD',
            'VC' => 'XCD',
            'AR' => 'ARS',
            'AM' => 'AMD',
            'AW' => 'ANG',
            'AN' => 'ANG',
            'AZ' => 'AZN',
            'BS' => 'BSD',
            'BH' => 'BHD',
            'BD' => 'BDT',
            'BB' => 'BBD',
            'BY' => 'BYR',
            'BZ' => 'BZD',
            'BJ' => 'XOF',
            'BF' => 'XOF',
            'GW' => 'XOF',
            'CI' => 'XOF',
            'ML' => 'XOF',
            'NE' => 'XOF',
            'SN' => 'XOF',
            'TG' => 'XOF',
            'BM' => 'BMD',
            'BT' => 'INR',
            'IN' => 'INR',
            'BO' => 'BOB',
            'BW' => 'BWP',
            'BV' => 'NOK',
            'NO' => 'NOK',
            'SJ' => 'NOK',
            'BR' => 'BRL',
            'BN' => 'BND',
            'BG' => 'BGN',
            'BI' => 'BIF',
            'KH' => 'KHR',
            'CM' => 'XAF',
            'CF' => 'XAF',
            'TD' => 'XAF',
            'CG' => 'XAF',
            'GQ' => 'XAF',
            'GA' => 'XAF',
            'CV' => 'CVE',
            'KY' => 'KYD',
            'CL' => 'CLP',
            'CN' => 'CNY',
            'CO' => 'COP',
            'KM' => 'KMF',
            'CD' => 'CDF',
            'CR' => 'CRC',
            'HR' => 'HRK',
            'CU' => 'CUP',
            'CY' => 'CYP',
            'CZ' => 'CZK',
            'DK' => 'DKK',
            'FO' => 'DKK',
            'GL' => 'DKK',
            'DJ' => 'DJF',
            'DO' => 'DOP',
            'TP' => 'IDR',
            'ID' => 'IDR',
            'EC' => 'ECS',
            'EG' => 'EGP',
            'SV' => 'SVC',
            'ER' => 'ETB',
            'ET' => 'ETB',
            'EE' => 'EEK',
            'FK' => 'FKP',
            'FJ' => 'FJD',
            'PF' => 'XPF',
            'NC' => 'XPF',
            'WF' => 'XPF',
            'GM' => 'GMD',
            'GE' => 'GEL',
            'GI' => 'GIP',
            'GT' => 'GTQ',
            'GN' => 'GNF',
            'GY' => 'GYD',
            'HT' => 'HTG',
            'HN' => 'HNL',
            'HU' => 'HUF',
            'IS' => 'ISK',
            'IR' => 'IRR',
            'IQ' => 'IQD',
            'IL' => 'ILS',
            'JM' => 'JMD',
            'JO' => 'JOD',
            'KZ' => 'KZT',
            'KE' => 'KES',
            'KP' => 'KPW',
            'KR' => 'KRW',
            'KW' => 'KWD',
            'KG' => 'KGS',
            'LA' => 'LAK',
            'LV' => 'LVL',
            'LB' => 'LBP',
            'LS' => 'LSL',
            'LR' => 'LRD',
            'LY' => 'LYD',
            'LI' => 'CHF',
            'CH' => 'CHF',
            'LT' => 'LTL',
            'MO' => 'MOP',
            'MK' => 'MKD',
            'MG' => 'MGA',
            'MW' => 'MWK',
            'MY' => 'MYR',
            'MV' => 'MVR',
            'MT' => 'MTL',
            'MR' => 'MRO',
            'MU' => 'MUR',
            'MX' => 'MXN',
            'MD' => 'MDL',
            'MN' => 'MNT',
            'MA' => 'MAD',
            'EH' => 'MAD',
            'MZ' => 'MZN',
            'MM' => 'MMK',
            'NA' => 'NAD',
            'NP' => 'NPR',
            'NI' => 'NIO',
            'NG' => 'NGN',
            'OM' => 'OMR',
            'PK' => 'PKR',
            'PA' => 'PAB',
            'PG' => 'PGK',
            'PY' => 'PYG',
            'PE' => 'PEN',
            'PH' => 'PHP',
            'PL' => 'PLN',
            'QA' => 'QAR',
            'RO' => 'RON',
            'RU' => 'RUB',
            'RW' => 'RWF',
            'ST' => 'STD',
            'SA' => 'SAR',
            'SC' => 'SCR',
            'SL' => 'SLL',
            'SG' => 'SGD',
            'SK' => 'SKK',
            'SB' => 'SBD',
            'SO' => 'SOS',
            'ZA' => 'ZAR',
            'LK' => 'LKR',
            'SD' => 'SDG',
            'SR' => 'SRD',
            'SZ' => 'SZL',
            'SE' => 'SEK',
            'SY' => 'SYP',
            'TW' => 'TWD',
            'TJ' => 'TJS',
            'TZ' => 'TZS',
            'TH' => 'THB',
            'TO' => 'TOP',
            'TT' => 'TTD',
            'TN' => 'TND',
            'TR' => 'TRY',
            'TM' => 'TMT',
            'UG' => 'UGX',
            'UA' => 'UAH',
            'AE' => 'AED',
            'UY' => 'UYU',
            'UZ' => 'UZS',
            'VU' => 'VUV',
            'VE' => 'VEF',
            'VN' => 'VND',
            'YE' => 'YER',
            'ZM' => 'ZMK',
            'ZW' => 'ZWD',
            'AX' => 'EUR',
            'AO' => 'AOA',
            'AQ' => 'AQD',
            'BA' => 'BAM',
            'CD' => 'CDF',
            'GH' => 'GHS',
            'GG' => 'GGP',
            'IM' => 'GBP',
            'LA' => 'LAK',
            'MO' => 'MOP',
            'ME' => 'EUR',
            'PS' => 'JOD',
            'BL' => 'EUR',
            'SH' => 'GBP',
            'MF' => 'ANG',
            'PM' => 'EUR',
            'RS' => 'RSD',
            'USAF' => 'USD'
        ];

        //add_filter('wp_travel_engine_currency_code', [$this, 'i_change_currency']); // Partially working
        // EUR   
        //add_filter('wp_travel_engine_currency', [$this, 'i_change_currency_v2']); // Not working
        //add_filter('wte_price_value', [$this, 'i_wte_price_value']); // Not working

        if ($this->cc_enabled)
        {
            $reader = new Reader(__DIR__ . '/GeoLite2-City.mmdb');
            $record = $reader->city( $_SERVER['REMOTE_ADDR'] );
            $this->userCountry      = $record->country->isoCode;
            $this->currency         = $this->cc_to_cr[$this->userCountry];
            $this->currency_symbol  = Wp_Travel_Engine_Functions::currency_symbol_by_code( $this->currency );

            //echo "<br>country: " . $this->userCountry;
            //echo "<br>currency: " . $this->currency;
            //echo "<br>currency_symbol: " . $this->currency_symbol;


            add_action( 'wp_enqueue_scripts', [$this, 'i_custom_styles'] );
            add_action( 'wp_footer', [$this, 'i_wp_footer'] );
        }

        add_action( 'wp_footer', [$this, 'i_cat_links_in_new_tab'] );
        
    }

    function i_custom_styles()
    {
        wp_enqueue_script( 'money_script', home_url() . '/wp-content/plugins/wp-travel-engine-currency-converter/js/money.min.js' );
        wp_enqueue_script( 'main_script', home_url() . '/wp-content/plugins/wp-travel-engine-currency-converter/js/main.js' );
    }

    function i_wp_footer() {
        ?>
        <script>
            var iCurrency = "<?php echo $this->currency ?>";
            var iCurrencySymbol = "<?php echo $this->currency_symbol ?>";
            jQuery(document).ready(function($){
                var currency_api = getCookie("currency_api_pas");
                if (currency_api)
                {
                    currency_api = JSON.parse(currency_api);
                    
                    console.log('currency rates from cookie' + currency_api);
                    
                    if ( typeof fx !== "undefined" && fx.rates ) {
                            
                            fx.rates = currency_api.rates;
                            fx.base = currency_api.base;
                        } else {
                            
                            // If not, apply to fxSetup global:
                            var fxSetup = {
                                rates : currency_api.rates,
                                base : currency_api.base
                            }
                        }
                }
                else
                {
                    console.log('currency rates from server');
                    
                    var currency_api_local = new Object();
                    
                    // Load exchange rates data via AJAX:
                    jQuery.getJSON(
                    // NB: using Open Exchange Rates here, but you can use any source!
                    'https://openexchangerates.org/api/latest.json?app_id=04ee5c8cb8724c86a64557c55c2b5768',
                    function(data) {
                        // Check money.js has finished loading:
                        if ( typeof fx !== "undefined" && fx.rates ) {
                            console.log('money js loaded');
                            fx.rates = data.rates;
                            fx.base = data.base;
                        } else {
                            console.log('money js NOT loaded');
                            // If not, apply to fxSetup global:
                            var fxSetup = {
                                rates : data.rates,
                                base : data.base
                            }
                        }
                        
                        currency_api_local.rates = data.rates;
                        currency_api_local.base = data.base;
                        
                        setCookie("currency_api_pas", JSON.stringify(currency_api_local));
                    }
                    );
                }

                var wpte_bf_offer_amount = jQuery('.wpte-bf-offer-amount');
                jQuery.each(wpte_bf_offer_amount, function(index, value){
                    replacePrice(this, true);
                });

                var wpte_currency_code = jQuery('.wpte-currency-code');
                jQuery.each(wpte_currency_code, function(index, value){
                    jQuery(this).html( "<?php echo $this->currency_symbol ?>" );
                });

                var wpte_price = jQuery('.wpte-price');
                jQuery.each(wpte_price, function(index, value){
                    replacePrice(this, false);
                });

                <?php if (!is_front_page()) { ?>
                var actual_price = jQuery('.actual-price');
                jQuery.each(actual_price, function(index, value){
                    replacePrice(this, true);
                });
                <?php } ?>

                /*
                $(window).on('DOMContentLoaded', function(){
                    
                    

                    $('.wpte-price amount').on('change', function(){

                        console.log('date changed in booking cal');

                        $('.wpte-currency-code').html("<?php echo $this->currency_symbol ?> ");
                        var wpte_price = jQuery('.wpte-price');
                        jQuery.each(wpte_price, function(index, value){
                            replacePrice(this, false);
                        });

                    })
                });

                $('#open-booking-modal').on('click', function(){

                    console.log('booking modal button clicked');

                    $('.wpte-currency-code').html("<?php echo $this->currency_symbol ?> ");
                    
                    var wpte_price = jQuery('.wpte-price');
                    jQuery.each(wpte_price, function(index, value){
                        replacePrice(this, false);
                    });

                    // flatpickr-monthDropdown-months change
                    // flatpickr-days click
                    // fancybox-container-2 focus

                    $('.flatpickr-days').on('click', function(){
                        console.log('date clicked...');
                        $('.wpte-currency-code').html("<?php echo $this->currency_symbol ?> ");
                    
                        var wpte_price = jQuery('.wpte-price');
                        jQuery.each(wpte_price, function(index, value){
                            replacePrice(this, false);
                        });

                    });
                    
                    $(document).on('wteEditPackageRender', function(){
                        console.log( 'wte edit package... ' );
                    })
                });
                */

                /* following event will be triggerred when payment method is changed on checkout page */
                $('input[name=wpte_checkout_paymnet_method]').on('change', function(){
                        var wpte_price_wrap = $('.wpte-price-wrap');
                        replacePrice(wpte_price_wrap, true); 
                });

            });

            function replacePrice(obj, show_symbol)
            {
                var price = jQuery(obj).html();
                price = price.replace('$', '');
                var convertedPrice = fx(price).from("USD").to("<?php echo $this->currency ?>");
                if (show_symbol)
                    jQuery(obj).html( "<?php echo $this->currency_symbol ?> " + convertedPrice.toFixed(2) );
                else
                    jQuery(obj).html( convertedPrice.toFixed(2) );
            }
        </script>
        <?php
    }

    

    function i_change_currency($base_currency)
    {
        $base_currency = "EUR";
        return $base_currency;
    }

    function i_cat_links_in_new_tab() {
        ?>
        <script>
            jQuery(document).ready(function(){

                var category_trip_title = jQuery('.category-trip-title a');
                jQuery.each(category_trip_title, function(index, value){
                    jQuery(this).attr('target', '_blank');
                });

            });

        </script>
        <?php
    }

}

new CurrencyConverter();