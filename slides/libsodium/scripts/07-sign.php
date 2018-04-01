<?php
// Lê do stdin
$message = trim(fgets(STDIN));
if (empty($message)) {
    echo 'Você deve informar o texto a ser autenticado.' . PHP_EOL;
    die(1);
}

// Lê a chave privada
$secretKey = trim(file_get_contents('07-secret.key'));

echo json_encode([
    'message'   => $message,
    'signature' => sodium_bin2hex(sodium_crypto_sign_detached($message, $secretKey))
]) . PHP_EOL;
