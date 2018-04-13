<?php
// Lê a senha gerada pelo arquivo 09-pwhash.php
$storedPassword = trim(file_get_contents('09-pwhash.key'));
if (empty($storedPassword)) {
    echo 'Você deve primeiro executar o script 09-pwhash.php para gerar uma senha.' . PHP_EOL;
    die(1);
}
// Lê do stdin
$userPassword = trim(fgets(STDIN));
if (empty($userPassword)) {
    echo 'Você deve informar a senha a ser verificada.' . PHP_EOL;
    die(1);
}

var_dump(sodium_crypto_pwhash_str_verify($storedPassword, $userPassword));
