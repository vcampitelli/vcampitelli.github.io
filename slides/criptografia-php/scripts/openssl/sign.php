<?php
// Dados a serem assinados
$data = trim(file_get_contents('data_to_be_signed.txt'));
if (empty($data)) {
    echo 'Nenhum dado foi informado no arquivo data_to_be_signed.txt.';
    die(1);
}

$privateKey = openssl_pkey_get_private('file://' . __DIR__ . '/private.key');
openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
echo $signature;
echo PHP_EOL;
