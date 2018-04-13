<?php
// Lê do stdin
$message = trim(fgets(STDIN));
if (empty($message)) {
    echo 'Você deve informar o texto a ser enviado.' . PHP_EOL;
    die(1);
}

require __DIR__ . '/src/SimpleCrypt.php';

$alice = new Vcampitelli\SimpleCrypt();
$bob = new Vcampitelli\SimpleCrypt();

// On Alice's computer:
$crypted = $alice->encrypt($message, $bob->publicKey);
echo "\e[0;34m[Crypt]\e[0m {$crypted}" . PHP_EOL;

// On Bob's computer:
echo "\e[0;35m[Decrypt]\e[0m " . $bob->decrypt($crypted, $alice->publicKey) . PHP_EOL;
