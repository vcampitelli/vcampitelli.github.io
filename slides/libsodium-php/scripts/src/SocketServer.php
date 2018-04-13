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
    protected $clientId;

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
        ob_implicit_flush();
        $this->socket = stream_socket_server("tcp://127.0.0.1:{$port}", $errno, $errstr);
        if (!$this->socket) {
            throw new Exception($errstr, $errno);
        }

        $this->init();
    }

    /**
     * Inicialização
     */
    protected function init() : void
    {
    }

    /**
     * Fecha o socket
     */
    public function __destruct()
    {
        fclose($this->socket);
    }

    /**
     * Inicia o socket
     */
    public function start() : void
    {
        $this->clients = [];
        $this->clientId = 0;
        $colorsCount = count($this->colors);

        while (true) {
            $reads = $this->clients;
            $reads[] = $this->socket;
            stream_select($reads, $writes, $except, 300000);

            if (in_array($this->socket, $reads)) {
                $client = stream_socket_accept($this->socket);
                if ($client) {
                    ++$this->clientId;
                    list($ip, $port) = explode(':', stream_socket_get_name($client, true));
                    echo "Novo cliente conectado: {$this->clientId} (porta {$port})" . PHP_EOL;

                    // Avisa os outros clientes que há um novo cliente conectado
                    if ($this->handleNewClient($this->clientId, $client)) {
                        $this->clients[$this->clientId] = $client;
                    }
                }

                unset($reads[array_search($this->socket, $reads)]);
            }

            foreach ($reads as $clientId => $client) {
                // Ignora as mensagens do server
                if (!$clientId) {
                    continue;
                }

                $data = fgets($client);
                if (!$data) {
                    unset($this->clients[$clientId]);
                    fclose($client);
                    echo "Cliente {$clientId} desconectou" . PHP_EOL;
                    continue;
                }

                $data = trim($data);
                if (empty($data)) {
                    continue;
                }
                $data = $this->readFrom($clientId, $data);

                $color = $this->colors[($clientId - 1) % $colorsCount];
                $data = "{$color}[{$clientId}] {$data}\e[0m";
                echo $data . PHP_EOL;
                foreach ($this->clients as $receiverId => $receiver) {
                    if ($receiverId != $clientId) {
                        fwrite($receiver, $this->formatMessage($receiverId, $data) . PHP_EOL);
                    }
                }
            }
        }
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
        return $data;
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
        return $message;
    }
}
