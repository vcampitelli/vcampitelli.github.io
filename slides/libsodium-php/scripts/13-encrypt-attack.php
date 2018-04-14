<?php
// Classe de criptografia sem autenticação
$crypt = new class {
    public function __construct()
    {
        $this->key = random_bytes(SODIUM_CRYPTO_STREAM_KEYBYTES);
    }

    public function encrypt($message)
    {
        $nonce = random_bytes(SODIUM_CRYPTO_STREAM_NONCEBYTES);
        $ciphertext = sodium_crypto_stream_xor($message, $nonce, $this->key);
        return sodium_bin2hex($nonce . $ciphertext);
    }

    public function decrypt($text)
    {
        try {
            $text = sodium_hex2bin($text);
            $nonce = mb_substr($text, 0, SODIUM_CRYPTO_STREAM_NONCEBYTES, '8bit');
            $ciphertext = mb_substr($text, SODIUM_CRYPTO_STREAM_NONCEBYTES, null, '8bit');

            return sodium_crypto_stream_xor($ciphertext, $nonce, $this->key);
        } catch (Exception $ex) {
            return false;
        }
    }
};

// Simulando um login
$ciphertext = $crypt->encrypt(json_encode([
    'user' => 1,
    'admin' => 0
]));
echo 'Ciphertext:' . PHP_EOL;
var_dump($ciphertext);

// Valores possíveis em um hexadecimal
echo PHP_EOL . 'Ataques:' . PHP_EOL;
$alphabet = [
    0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
    'a', 'b', 'c', 'd', 'e', 'f'
];

$countAlphabet = count($alphabet);
$visited = [];
for ($i = 0; $i < strlen($ciphertext); ++$i) {
    $temp = $ciphertext;
    for ($x = 0; $x < $countAlphabet; ++$x) {
        $temp[$i] = $alphabet[$x];

        // Evita usar 2x a mesma string :-)
        if (isset($visited[$temp])) {
            continue;
        }
        $visited[$temp] = true;

        $decrypt = str_replace("\n", '', $crypt->decrypt($temp));
        if (!empty($decrypt)) {
            $json = json_decode($decrypt);
            $isValid = ((!empty($json)) && (isset($json->user)) && (isset($json->admin))) ? 'Valid' : 'Invalid';
            echo (($isValid == 'Valid') ? "\e[0;32m" : "\e[0;31m") .
                "{$temp}\t{$isValid}\t{$decrypt}" . PHP_EOL;
        }
    }
}
echo "\e[0m" . PHP_EOL;
