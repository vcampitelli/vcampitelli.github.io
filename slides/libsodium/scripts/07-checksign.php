<?php
try {
    // Lê do stdin
    $data = trim(fgets(STDIN));
    if (empty($data)) {
        throw new Exception('Você deve informar os dados a serem validados.');
    }

    // Converte o JSON
    $data = json_decode($data);
    if ((empty($data)) || (empty($data->message)) || (empty($data->signature))) {
        throw new Exception('Os dados informados não são válidos. Utilize o script 07-sign.php para gerá-los.');
    }
    if (($data->signature = sodium_hex2bin($data->signature)) === false) {
        throw new Exception('A assinatura informada não é válida.');
    }

    // Lê a chave pública
    $publicKey = trim(file_get_contents('07-public.key'));

    // Verifica a assinatura
    if (!sodium_crypto_sign_verify_detached($data->signature, $data->message, $publicKey)) {
        throw new Exception('A autenticação falhou.');
    }

    echo 'Autenticação efetuada com sucesso.' . PHP_EOL;
} catch (Exception $e) {
    echo "\e[0;31m[" . get_class($e) . "] {$e->getMessage()}\e[0m" . PHP_EOL;
    die(1);
}
