<?php
define('KEY_FILE', 'openssl.key');

// Verifica se há o arquivo
if (is_file(KEY_FILE)) {
    return file_get_contents(KEY_FILE);
}

// Senão, gera um novo com uma chave de 256 bits
$key = openssl_random_pseudo_bytes(32);
file_put_contents(KEY_FILE, $key);
return $key;
