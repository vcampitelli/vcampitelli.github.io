<?php
// Lê do stdin
$message = trim(fgets(STDIN));
if (empty($message)) {
    echo 'Você deve informar o texto a ser enviado.' . PHP_EOL;
    die(1);
}

require __DIR__ . '/src/SimpleMessageExchange.php';

$alice = new Vcampitelli\SimpleMessageExchange();
$bob = new Vcampitelli\SimpleMessageExchange();

// On Alice's computer:
$crypted = $alice->send($bob, $message);
echo $crypted . PHP_EOL;

// On Bob's computer:
echo $bob->read($alice, $crypted) . PHP_EOL;
