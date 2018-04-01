<?php
// Lê do stdin
$plaintext = trim(fgets(STDIN));
if (empty($plaintext)) {
    echo 'Você deve informar o texto a ser autenticado.' . PHP_EOL;
    die(1);
}

// Lê a chave
$key = trim(file_get_contents('03-auth.key'));

$mac = sodium_crypto_auth($plaintext, $key);
echo json_encode([
    'message' => $plaintext,
    'auth'    => sodium_bin2hex($mac)
]) . PHP_EOL;
