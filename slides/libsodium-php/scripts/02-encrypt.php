<?php
// Lê do stdin
$plaintext = trim(fgets(STDIN));
if (empty($plaintext)) {
    echo 'Você deve informar o texto a ser criptografado.' . PHP_EOL;
    die(1);
}

// Lê a chave
$key = trim(file_get_contents('02-encrypt.key'));

// Gera um nonce
$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

// Prefixa o nonce junto com os dados para poder ser recuperado pelo decrypt
echo sodium_bin2hex($nonce . sodium_crypto_secretbox($plaintext, $nonce, $key));
