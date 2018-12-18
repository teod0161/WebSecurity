<?php
function base64UrlEncode($data)
{
    $urlSafeData = strtr(base64_encode($data), '+/', '-_');

    return rtrim($urlSafeData, '=');
}

function base64UrlDecode($data)
{
    $urlUnsafeData = strtr($data, '-_', '+/');

    $paddedData = str_pad($urlUnsafeData, strlen($data) % 4, '=', STR_PAD_RIGHT);

    return base64_decode($paddedData);
}

// Highly confidential
$secret = "LuvTw5pYngWvDsn6grggImBQhdjeD/T+QRHwpSikyQs=";
//method used to essentially create the token
function generateJWT(
    $algo,
    $header,
    $payload,
    $secret
) {
    $headerEncoded = base64UrlEncode(json_encode($header));
    $payloadEncoded = base64UrlEncode(json_encode($payload));
    $dataEncoded = "$headerEncoded.$payloadEncoded";
    $rawSignature = hash_hmac($algo, $dataEncoded, $secret, true);
    $signatureEncoded = base64UrlEncode($rawSignature);
    $jwt = "$dataEncoded.$signatureEncoded";

    return $jwt;
}
//verified the signature of the token, return a boolean
function verifyJWT($algo, $jwt, $secret)
{
    list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $jwt);
    $dataEncoded = "$headerEncoded.$payloadEncoded";
    $signature = base64UrlDecode($signatureEncoded);
    $rawSignature = hash_hmac($algo, $dataEncoded, $secret, true);

    return hash_equals($rawSignature, $signature);
}
