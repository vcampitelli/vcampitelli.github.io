<?php
// Lê do stdin
$password = trim(fgets(STDIN));
if (empty($password)) {
    echo 'Você deve informar a senha a ser hasheada.' . PHP_EOL;
    die(1);
}

$hash = sodium_crypto_pwhash_str(
    $password,
    SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
    SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
);
file_put_contents('09-pwhash.key', $hash);
echo $hash . PHP_EOL;
