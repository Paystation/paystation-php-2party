<?php

$pstn_pi = 'paystation-id';
$pstn_gi = 'gateway-id';
$hmac_security_code = 'hmac-security-code';
$pstn_cn = '5123456789012346';
$pstn_ex = '2105';
$pstn_ms = makePaystationMerchantSession(); //unique identifier
$pstn_am = "100"; // Approved (Response Code 0)
//$pstn_am = "151"; // Insufficient Funds (Response Code 5)
//$pstn_am = "112"; // Invalid Transaction (Response Code 8)
//$pstn_am = "154"; // Expired Card (Response Code 4)
//$pstn_am = "191"; // Error communicating with Bank (Response Code 6)

// Transaction parameters are passed in as POST variables in standard key/value pairs
$params = [
    'paystation' => '_empty',
    'pstn_pi' => $pstn_pi,
    'pstn_gi' => $pstn_gi,
    'pstn_ms' => $pstn_ms,
    'pstn_am' => $pstn_am,
    'pstn_cn' => $pstn_cn,
    'pstn_ex' => $pstn_ex,
    'pstn_2p' => 't',
    'pstn_nr' => 't',
    'pstn_tm' => 't',
    'pstn_rf' => 'JSON'
];

$post_params = http_build_query($params);

// HMAC hash and timestamp are passed in as part of the query string
$get_params = generateHMACParams($post_params, $hmac_security_code);

$url = 'https://www.paystation.co.nz/direct/paystation.dll?paystation';
$url .= $get_params;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);

$json = json_decode($result, true);
$json = isset($json['response']) ? $json['response'] : null;

$transactionId = $json['PaystationTransactionID'];
$code = $json['PaystationErrorCode'];
$message = $json['PaystationErrorMessage'];

echo "Transaction id: $transactionId, Error code: $code, Message: $message";

function generateHMACParams($body, $key)
{
    $hmac_timestamp = time();
    $hmac_hash = hash_hmac('sha512', "{$hmac_timestamp}paystation$body", $key);
    return http_build_query(['pstn_HMACTimestamp' => $hmac_timestamp, 'pstn_HMAC' => $hmac_hash]);
}

function makePaystationMerchantSession()
{
    return uniqid() . time();
}
