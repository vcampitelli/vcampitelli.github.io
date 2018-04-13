<?php
echo 'Texto a ser criptografado: ';
$message = trim(fgets(STDIN));
if (empty($message)) {
    echo 'Você deve informar o texto a ser criptografado.' . PHP_EOL;
    die(1);
}

echo 'Dados extras: ';
$ad = trim(fgets(STDIN));
if (empty($ad)) {
    echo 'Você deve informar os dados extras a serem autenticados.' . PHP_EOL;
    die(1);
}

// Lê a chave
$key = file_get_contents('12-aead.key');
$nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);

$ciphertext = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
    $message,
    $ad,
    $nonce,
    $key
);
echo json_encode([
    'ad'   => $ad,
    'message' => sodium_bin2hex("{$nonce}{$ciphertext}")
], true) . PHP_EOL;
