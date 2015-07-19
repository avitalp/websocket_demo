<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MainController extends Controller implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function index()
    {
        return view('greeting', ['name' => "Pavan", 'delay' => 2]);
    }

    public function onOpen(ConnectionInterface $conn)
    {
       // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
	$numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
       echo "An error has occurred: {$e->getMessage()}\n";

       $conn->close();
    }

    public function updateRandom()
    {
	$response = new StreamedResponse(function()
	{
		$x = mt_rand(1, 100);
		echo "data: $x\n\n";
		ob_flush();
		flush();
		sleep(2);
	});

        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;
    }
}
