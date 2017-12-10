<?php
// Dados do STDIN
if (ftell(STDIN) === false) {
    echo 'Texto a ser criptografado: ';
}
$data = trim(fgets(STDIN));
if (empty($data)) {
    echo 'Nenhum dado foi informado.';
    die(1);
}

// Buscamos a chave de 256 bits
$key = require 'key.php';

// Geramos o IV
$iv = openssl_random_pseudo_bytes(
    openssl_cipher_iv_length('aes-256-ctr')
);

// Concatenamos o IV para que ele seja utilizado no decrypt
echo base64_encode($iv . openssl_encrypt($data, 'aes-256-ctr', $key, 0, $iv));
echo PHP_EOL;
