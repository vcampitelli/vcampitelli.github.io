<?php
// Lê do stdin
$port = (empty($argv[1])) ? 0 : (int) $argv[1];
if ($port < 1) {
    $port = 8000;
}

ob_implicit_flush();

// Cria o socket
$socket = stream_socket_client("tcp://127.0.0.1:{$port}", $errno, $errstr, 30);
if (!$socket) {
    echo "{$errstr} ({$errno})" . PHP_EOL;
    die(1);
}

// Recebe a public key do server
$publicKey = trim(fgets($socket, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES * 2 + 1));
echo "Recebido public key do server: {$publicKey}" . PHP_EOL;
$publicKey = sodium_hex2bin($publicKey);

stream_set_timeout($socket, 1);

// Gera o par de chaves e avisa o server
require __DIR__ . '/src/SimpleCrypt.php';
$crypt = new Vcampitelli\SimpleCrypt();
fwrite($socket, $crypt->boxPublicKey . PHP_EOL);

while (true) {
    echo 'Digite aqui sua mensagem: ';
    $line = trim(fgets(STDIN));
    if (!empty($line)) {
        fwrite($socket, sodium_bin2hex($crypt->encrypt($line, $publicKey)) . PHP_EOL);
    }

    echo 'Aguardando server...      ';
    $line = trim(fgets($socket));
    if (!empty($line)) {
        echo PHP_EOL . $crypt->decrypt($line, $publicKey) . PHP_EOL;
        continue;
    }
    echo "\r";
}
fclose($socket);
