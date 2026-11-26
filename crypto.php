<?php
require_once 'config.php';

function encryptData($plainText) {
if ($plainText === '') {
return '';
}
$ivLength = openssl_cipher_iv_length(ENC_METHOD);
$iv = openssl_random_pseudo_bytes($ivLength);
$encrypted = openssl_encrypt($plainText, ENC_METHOD, ENC_KEY, OPENSSL_RAW_DATA, $iv);
return base64_encode($iv . $encrypted);
}

function decryptData($encryptedText) {
if ($encryptedText === '') {
return '';
}
$data = base64_decode($encryptedText);
$ivLength = openssl_cipher_iv_length(ENC_METHOD);
$iv = substr($data, 0, $ivLength);
$cipherText = substr($data, $ivLength);
return openssl_decrypt($cipherText, ENC_METHOD, ENC_KEY, OPENSSL_RAW_DATA, $iv);
}
?>
