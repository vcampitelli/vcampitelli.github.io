<?php
if (!is_file('10-stream.key')) {
    echo 'Primeiro, rode o arquivo 10-stream-encrypt.php' . PHP_EOL;
    die(1);
}

echo "\e[0;33m";
echo '--------------------------------' . PHP_EOL;
echo 'Lendo arquivo /tmp/10-stream.enc' . PHP_EOL;
echo '--------------------------------' . PHP_EOL;
echo "\e[0m";

$chunkSize = 4096;
$key = sodium_hex2bin(file_get_contents('10-stream.key'));
$input = fopen('/tmp/10-stream.enc', 'rb');

$header = fread($input, SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES);

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
echo 'Fim da leitura do arquivo /tmp/10-stream.enc' . PHP_EOL;
echo '--------------------------------------------' . PHP_EOL;
echo "\e[0m";
