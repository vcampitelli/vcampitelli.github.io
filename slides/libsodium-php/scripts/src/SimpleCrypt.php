<?php
namespace Vcampitelli;

use Exception;

/**
 * Simples classe para mostrar como utilizar a criptografia assimétrica com a libsodium
 */
class SimpleCrypt
{
    /**
     * Chave privada para encriptação
     *
     * @var string
     */
    protected $boxSecretKey;

    /**
     * Chave pública para encriptação
     *
     * @var string
     */
    public $boxPublicKey;

    /**
     * Construtor
     */
    public function __construct()
    {
        $boxKp = sodium_crypto_box_keypair();

        // Split the key for the crypto_box API for ease of use
        $this->boxSecretKey = sodium_crypto_box_secretkey($boxKp);
        $this->boxPublicKey = sodium_crypto_box_publickey($boxKp);
    }

    /**
     * Descriptografa uma mensagem
     *
     * @param  string $plainText Mensagem a ser enviada
     * @param  string $publicKey Destinatário
     *
     * @return string            Mensagem criptografada
     */
    public function encrypt(string $plainText, string $publicKey) : string
    {
        $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
            $this->boxSecretKey,
            $publicKey
        );
        $nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
        $ciphertext = sodium_crypto_box(
            $plainText,
            $nonce,
            $keypair
        );
        return $nonce . $ciphertext;
    }

    /**
     * Descriptografa uma mensagem
     *
     * @param  string $ciphertext Mensagem encriptada
     * @param  string $publicKey  Remetente
     *
     * @return string             Mensagem em texto plano
     */
    public function decrypt(string $ciphertext, string $publicKey) : string
    {
        $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
            $this->boxSecretKey,
            $publicKey
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

    /**
     * Retorna a chave pública para encriptação
     *
     * @return string
     */
    public function getBoxPublicKey()
    {
        return $this->boxPublicKey;
    }
}
