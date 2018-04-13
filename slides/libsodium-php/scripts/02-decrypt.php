<?php
// Lê do stdin
$ciphertext = trim(fgets(STDIN));
if (empty($ciphertext)) {
    echo 'Você deve informar o texto a ser descriptografado.' . PHP_EOL;
    die();
}
$ciphertext = sodium_hex2bin($ciphertext);

// Lê a chave
$key = trim(file_get_contents('02-encrypt.key'));

// Separa o nonce do texto
$nonce = substr($ciphertext, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
$ciphertext = substr($ciphertext, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

// Descriptografa
$plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
if ($plaintext === false) {
    echo 'Erro ao descriptografar.' . PHP_EOL;
    die();
}

echo $plaintext . PHP_EOL;
