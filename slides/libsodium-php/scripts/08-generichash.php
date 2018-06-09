<?php
// Lê do stdin
$data = trim(fgets(STDIN));
if (empty($data)) {
    throw new Exception('Você deve informar os dados a serem hasheados.');
}

// Lê a chave privada
$secretKey = trim(file_get_contents('08-generichash.key'));

// Alternativa à md5/sha1
$hash = sodium_crypto_generichash($data);
echo 'Hash simples (alternativa a md5/sha1): ' . PHP_EOL;
var_dump(sodium_bin2hex($hash));

// MAC
echo 'HMAC: ' . PHP_EOL;
$blake2mac = sodium_crypto_generichash($data, $secretKey);
var_dump(sodium_bin2hex($blake2mac));
