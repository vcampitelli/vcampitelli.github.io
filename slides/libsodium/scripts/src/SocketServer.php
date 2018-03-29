<?php
declare(strict_types=1);

namespace Vcampitelli;

/**
 * Socket server baseado na palestra do Alexandre Gaigalas na PHP Experience 2016
 *
 * @link https://www.youtube.com/watch?v=ZRYMzS97HVQ
 */
class SocketServer
{
    /**
     * Socket
     *
     * @var resource
     */
    protected $socket;

    /**
     * Clientes
     *
     * @var array
     */
    protected $clients;

    /**
     * ID incremental dos clientes conectados
     *
     * @var int
     */
    protected $clientIncrement;

    /**
     * Cores de cada cliente
     *
     * @var array
     */
    protected $colors = [
        "\e[0;31m",
        "\e[0;32m",
        "\e[0;33m",
        "\e[0;34m",
        "\e[0;35m",
        "\e[0;36m",
        "\e[0;37m"
    ];

    /**
     * Construtor
     *
     * @param int $port Porta para escutar
     */
    public function __construct(int $port)
    {
        $this->socket = stream_socket_server("tcp://127.0.0.1:{$port}", $errno, $errstr);
        if (!$this->socket) {
            throw new Exception($errstr, $errno);
        }
    }

    public function __destruct()
    {
        fclose($this->socket);
    }

    /**
     * Inicia o socket
     *
     * @param callable $formatMessage Send message function
     */
    public function start(callable $formatMessage = null) : void
    {
        if ($formatMessage === null) {
            $formatMessage = function ($sender, $receiver, string $message) {
                return $message;
            };
        }

        $this->clients = [];
        $this->clientIncrement = 0;
        $colorsCount = count($this->colors);

        while (true) {
            $reads = $this->clients;
            $reads[] = $this->socket;
            stream_select($reads, $writes, $except, 300000);

            if (in_array($this->socket, $reads)) {
                $client = stream_socket_accept($this->socket);
                if ($client) {
                    ++$this->clientIncrement;
                    echo "Novo cliente conectado: {$this->clientIncrement}" . PHP_EOL;
                    $this->clients[$this->clientIncrement] = $client;
                }

                unset($reads[array_search($this->socket, $reads)]);
            }

            foreach ($reads as $clientId => $client) {
                $data = fread($client, 128);
                if (!$data) {
                    unset($this->clients[$clientId]);
                    fclose($client);
                    echo "Cliente {$clientId} desconectou" . PHP_EOL;
                    continue;
                }

                $color = $this->colors[($clientId - 1) % $colorsCount];
                $data = "{$color}[{$clientId}] {$data}\e[0m";
                foreach ($this->clients as $receiverId => $receiver) {
                    if ($receiverId != $clientId) {
                        fwrite($receiver, $formatMessage($client, $receiver, $data));
                    }
                }
            }
        }
    }
}
