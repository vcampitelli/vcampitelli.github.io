<?php
namespace Vcampitelli;

class SimpleMessageExchange
{
    protected $boxSecretKey;

    protected $boxPublicKey;

    public function __construct()
    {
        $boxKp = sodium_crypto_box_keypair();
        $signKp = sodium_crypto_sign_keypair();

        // Split the key for the crypto_box API for ease of use
        $this->boxSecretKey = sodium_crypto_box_secretkey($boxKp);
        $this->boxPublicKey = sodium_crypto_box_publickey($boxKp);

        // Split the key for the crypto_sign API for ease of use
        $signSecretKey = sodium_crypto_sign_secretkey($signKp);
        $signPublicKey = sodium_crypto_sign_publickey($signKp);
    }

    public function send(SimpleMessageExchange $destination, string $message) : string
    {
        $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
            $this->boxSecretKey,
            $destination->getBoxPublicKey()
        );
        $nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
        $ciphertext = sodium_crypto_box(
            $message,
            $nonce,
            $keypair
        );
        return $nonce . $ciphertext;
    }

    public function read(SimpleMessageExchange $sender, string $ciphertext) : string
    {
        $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
            $this->boxSecretKey,
            $sender->getBoxPublicKey()
        );

        // Separa o nonce do texto
        $nonce = substr($ciphertext, 0, SODIUM_CRYPTO_BOX_NONCEBYTES);
        $ciphertext = substr($ciphertext, SODIUM_CRYPTO_BOX_NONCEBYTES);

        $plaintext = sodium_crypto_box_open(
            $ciphertext,
            $nonce,
            $keypair
        );
        if ($plaintext === false) {
            throw new Exception("Malformed message or invalid MAC");
        }
        return $plaintext;
    }

    public function getBoxPublicKey() : string
    {
        return $this->boxPublicKey;
    }
}
