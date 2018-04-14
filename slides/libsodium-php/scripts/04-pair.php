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

// Alice
$crypted = $alice->encrypt($message, $bob->boxPublicKey);
echo "\e[0;34m[Crypt]\e[0m " . sodium_bin2hex($crypted) . PHP_EOL;

// Bob
echo "\e[0;35m[Decrypt]\e[0m " . $bob->decrypt($crypted, $alice->boxPublicKey) . PHP_EOL;
