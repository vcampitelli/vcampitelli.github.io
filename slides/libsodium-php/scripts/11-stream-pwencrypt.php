<?php
// Lê a senha do stdin
echo "\e[0;33m";
echo 'Qual a senha para criptografar esse arquivo? ';
echo "\e[0m";
$password = trim(fgets(STDIN));

echo "\e[0;33m";
echo '-----------------------------------------------' . PHP_EOL;
echo 'Iniciando escrita no arquivo /tmp/11-stream.enc' . PHP_EOL;
echo '-----------------------------------------------' . PHP_EOL;
echo "\e[0m";

// Gerando uma chave forte a partir da senha
$alg = SODIUM_CRYPTO_PWHASH_ALG_DEFAULT;
$opslimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE;
$memlimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE;
$salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);
$key = sodium_crypto_pwhash(
    SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
    $password,
    $salt,
    $opslimit,
    $memlimit,
    $alg
);

$chunkSize = 4096;
$output = fopen('/tmp/11-stream.enc', 'wb');

// Escreve os valores usados para gerar esses dados
fwrite($output, pack('C', $alg));
fwrite($output, pack('P', $opslimit));
fwrite($output, pack('P', $memlimit));
fwrite($output, $salt);

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
echo 'Fim da escrita no arquivo /tmp/11-stream.enc' . PHP_EOL;
echo '--------------------------------------------' . PHP_EOL;
echo "\e[0m";
