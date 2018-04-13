<?php
// Lê a senha do stdin
echo "\e[0;33m";
echo 'Qual a senha para descriptografar esse arquivo? ';
echo "\e[0m";
$password = trim(fgets(STDIN));

echo "\e[0;33m";
echo '--------------------------------' . PHP_EOL;
echo 'Lendo arquivo /tmp/11-stream.enc' . PHP_EOL;
echo '--------------------------------' . PHP_EOL;
echo "\e[0m";

$chunkSize = 4096;
$input = fopen('/tmp/11-stream.enc', 'rb');

// Lendo os dados usados para gerar a criptografia
$alg = unpack('C', fread($input, 1))[1];
$opslimit = unpack('P', fread($input, 8))[1];
$memlimit = unpack('P', fread($input, 8))[1];
$salt = fread($input, SODIUM_CRYPTO_PWHASH_SALTBYTES);

$header = fread($input, SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES);

$key = sodium_crypto_pwhash(
    SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
    $password,
    $salt,
    $opslimit,
    $memlimit,
    $alg
);

$stream = sodium_crypto_secretstream_xchacha20poly1305_init_pull($header, $key);
do {
    $chunk = fread($input, $chunkSize + SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_ABYTES);
    list($decrypted, $tag) = sodium_crypto_secretstream_xchacha20poly1305_pull($stream, $chunk);
    if ($tag === SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL) {
        break;
    }
    echo trim($decrypted, "\0") . PHP_EOL;
} while (!feof($input));

fclose($input);

echo "\e[0;33m";
echo '--------------------------------------------' . PHP_EOL;
echo 'Fim da leitura do arquivo /tmp/11-stream.enc' . PHP_EOL;
echo '--------------------------------------------' . PHP_EOL;
echo "\e[0m";
