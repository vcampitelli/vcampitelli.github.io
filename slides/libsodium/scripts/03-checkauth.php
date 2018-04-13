<?php
try {
    // Lê do stdin
    $data = trim(fgets(STDIN));
    if (empty($data)) {
        throw new Exception('Você deve informar os dados a serem validados.');
    }

    // Converte o JSON
    $data = json_decode($data);
    if ((empty($data)) || (empty($data->message)) || (empty($data->auth))) {
        throw new Exception('Os dados informados não são válidos. Utilize o script 03-auth.php para gerá-los.');
    }
    if (($data->auth = sodium_hex2bin($data->auth)) === false) {
        throw new Exception('A assinatura informada não é válida.');
    }

    // Lê a chave
    $key = trim(file_get_contents('03-auth.key'));

    // Verifica a assinatura
    if (!sodium_crypto_auth_verify($data->auth, $data->message, $key)) {
        sodium_memzero($key);
        throw new Exception('A autenticação falhou.');
    }

    echo 'Autenticação efetuada com sucesso.' . PHP_EOL;
} catch (Exception $e) {
    echo "\e[0;31m[" . get_class($e) . "] {$e->getMessage()}\e[0m" . PHP_EOL;
    die(1);
}
