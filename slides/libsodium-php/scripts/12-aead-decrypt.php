<?php
$data = trim(fgets(STDIN));
if (empty($data)) {
    echo 'Você deve informar o texto a ser descriptografado.' . PHP_EOL;
    die(1);
}

// Converte o JSON
$data = json_decode($data);
if ((empty($data)) || (empty($data->ad)) || (empty($data->message))) {
    throw new Exception('Os dados informados não são válidos. Utilize o script 12-aead-encrypt.php para gerá-los.');
}

// Lê a chave
$key = file_get_contents('12-aead.key');

$data->message = sodium_hex2bin($data->message);
$nonce = substr($data->message, 0, SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
$ciphertext = substr($data->message, SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);

$decrypted = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
    $ciphertext,
    $data->ad,
    $nonce,
    $key
);
if ($decrypted === false) {
    echo 'Erro na verificação' . PHP_EOL;
    die(1);
}
var_dump($decrypted);
