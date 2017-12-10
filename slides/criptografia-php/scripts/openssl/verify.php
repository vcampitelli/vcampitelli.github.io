<?php
// Dados do STDIN
if (ftell(STDIN) === false) {
    echo 'Assinatura a ser verificada: ';
}
$signature = trim(fgets(STDIN));
if (empty($signature)) {
    echo 'Nenhum dado foi informado.';
    die(1);
}

// Dados a serem assinados
$data = trim(file_get_contents('data_to_be_signed.txt'));
if (empty($data)) {
    echo 'Nenhum dado foi informado no arquivo data_to_be_signed.txt.';
    die(1);
}

$pubKey = openssl_pkey_get_public('file://' . __DIR__ . '/public.pem');
var_dump(openssl_verify($data, $signature, $pubKey, 'sha256WithRSAEncryption'));
echo PHP_EOL;
