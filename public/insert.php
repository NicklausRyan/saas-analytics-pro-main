<?php
error_reporting(-1);
ini_set('display_errors', 1);
session_start();
$CONF['host'] = '127.0.0.1';
$CONF['user'] = 'root';
$CONF['pass'] = '';
$CONF['name'] = 'phpanalytics';
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");

function incrementalHash($len = 5){
  $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  $base = strlen($charset);
  $result = '';

  $now = explode(' ', microtime())[1];
  while ($now >= $base){
    $i = $now % $base;
    $result = $charset[$i] . $result;
    $now /= $base;
  }
  return substr($result, -5);
}

function generateRandomNumbers($max, $count)
{
    $numbers = [];

    for ($i = 1; $i < $count; $i++) {
        $random = mt_rand(0, $max / ($count - $i));
        $numbers[] = $random;
        $max -= $random;
    }

    $numbers[] = $max;

    shuffle($numbers);

    return $numbers;
}

if (isset($_GET['live'])) {
    $referrer = ['www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com',
        'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com','www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com',
        'www.facebook.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com',
        'www.youtube.com', 'www.youtube.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com',
        'www.reddit.com', 'www.reddit.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com',
        'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv',
        'www.wikipedia.org', 'www.wikipedia.org', 'www.wikipedia.org', 'www.wikipedia.org', 'www.wikipedia.org',
        'www.quora.com', 'www.quora.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.quora.com', 'www.quora.com',
        'www.amazon.com', 'www.amazon.com', 'www.amazon.com',
        'www.baidu.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'www.baidu.com',
        'www.ecosia.org',
        'search.yahoo.com',
        'search.aol.com',
        'yandex.ru', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com',
        'l.facebook.com',
        't.co', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com',
        'l.instagram.com',
        'out.reddit.com',
        'away.vk.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com', 'example.com'];

    $cities = ['AU:Sydney', 'US:New York', 'US:San Francisco', 'GB:London', 'DE:Berlin', 'IT:Rome', 'FR:Paris', 'RO:Bucharest', 'DK:Denmark', 'RU:Moscow', 'TK:Ankara', 'PT:Lisbon', 'IT:Milan', 'GB:Manchester', 'DK:Amsterdam', 'RO:Bocsa', 'RO:Timisoara', 'RO:Brasov', 'RO:Constanta', 'RO:Iasi', ''];

    $browser = ['Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome',
        'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox',
        'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge',
        'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari',
        'Opera', 'Opera', 'Opera', 'Opera', 'Opera', 'Opera', 'Opera', 'Opera',
        'Samsung Internet', 'Samsung Internet', 'Samsung Internet', 'Samsung Internet',
        'Opera Touch', 'Opera Touch', 'Opera Touch',
        'Chromium', 'Chromium',
        'Vivaldi', 'Vivaldi',
        'Brave', 'Brave',
        'Yandex Browser',
        'Internet Explorer'];

    $os = ['Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows',
        'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android',
        'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS',
        'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux',
        'Chrome OS', 'Chrome OS', 'Chrome OS', 'Chrome OS',
        'OS X', 'OS X', 'OS X',
        'Tizen', 'Tizen',
        'BlackBerry OS',
        'FreeBSD',
        'KaiOS',
        'Ubuntu'];

    $device = ['desktop', 'desktop', 'desktop', 'desktop', 'desktop', 'desktop', 'desktop',
        'mobile', 'mobile', 'mobile', 'mobile',
        'tablet', 'tablet',
        'gaming',
        'watch',
        'television'];

    $location = ['US:United States' => 'US:New York, NY', 'DE:Germany' => 'DE:Berlin, BE', 'FR:France' =>  'FR:Paris, 75', 'GB:Great Britain' => 'GB:London, ENG', 'RO:Romania' => 'RO:Bucharest, B', 'IT:Italy' => 'IT:Rome, RM', 'ES:Spain' => 'ES:Madrid, M', 'IN:India' => 'IN:Delhi, DL', 'RU:Russia' =>  'RU:Moscow, MOW', 'TR:Turkey' =>  'TR:Istanbul, 34', 'AU:Australia' => 'AU:Sydney, NSW', 'BR:Brazil' =>  'BR:São Paulo, SP', 'CA:Canada' =>  'CA:Brampton, ON', 'CN:China' => 'CN:Beijing, BJ', 'BD:Bangladesh' =>  'BD:Dhaka, 13', 'CH:Switzerland' => 'CH:Zurich, ZH', 'AR:Argentina' => 'AR:Moron, B', 'KR:South Korea' => 'KR:Seocho-gu, 11', 'MA:Morocco' => 'MA:Rabat, RAB', 'NG:Nigeria' => 'NG:Lagos, LA', 'PK:Pakistan' =>  'PK:Attock, PB', 'PY:Paraguay' =>  'PY:Santa Rita, 10', 'BE:Belgium' => 'BE:Brussels, BRU', 'BG:Bulgaria' =>  'BG:Sofia, 22', 'BN:Brunei' =>  'BN:Bandar Seri Begawan, BM', 'BY:Belarus' => 'BY:Brest, BR', 'CZ:Czechia' => 'CZ:Prague, 10', 'DK:Denmark' => 'DK:Copenhagen, 84', 'EC:Ecuador' => 'EC:Babahoyo, R', 'EG:Egypt' => 'EG:Cairo, C', 'FI:Finland' => 'FI:Helsinki, 18', 'GH:Ghana' => 'GH:Accra, AA', 'GR:Greece' =>  'GR:Athens, I', 'ID:Indonesia' => 'ID:Jakarta, JK', 'IL:Israel' =>  'IL:Tel Aviv, TA', 'JM:Jamaica' => 'JM:Kingston, 01', 'JP:Japan' => 'JP:Setagaya-ku, 13', 'LB:Lebanon' => 'LB:Beirut, BA', 'MX:Mexico' =>  'MX:Los Mochis, SIN', 'MY:Malaysia' =>  'MY:Kuala Lumpur, 14', 'NL:Netherlands' => 'NL:Amsterdam, NH', 'NO:Norway' =>  'NO:Oslo, 03', 'NP:Nepal' => 'NP:Kathmandu, P3', 'PH:Philippines' => 'PH:Santa Rosa, LAG', 'PL:Poland' =>  'PL:Krakow, 12', 'PT:Portugal' =>  'PT:Vila do Corvo, 20', 'RS:Serbia' =>  'RS:Belgrade, 00', 'SE:Sweden' =>  'SE:Lund, M', 'TH:Thailand' =>  'TH:Bangkok, 10', 'UA:Ukraine' => 'UA:Kyiv, 30', 'ZA:South Africa' =>  'ZA:Cape Town, WC', 'AE:United Arab Emirates' =>  'AE:Dubai, DU', 'SN:Senegal' => 'SN:Dakar, DK', 'QA:Qatar' => 'QA:Doha, DA', 'SA:Saudi Arabia' =>  'SA:Jeddah, 02', 'KE:Kenya' => 'KE:Nairobi, 30', 'HU:Hungary' => 'HU:Budapest, BU', 'AT:Austria' => 'AT:Vienna, 9', 'UG:Uganda' =>  'UG:Kampala, 102', 'MM:Myanmar' => 'MM:Yangon, 06', 'VN:Vietnam' => 'VN:Hanoi, HN'];

    $countries = array_keys($location);

    $sites = ['http://lunatio.com', 'http://lunatio.test', 'http://demo.phpsearch.com', 'http://demo.phpmeteo.com'];

    $pages = ['/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/',
        '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard',
        '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register',
        '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login',
        '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome',
        '/services', '/services', '/services', '/services', '/services',
        '/settings', '/settings', '/settings', '/settings',
        '/about', '/about', '/about', '/about',
        '/contact', '/contact', '/contact',
        '/settings/account', '/settings/account',
        '/settings/security'];

    $lang = ['en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en',
        'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de',
        'es',  'es',  'es',  'es',  'es',  'es', 'es',  'es',  'es',  'es',  'es',  'es',
        'fr', 'fr', 'fr', 'fr', 'fr', 'fr', 'fr', 'fr',
        'cn', 'cn', 'cn', 'cn', 'cn', 'cn',
        'ro', 'ro', 'ro', 'ro',
        'it', 'it', 'it',
        'ru', 'ru',
        'bg',
        'hi',
        'tr'];

    for($i = 0; $i <= 1000; $i++) {
        usleep(1000000);

        for($z = 0; $z <= rand(5, 25); $z++) {
            $cRand = mt_rand(0, 60);
            $db->query(sprintf("INSERT INTO `recents` (
        `id`,
        `website_id`,
        `referrer`,
        `page`,
        `os`,
        `browser`,
        `device`,
        `country`,
        `city`,
        `language`,
        `created_at`
        ) VALUES (
        NULL,
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        NOW() - INTERVAL FLOOR(RAND() * 1) MINUTE);",
                1,
                $referrer[mt_rand(0, 710)],
                $pages[mt_rand(0, 520)],
                $os[mt_rand(0,115)],
                $browser[mt_rand(0,221)],
                $device[mt_rand(0,15)],
                $countries[$cRand],
                $location[$countries[$cRand]],
                $lang[mt_rand(0, 108)]
            ));
        }
    }
}
// Insert Stats
elseif(isset($_GET['stats'])) {
    /*$days = [
        'pageviews' => [11059, 10393, 11588, 11370, 9032, 9991, 9806, 11583, 10434, 10590, 9187, 10711, 10024, 10388, 10190, 11797, 10419, 11553, 10033, 11959, 11131, 11218, 9923, 11083, 9979, 11016, 10571, 9656, 10501, 10405, 11439, 11083, 9067, 10650, 9562, 11767, 10650, 10140, 9498, 10523, 9579, 11539, 10779, 10211, 10720, 11350, 9724, 11587, 10497, 11237, 12355, 11410, 11880, 9021, 12331, 9193, 9784, 10702, 9303, 11520, 9625, 10927, 9034, 10506, 10369, 10937, 12102, 11578, 10021, 11736, 9455],

        'visitors' => [4432, 4588, 3786, 4600, 3889, 4700, 3145, 5054, 3485, 4169, 5107, 4799, 3328, 4618, 4001, 3544, 4522, 4006, 4900, 4267, 3917, 4427, 3331, 4291, 3771, 4652, 3510, 4205, 3814, 4757, 3628, 4415, 3458, 3507, 3686, 4196, 3527, 4412, 2956, 4485, 5604, 4355, 3182, 4051, 3826, 4227, 3775, 4188, 3746, 4917, 3100, 4765, 2810, 4888, 3402, 3734, 4716, 4404, 3947, 4119, 3157, 4829, 3023, 4100, 3712, 3364, 3355, 4566, 3292, 4788, 5195]
    ];*/

    for($i = 0; $i <= 70; $i++) {
        for ($website_id = 2; $website_id <= 3; $website_id++) {
            $names = [
                'visitors'   =>  [''],
                'pageviews'  => [''],

                'browser'   =>  [
                    'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome', 'Chrome',
                    'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox', 'Firefox',
                    'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge', 'Edge',
                    'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari', 'Safari',
                    'Opera', 'Opera', 'Opera', 'Opera', 'Opera', 'Opera', 'Opera', 'Opera',
                    'Samsung Internet', 'Samsung Internet', 'Samsung Internet', 'Samsung Internet',
                    'Opera Touch', 'Opera Touch', 'Opera Touch',
                    'Chromium', 'Chromium',
                    'Vivaldi', 'Vivaldi',
                    'Brave', 'Brave',
                    'Yandex Browser',
                    'Internet Explorer'
                ],

                'os'  =>  [
                    'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows',
                    'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android', 'Android',
                    'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS', 'iOS',
                    'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux', 'Linux',
                    'Chrome OS', 'Chrome OS', 'Chrome OS', 'Chrome OS',
                    'OS X', 'OS X', 'OS X',
                    'Tizen', 'Tizen',
                    'BlackBerry OS',
                    'FreeBSD',
                    'KaiOS',
                    'Ubuntu'
                ],

                'device'    =>  [
                    'desktop', 'desktop', 'desktop', 'desktop', 'desktop', 'desktop', 'desktop',
                    'mobile', 'mobile', 'mobile', 'mobile',
                    'tablet', 'tablet',
                    'gaming',
                    'watch',
                    'television'
                ],

                'country'  =>   [
                    'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States', 'US:United States',
                    'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany', 'DE:Germany',
                    'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France', 'FR:France',
                    'GB:Great Britain', 'GB:Great Britain', 'GB:Great Britain', 'GB:Great Britain', 'GB:Great Britain', 'GB:Great Britain', 'GB:Great Britain', 'GB:Great Britain',
                    'RO:Romania',
                    'IT:Italy', 'IT:Italy', 'IT:Italy', 'IT:Italy', 'IT:Italy', 'IT:Italy', 'IT:Italy',
                    'ES:Spain', 'ES:Spain', 'ES:Spain', 'ES:Spain', 'ES:Spain',
                    'IN:India', 'IN:India', 'IN:India', 'IN:India',
                    'RU:Russia', 'RU:Russia', 'RU:Russia',
                    'TR:Turkey', 'TR:Turkey',
                    'AU:Australia', 'AU:Australia',
                    'BR:Brazil', 'BR:Brazil',
                    'CA:Canada',
                    'CN:China',
                    'BD:Bangladesh',
                    'CH:Switzerland',
                    'AR:Argentina',
                    'KR:South Korea',
                    'MA:Morocco',
                    'PK:Pakistan',
                    'PY:Paraguay',
                    'BE:Belgium',
                    'BG:Bulgaria',
                    'BN:Brunei',
                    'BY:Belarus',
                    'CZ:Czechia',
                    'DK:Denmark',
                    'EC:Ecuador',
                    'EG:Egypt',
                    'FI:Finland',
                    'GH:Ghana',
                    'GR:Greece',
                    'ID:Indonesia',
                    'IL:Israel',
                    'JM:Jamaica',
                    'JP:Japan',
                    'LB:Lebanon',
                    'MX:Mexico',
                    'MY:Malaysia',
                    'NG:Nigeria',
                    'NL:Netherlands',
                    'NO:Norway',
                    'NP:Nepal',
                    'PH:Philippines',
                    'PL:Poland',
                    'PT:Portugal',
                    'RS:Serbia',
                    'SE:Sweden',
                    'TH:Thailand',
                    'UA:Ukraine',
                    'ZA:South Africa',
                    'AE:United Arab Emirates',
                    'SN:Senegal',
                    'QA:Qatar',
                    'SA:Saudi Arabia',
                    'KE:Kenya',
                    'HU:Hungary',
                    'AT:Austria',
                    'UG:Uganda',
                    'MM:Myanmar',
                    'VN:Vietnam',
                ],

                'city'     =>   [
                    'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY', 'US:New York, NY',
                    'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE', 'DE:Berlin, BE',
                    'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75', 'FR:Paris, 75',
                    'GB:London, ENG', 'GB:London, ENG', 'GB:London, ENG', 'GB:London, ENG', 'GB:London, ENG', 'GB:London, ENG', 'GB:London, ENG', 'GB:London, ENG',
                    'RO:Bucharest, B',
                    'IT:Rome, RM', 'IT:Rome, RM', 'IT:Rome, RM', 'IT:Rome, RM', 'IT:Rome, RM', 'IT:Rome, RM', 'IT:Rome, RM',
                    'ES:Madrid, M', 'ES:Madrid, M', 'ES:Madrid, M', 'ES:Madrid, M', 'ES:Madrid, M',
                    'IN:Delhi, DL', 'IN:Delhi, DL', 'IN:Delhi, DL', 'IN:Delhi, DL',
                    'RU:Moscow, MOW', 'RU:Moscow, MOW', 'RU:Moscow, MOW',
                    'TR:Istanbul, 34', 'TR:Istanbul, 34',
                    'AU:Sydney, NSW', 'AU:Sydney, NSW',
                    'BR:São Paulo, SP', 'BR:São Paulo, SP',
                    'CA:Brampton, ON',
                    'CN:Beijing, BJ',
                    'BD:Dhaka, 13',
                    'CH:Zurich, ZH',
                    'AR:Moron, B',
                    'KR:Seocho-gu, 11',
                    'MA:Rabat, RAB',
                    'PK:Attock, PB',
                    'PY:Santa Rita, 10',
                    'BE:Brussels, BRU',
                    'BG:Sofia, 22',
                    'BN:Bandar Seri Begawan, BM',
                    'BY:Brest, BR',
                    'CZ:Prague, 10',
                    'DK:Copenhagen, 84',
                    'EC:Babahoyo, R',
                    'EG:Cairo, C',
                    'FI:Helsinki, 18',
                    'GH:Accra, AA',
                    'GR:Athens, I',
                    'ID:Jakarta, JK',
                    'IL:Tel Aviv, TA',
                    'JM:Kingston, 01',
                    'JP:Setagaya-ku, 13',
                    'LB:Beirut, BA',
                    'MX:Los Mochis, SIN',
                    'MY:Kuala Lumpur, 14',
                    'NG:Lagos, LA',
                    'NL:Amsterdam, NH',
                    'NO:Oslo, 03',
                    'NP:Kathmandu, P3',
                    'PH:Santa Rosa, LAG',
                    'PL:Krakow, 12',
                    'PT:Vila do Corvo, 20',
                    'RS:Belgrade, 00',
                    'SE:Lund, M',
                    'TH:Bangkok, 10',
                    'UA:Kyiv, 30',
                    'ZA:Cape Town, WC',
                    'AE:Dubai, DU',
                    'SN:Dakar, DK',
                    'QA:Doha, DA',
                    'SA:Jeddah, 02',
                    'KE:Nairobi, 30',
                    'HU:Budapest, BU',
                    'AT:Vienna, 9',
                    'UG:Kampala, 102',
                    'MM:Yangon, 06',
                    'VN:Hanoi, HN'
                ],

                'page'  =>   [
                    '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/',
                    '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard', '/dashboard',
                    '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register', '/register',
                    '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login', '/login',
                    '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome',
                    '/services', '/services', '/services', '/services', '/services',
                    '/settings', '/settings', '/settings', '/settings',
                    '/about', '/about', '/about', '/about',
                    '/contact', '/contact', '/contact',
                    '/settings/account', '/settings/account',
                    '/settings/security'
                ],

                'referrer'  => [
                    'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com', 'www.google.com',
                    'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com','www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com', 'www.bing.com',
                    'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com',
                    'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com', 'www.youtube.com',
                    'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com', 'www.reddit.com',
                    'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv', 'www.twitch.tv',
                    'www.wikipedia.org', 'www.wikipedia.org', 'www.wikipedia.org', 'www.wikipedia.org', 'www.wikipedia.org',
                    'www.quora.com', 'www.quora.com', 'www.quora.com', 'www.quora.com',
                    'www.amazon.com', 'www.amazon.com', 'www.amazon.com',
                    'www.baidu.com', 'www.baidu.com',
                    'www.ecosia.org',
                    'search.yahoo.com',
                    'search.aol.com',
                    'yandex.ru',
                    'l.facebook.com',
                    't.co',
                    'l.instagram.com',
                    'out.reddit.com',
                    'away.vk.com'
                ],

                'resolution' => [
                    '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080', '1920x1080',
                    '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768', '1366x768',
                    '360x640', '360x640', '360x640', '360x640', '360x640', '360x640', '360x640', '360x640', '360x640', '360x640', '360x640', '360x640',
                    '414x896', '414x896', '414x896', '414x896', '414x896', '414x896', '414x896', '414x896', '414x896',
                    '1536x864', '1536x864', '1536x864', '1536x864', '1536x864', '1536x864', '1536x864',
                    '375x667', '375x667', '375x667', '375x667','375x667', '375x667', '375x667',
                    '1440x900', '1440x900', '1440x900', '1440x900', '1440x900',
                    '1024x768', '1024x768', '1024x768', '1024x768',
                    '768x1024', '768x1024', '768x1024',
                    '360x760', '360x760',
                    '360x720'
                ],

                'language'    => [
                    'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en', 'en',
                    'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de',
                    'es',  'es',  'es',  'es',  'es',  'es', 'es',  'es',  'es',  'es',  'es',  'es',
                    'fr', 'fr', 'fr', 'fr', 'fr', 'fr', 'fr', 'fr',
                    'cn', 'cn', 'cn', 'cn', 'cn', 'cn',
                    'ro', 'ro', 'ro', 'ro',
                    'it', 'it', 'it',
                    'ru', 'ru',
                    'bg',
                    'hi',
                    'tr'
                ],

                'landing_page'     =>   [
                    '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/',
                    '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome', '/welcome',
                    '/register', '/register', '/register', '/register', '/register',
                    '/login', '/login', '/login', '/login',
                    '/services', '/services', '/services',
                    '/settings', '/settings',
                    '/dashboard', '/dashboard',
                    '/about',
                    '/contact',
                    '/settings/account',
                    '/settings/security',
                ],

                'campaign' => [
                    'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday', 'Black Friday',
                    'Cyber Monday', 'Cyber Monday', 'Cyber Monday', 'Cyber Monday', 'Cyber Monday', 'Cyber Monday', 'Cyber Monday', 'Cyber Monday',
                    'Christmas', 'Christmas', 'Christmas', 'Christmas',
                    'Winter sale', 'Winter sale', 'Winter sale',
                    'Social media', 'Social media',
                    'Newsletter'
                ],

                'event' => [
                    'Purchase:49:USD', 'Purchase:49:USD', 'Purchase:49:USD', 'Purchase:49:USD',
                    'Sale:99:EUR', 'Sale:99:EUR',
                    'Registration',
                    'Subscription',
                    'Contact form',
                ],

                'continent' => [
                    'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America', 'NA:North America',
                    'EU:Europe', 'EU:Europe', 'EU:Europe', 'EU:Europe', 'EU:Europe', 'EU:Europe', 'EU:Europe', 'EU:Europe', 'EU:Europe', 'EU:Europe',
                    'AS:Asia', 'AS:Asia', 'AS:Asia', 'AS:Asia', 'AS:Asia', 'AS:Asia', 'AS:Asia', 'AS:Asia',
                    'AF:Africa', 'AF:Africa', 'AF:Africa',
                    'SA:South America', 'SA:South America',
                    'AN:Antarctica',
                    'OC:Oceania',
                ],

                'visitors_hours' => ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],

                'pageviews_hours' => ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
            ];

            if (isset($days['pageviews'][$i]) && isset($days['visitors'][$i])) {
                $pageviews = $days['pageviews'][$i];
                $visitors = $days['visitors'][$i];
            } else {
                if ($i % 2 == 0) {
                    if ($i % 5 == 0) {
                        if (mt_rand(0, 1) == 0) {
                            $pageviews = mt_rand(9000, 10000);
                        } else {
                            $pageviews = mt_rand(11000, 12000);
                        }

                        if (mt_rand(0, 1) == 0) {
                            $visitors = mt_rand(3000, 4000);
                        } else {
                            $visitors = mt_rand(5000, 6000);
                        }
                    } else {
                        if (mt_rand(0, 1) == 0) {
                            $pageviews = mt_rand(9000, 10500);
                        } else {
                            $pageviews = mt_rand(9000, 12000);
                        }

                        if (mt_rand(0, 1) == 0) {
                            $visitors = mt_rand(3000, 4000);
                        } else {
                            $visitors = mt_rand(2750, 5250);
                        }
                    }
                } else {
                    if (mt_rand(0, 1) == 0) {
                        $pageviews = mt_rand(10500, 12000);
                    } else {
                        $pageviews = mt_rand(9000, 12000);
                    }

                    if (mt_rand(0, 1) == 0) {
                        $visitors = mt_rand(4000, 5000);
                    } else {
                        $visitors = mt_rand(2750, 5250);
                    }
                }
            }

            $cCount = false;
            foreach ($names as $name => $values) {
                if ($name == 'page' || $name == 'pageviews' || $name == 'pageviews_hours') {
                    $count = generateRandomNumbers($pageviews, count($values));
                } else {
                    if($name == 'campaign') {
                        $count = generateRandomNumbers(200, count($values));
                    } elseif($name == 'event') {
                        $count = generateRandomNumbers(100, count($values));
                    } elseif($name == 'country') {
                        $count = generateRandomNumbers($visitors, count($values));
                        if ($cCount === false) {
                            $cCount = $count;
                        }
                    } elseif($name == 'city') {
                        $cc = $visitors;
                    } elseif($name == 'referrer') {
                        $count = generateRandomNumbers(round((($visitors/100) * rand(25, 60))), count($values));
                    } else {
                        $count = generateRandomNumbers($visitors, count($values));
                    }
                }

                $c = 0;
                foreach ($values as $value) {
                    $db->query(sprintf("INSERT INTO `stats` (
                    `website_id`,
                    `name`,
                    `value`,
                    `count`,
                    `date`
                    ) VALUES (
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    DATE_SUB(CURRENT_DATE, INTERVAL ".$i." DAY)) ON DUPLICATE KEY UPDATE `count` = `count` + %s;",
                        $website_id,
                        $name,
                        $value,
                        ($name == 'city' ? $cCount[$c] : $count[$c]),
                        ($name == 'city' ? $cCount[$c] : $count[$c])
                    ));

                    $c++;
                }

                if($name == 'city') {
                    $cCount = false;
                }
            }
        }
    }
}
 
 
mysqli_close($db);
?>