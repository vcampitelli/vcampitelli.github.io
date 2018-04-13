<?php
/**
 * @link https://paragonie.com/book/pecl-libsodium/read/03-utilities-helpers.md
 */

$x = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
// After an encryption
sodium_increment($x);

// compare
if (sodium_compare($message['nonce'], $expected_nonce) === 0) {
    // Proceed with crypto_box decryption
}

/**
 * @link https://hotexamples.com/examples/-/-/Sodium%5Cmemzero/php-sodium%5Cmemzero-function-examples.html
 */
for ($i = 0; $i < 5; ++$i) {
    $time = microtime(true);
    $random = random_bytes(10);
    for ($j = 0; $j < 5; ++$j) {
        bin2hex($random);
    }
    var_dump(microtime(true) - $time);

    $time = microtime(true);
    for ($j = 0; $j < 5; ++$j) {
        sodium_bin2hex($random);
    }
    var_dump(microtime(true) - $time);

    echo PHP_EOL;
    sleep(1);
}
