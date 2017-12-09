<?php
// Dados do STDIN
if (ftell(STDIN) === false) {
    echo 'Texto a ser descriptografado: ';
}
$data = trim(fgets(STDIN));
if (empty($data)) {
    echo 'Nenhum dado foi informado.';
    die(1);
}

$data = base64_decode($data);

// Separamos o IV do começo
$ivLength = openssl_cipher_iv_length('aes-256-ctr');
$iv = substr($data, 0, $ivLength);
$crypt = substr($data, $ivLength);

// Buscamos a chave de 256 bits
$key = require 'key.php';

echo openssl_decrypt($crypt, 'aes-256-ctr', $key, 0, $iv);
echo PHP_EOL;
