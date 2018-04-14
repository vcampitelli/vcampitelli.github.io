<?php
// Classe de criptografia com autenticação
$crypt = new class {
    public function __construct()
    {
        $this->encKey = random_bytes(SODIUM_CRYPTO_STREAM_KEYBYTES);
        $this->authKey = random_bytes(SODIUM_CRYPTO_AUTH_BYTES);
    }

    public function encrypt($message)
    {
        $nonce = random_bytes(SODIUM_CRYPTO_STREAM_NONCEBYTES);
        $ciphertext = sodium_crypto_stream_xor($message, $nonce, $this->encKey);

        $mac = sodium_crypto_auth($nonce . $ciphertext, $this->authKey);

        return sodium_bin2hex($mac . $nonce . $ciphertext);
    }

    public function decrypt($text)
    {
        $text = sodium_hex2bin($text);


        $mac = mb_substr(
            $text,
            0,
            SODIUM_CRYPTO_AUTH_BYTES,
            '8bit'
        );
        $nonce = mb_substr(
            $text,
            SODIUM_CRYPTO_AUTH_BYTES,
            SODIUM_CRYPTO_STREAM_NONCEBYTES,
            '8bit'
        );
        $ciphertext = mb_substr(
            $text,
            SODIUM_CRYPTO_AUTH_BYTES + SODIUM_CRYPTO_STREAM_NONCEBYTES,
            null,
            '8bit'
        );

        if (!sodium_crypto_auth_verify($mac, $nonce . $ciphertext, $this->authKey)) {
            throw new Exception('Autenticação inválida');
        }

        return sodium_crypto_stream_xor($ciphertext, $nonce, $this->encKey);
    }
};

// Simulando um login
$ciphertext = $crypt->encrypt(json_encode([
    'user' => 1,
    'admin' => 0
]));
echo 'Ciphertext:' . PHP_EOL;
var_dump($ciphertext);
var_dump($crypt->decrypt($ciphertext));

// Valores possíveis em um hexadecimal
echo PHP_EOL . 'Ataques:' . PHP_EOL;
$alphabet = [
    0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
    'a', 'b', 'c', 'd', 'e', 'f'
];
$countAlphabet = count($alphabet);

// Evita usar 2x a mesma string :-)
$visited = [
    $ciphertext => true
];

for ($i = 0; $i < strlen($ciphertext); ++$i) {
    $temp = $ciphertext;
    for ($x = 0; $x < $countAlphabet; ++$x) {
        $temp[$i] = $alphabet[$x];

        if (isset($visited[$temp])) {
            continue;
        }
        $visited[$temp] = true;

        try {
            $decrypt = str_replace("\n", '', $crypt->decrypt($temp));
            if (!empty($decrypt)) {
                $json = json_decode($decrypt);
                $isValid = ((!empty($json)) && (isset($json->user)) && (isset($json->admin))) ? 'Valid' : 'Invalid';
                echo (($isValid == 'Valid') ? "\e[0;32m" : "\e[0;31m") .
                    "{$temp}\t{$isValid}\t{$decrypt}" . PHP_EOL;
            }
        } catch (Exception $ex) {
            echo "\e[0;31m{$temp}\tInvalid\t{$ex->getMessage()}" . PHP_EOL;
        }
    }
}
