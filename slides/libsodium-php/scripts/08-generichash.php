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
var_dump(sodium_bin2hex($hash));

// MAC
$blake2mac = sodium_crypto_generichash($data, $secretKey);
var_dump(sodium_bin2hex($blake2mac));
