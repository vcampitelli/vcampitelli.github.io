<?php
if (is_file('10-stream.key')) {
    $key = sodium_hex2bin(file_get_contents('10-stream.key'));
} else {
    $key = sodium_crypto_secretstream_xchacha20poly1305_keygen();
    file_put_contents('10-stream.key', sodium_bin2hex($key));
}

echo "\e[0;33m";
echo '-----------------------------------------------' . PHP_EOL;
echo 'Iniciando escrita no arquivo /tmp/10-stream.enc' . PHP_EOL;
echo '-----------------------------------------------' . PHP_EOL;
echo "\e[0m";

$chunkSize = 4096;
$output = fopen('/tmp/10-stream.enc', 'wb');

list($stream, $header) = sodium_crypto_secretstream_xchacha20poly1305_init_push($key);

fwrite($output, $header);

$tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE;
do {
    $chunk = trim(fread(STDIN, $chunkSize), "\n");
    if (empty($chunk)) {
        $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL;
    }
    $chunk = str_pad($chunk, $chunkSize, "\0");
    $encrypted = sodium_crypto_secretstream_xchacha20poly1305_push($stream, $chunk, '', $tag);
    fwrite($output, $encrypted);
} while ($tag !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);

fclose($output);

echo "\e[0;33m";
echo '--------------------------------------------' . PHP_EOL;
echo 'Fim da escrita no arquivo /tmp/10-stream.enc' . PHP_EOL;
echo '--------------------------------------------' . PHP_EOL;
echo "\e[0m";
