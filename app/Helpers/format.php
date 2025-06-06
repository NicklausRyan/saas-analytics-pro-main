<?php

/**
 * Format the page titles.
 *
 * @param null $value
 * @return string|null
 */
function formatTitle($value = null)
{
    if (is_array($value)) {
        return implode(" - ", $value);
    }

    return $value;
}

/**
 * Format money.
 *
 * @param $amount
 * @param $currency
 * @param bool $separator
 * @param bool $translate
 * @return string
 */
function formatMoney($amount, $currency, $separator = true, $translate = true)
{
    if (in_array(strtoupper($currency), config('currencies.zero_decimals'))) {
        return number_format($amount, 0, $translate ? __('.') : '.', $separator ? ($translate ? __(',') : ',') : false);
    } else {
        return number_format($amount, 2, $translate ? __('.') : '.', $separator ? ($translate ? __(',') : ',') : false);
    }
}

/**
 * Get and format the Gravatar URL.
 *
 * @param $email
 * @param int $size
 * @param string $default
 * @param string $rating
 * @return string
 */
function gravatar($email, $size = 80, $default = 'identicon', $rating = 'g')
{
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= '?s='.$size.'&d='.$default.'&r='.$rating;
    return $url;
}

/**
 * Format the browser icon
 *
 * @param $key
 * @return mixed|string
 */
function formatBrowser($key)
{
    $browsers = [
        'Chrome' => 'chrome',
        'Chromium' => 'chromium',
        'Firefox' => 'firefox',
        'Firefox Mobile' => 'firefox',
        'Edge' => 'edge',
        'Internet Explorer' => 'ie',
        'Mobile Internet Explorer' => 'ie',
        'Vivaldi' => 'vivaldi',
        'Brave' => 'brave',
        'Safari' => 'safari',
        'Opera' => 'opera',
        'Opera Mini' => 'opera',
        'Opera Mobile' => 'opera',
        'Opera Touch' => 'operatouch',
        'Yandex Browser' => 'yandex',
        'UC Browser' => 'ucbrowser',
        'Samsung Internet' => 'samsung',
        'QQ Browser' => 'qq',
        'BlackBerry Browser' => 'bbbrowser',
        'Maxthon' => 'maxthon'
    ];

    if (array_key_exists($key, $browsers)) {
        return $browsers[$key];
    } else {
        return 'unknown';
    }
}

/**
 * Format the operating system icon
 *
 * @param $key
 * @return mixed|string
 */
function formatOperatingSystem($key)
{
    $operatingSystems = [
        'Windows' => 'windows',
        'Linux' => 'linux',
        'Ubuntu' => 'ubuntu',
        'Windows Phone' => 'windows',
        'iOS' => 'apple',
        'OS X' => 'apple',
        'FreeBSD' => 'freebsd',
        'Android' => 'android',
        'Chrome OS' => 'chromeos',
        'BlackBerry OS' => 'bbos',
        'Tizen' => 'tizen',
        'KaiOS' => 'kaios',
        'BlackBerry Tablet OS' => 'bbos'
    ];

    if (array_key_exists($key, $operatingSystems)) {
        return $operatingSystems[$key];
    } else {
        return 'unknown';
    }
}

/**
 * Format the devices icon
 *
 * @param $key
 * @return mixed|string
 */
function formatDevice($key)
{
    $devices = [
        'desktop' => 'desktop',
        'mobile' => 'mobile',
        'tablet' => 'tablet',
        'television' => 'tv',
        'gaming' => 'gaming',
        'watch' => 'watch'
    ];

    if (array_key_exists($key, $devices)) {
        return $devices[$key];
    } else {
        return 'unknown';
    }
}

/**
 * Format the flag icon
 *
 * @param $value
 * @return string
 */
function formatFlag($value)
{
    $country = explode(':', $value);

    if (isset($country[0]) && !empty($country[0])) {
        // Return the country code
        return strtolower($country[0]);
    } else {
        return 'unknown';
    }
}

/**
 * Format country name from stored value.
 * Handles both "CODE:Country Name" and just "CODE" formats.
 *
 * @param   string   $value  The country value from database
 * @return  string
 */
function formatCountryName($value)
{
    if (empty($value)) {
        return __('Unknown');
    }

    $country = explode(':', $value);
    
    // If we have country name after colon, use it
    if (isset($country[1]) && !empty(trim($country[1]))) {
        return trim($country[1]);
    }
    
    // If we only have country code, look it up in config
    if (isset($country[0]) && !empty($country[0])) {
        $countryCode = strtoupper(trim($country[0]));
        $countries = config('countries');
        
        if (isset($countries[$countryCode])) {
            return $countries[$countryCode];
        }
    }
    
    return __('Unknown');
}

/**
 * Convert a number into a readable one.
 *
 * @param   int   $number  The number to be transformed
 * @return  string
 */
function shortenNumber($number)
{
    $suffix = ["", "K", "M", "B"];
    $precision = 1;
    for($i = 0; $i < count($suffix); $i++) {
        $divide = $number / pow(1000, $i);
        if($divide < 1000) {
            return round($divide, $precision).$suffix[$i];
        }
    }

    return $number;
}

/**
 * Safely parse a date string using Carbon with error handling.
 *
 * @param   string   $dateString  The date string to be parsed
 * @param   string   $format      The format to use for parsing (default: 'Y-m-d')
 * @return  \Carbon\Carbon
 */
function safeCreateFromFormat($dateString, $format = 'Y-m-d')
{
    try {
        // Clean the input and validate format
        $dateString = trim($dateString);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return \Carbon\Carbon::createFromFormat($format, $dateString);
        } else {
            return \Carbon\Carbon::parse($dateString);
        }
    } catch (\Exception $e) {
        return \Carbon\Carbon::now();
    }
}