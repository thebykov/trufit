<?php

if (!defined('ABSPATH')) exit;

class gdCPTData {
    function __construct() {
        add_filter('gdcpt_custom_field_function', array(&$this, 'functions'), 1);
        add_filter('gdcpt_custom_field_regex', array(&$this, 'regexes'), 1);
        add_filter('gdcpt_custom_field_mask', array(&$this, 'masks'), 1);
    }

    public function functions($list) {
        $list['gdCPTData::list_genders'] = __("Default", "gd-taxonomies-tools").': '.__("Genders", "gd-taxonomies-tools");
        $list['gdCPTData::list_continets'] = __("Default", "gd-taxonomies-tools").': '.__("Continets", "gd-taxonomies-tools");
        $list['gdCPTData::list_countries'] = __("Default", "gd-taxonomies-tools").': '.__("Countries", "gd-taxonomies-tools");
        $list['gdCPTData::list_months'] = __("Default", "gd-taxonomies-tools").': '.__("Months", "gd-taxonomies-tools");
        $list['gdCPTData::list_daysweek'] = __("Default", "gd-taxonomies-tools").': '.__("Days of the Week", "gd-taxonomies-tools");

        return $list;
    }

    public function masks($list) {
        $list['gdCPTData::mask_percent'] = __("Mask Default", "gd-taxonomies-tools").': '.__("Percent", "gd-taxonomies-tools");
        $list['gdCPTData::mask_phone_us'] = __("Mask Default", "gd-taxonomies-tools").': '.__("Phone Number US", "gd-taxonomies-tools");
        $list['gdCPTData::mask_phone_int'] = __("Mask Default", "gd-taxonomies-tools").': '.__("Phone Number International", "gd-taxonomies-tools");
        $list['gdCPTData::mask_ssn_us'] = __("Mask Default", "gd-taxonomies-tools").': '.__("SSN", "gd-taxonomies-tools");

        return $list;
    }

    public function regexes($list) {
        $list['gdCPTData::regex_integer'] = __("Regex Default", "gd-taxonomies-tools").': '.__("Integer", "gd-taxonomies-tools");
        $list['gdCPTData::regex_positive_integer'] = __("Regex Default", "gd-taxonomies-tools").': '.__("Positive Integer", "gd-taxonomies-tools");
        $list['gdCPTData::regex_float'] = __("Regex Default", "gd-taxonomies-tools").': '.__("Float", "gd-taxonomies-tools");
        $list['gdCPTData::regex_positive_float'] = __("Regex Default", "gd-taxonomies-tools").': '.__("Positive Float", "gd-taxonomies-tools");
        $list['gdCPTData::regex_letters_only'] = __("Regex Default", "gd-taxonomies-tools").': '.__("Letters", "gd-taxonomies-tools");
        $list['gdCPTData::regex_alphanumerics'] = __("Regex Default", "gd-taxonomies-tools").': '.__("Alphanumeric", "gd-taxonomies-tools");
        $list['gdCPTData::regex_hexnumber'] = __("Regex Default", "gd-taxonomies-tools").': '.__("Hex Number", "gd-taxonomies-tools");

        return $list;
    }

    public static function mask_percent() {
        return '99%';
    }

    public static function mask_phone_us() {
        return '(999) 999-9999';
    }

    public static function mask_phone_int() {
        return '+999 99 999 9999';
    }

    public static function mask_ssn_us() {
        return '999-99-9999';
    }

    public static function regex_integer() {
        return '/^[-+]?\d*$/';
    }

    public static function regex_positive_integer() {
        return '/^[+]?\d*$/';
    }

    public static function regex_float() {
        return '/^[-+]?\d*\.?\d*$/';
    }

    public static function regex_positive_float() {
        return '/^[+]?\d*\.?\d*$/';
    }

    public static function regex_letters_only() {
        return '/^[A-Za-z]*$/';
    }

    public static function regex_alphanumerics() {
        return '/^[0-9A-Za-z]*$/';
    }

    public static function regex_hex() {
        return '/^[a-f0-9]*$/';
    }

    public static function list_genders() {
        $data = array(
            'male' => 'Male',
            'female' => 'Female'
        );

        return apply_filters('gdcpt_data_list_genders', $data);
    }
    
    public static function list_continets() {
        $data = array(
            'africa' => 'Africa',
            'antarctica' => 'Antarctica',
            'asia' => 'Asia',
            'australia' => 'Australia',
            'europe' => 'Europe',
            'north-america' => 'North America',
            'south-america' => 'South America'
        );

        return apply_filters('gdcpt_data_list_continets', $data);
    }

    public static function list_countries() {
        $data = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo The Democratic Republic of The',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran Islamic Republic of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea Democratic People\'s Republic of',
            'KR' => 'Korea Republic of',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia The Former Yugoslav Republic of',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia Federated States of',
            'MD' => 'Moldova Republic of',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and The Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and The South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan Province of China',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania United Republic of',
            'TH' => 'Thailand',
            'TL' => 'Timor-leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Minor Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands British',
            'VI' => 'Virgin Islands U.S.',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );

        return apply_filters('gdcpt_data_list_countries', $data);
    }

    public static function list_months() {
        $data = array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        );

        return apply_filters('gdcpt_data_list_months', $data);
    }

    public static function list_daysweek() {
        $data = array(
            'sunday' => 'Sunday',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        );

        return apply_filters('gdcpt_data_list_daysweek', $data);
    }
}

global $gdcpt_data;
$gdcpt_data = new gdCPTData();

?>