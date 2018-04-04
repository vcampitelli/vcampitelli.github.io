<?php
declare(strict_types=1);

namespace Vcampitelli;

/**
 * Socket server baseado na palestra do Alexandre Gaigalas na PHP Experience 2016
 *
 * @link https://www.youtube.com/watch?v=ZRYMzS97HVQ
 */
class CryptSocketServer extends SocketServer
{
    /**
     * Objeto quer lida com a criptografia
     *
     * @var SimpleCrypt
     */
    protected $crypt;

    /**
     * Pool de public keys de cada cliente
     *
     * @var string[]
     */
    protected $publicKeys = [];

    /**
     * Inicialização
     */
    protected function init() : void
    {
        $this->crypt = new SimpleCrypt();
    }

    /**
     * Cria um objeto para o novo cliente
     *
     * @param  string $clientId ID do cliente
     * @param  object $client   Cliente
     *
     * @return bool
     */
    protected function handleNewClient($clientId, $client) : bool
    {
        // Envia a public key do server para o cliente
        fwrite($client, sodium_bin2hex($this->crypt->boxPublicKey) . PHP_EOL);

        // Recebe e guarda a public key do cliente
        $publicKey = trim(fgets($client, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES * 2 + 1));
        if ($publicKey === false) {
            echo "Erro ao receber public key de {$clientId}" . PHP_EOL;
            return false;
        }
        echo "Recebido public key de {$clientId}: " . sodium_bin2hex($publicKey) . PHP_EOL;
        $this->publicKeys[$clientId] = $publicKey;
        return true;
    }

    /**
     * Lê uma mensagem enviada pelo cliente especificado
     *
     * @param  int    $clientId ID do cliente
     * @param  string $data     Mensagem enviada
     *
     * @return string
     */
    protected function readFrom(int $clientId, string $data) : string
    {
        return $this->crypt->decrypt(
            sodium_hex2bin($data),
            $this->publicKeys[$clientId]
        );
    }

    /**
     * Formata a mensagem
     *
     * @param  int    $receiverId ID do cliente de destino
     * @param  string $message    Mensagem a ser formatada
     *
     * @return string
     */
    protected function formatMessage(int $receiverId, string $message) : string
    {
        return $this->crypt->encrypt($message, $this->publicKeys[$receiverId]);
    }
}
