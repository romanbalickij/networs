<?php


namespace App\Stateful\Controllers;


use App\Stateful\ConnectionManager;
use App\Stateful\EventBus;
use App\Stateful\EventProtocol;
use BeyondCode\LaravelWebSockets\HttpApi\Controllers\Controller;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;


class EventController extends Controller
{
    private EventBus $bus;

    public function __construct (ChannelManager $channelManager)
    {
        parent::__construct($channelManager);
        $this->bus = EventBus::get();
    }


    public function __invoke (Request $request)
    {
        $content = $request->all(['uid', 'event', 'payload']);

        // Trigger event
        $this->bus->emit(EventProtocol::fromArray($content));

    }

    /**
     * Because library causes error otherwise
     */
    public function ensureValidAppId (?string $appId)
    {
        return $this;
    }

    public function ensureValidSignature (Request $request)
    {
        list('payload' => $payload, 'timestamp' => $timestamp) = $request->all(['payload', 'timestamp']);

        // Validate timestamp
        if ($timestamp - time() > config('stateful-server.broadcast_request_validity')) {
            throw new HttpException(410, '.');
        }

        // Validate header

        if ($request->header('X-Stateful-Auth') !== static::broadcastingAuthHeader($timestamp, $payload)) {
            throw new HttpException(401, 'Invalid auth signature provided.');
        }
    }


    public function onError(ConnectionInterface $connection, Exception $exception)
    {

        $body = [
            'error' => $exception->getMessage(),
        ];

        if (config('app.debug')) {
            $body['trace'] = $exception->getTraceAsString();
        }

        $response = new Response($exception->getStatusCode(), [
            'Content-Type' => 'application/json',
        ], json_encode($body));

        $connection->send(\GuzzleHttp\Psr7\Message::toString($response));

        $connection->close();
    }


    public static function broadcastingAuthHeader(int $timestamp, array $payload): string
    {
        return hash_hmac('sha256', $timestamp . json_encode($payload), env('APP_KEY'));
    }



    public static function trigger(int $uid, string $event, array $payload) {
        $port = env('BROADCASTING_SCHEME') === 'http' ? env('WS_PORT') : env('WSS_PORT');
        $timestamp = time();

        try {
            (new Client([
                'base_uri' => env('HOST_BROADCASTING') . ':' . $port,
                'verify' => false
            ])) -> post('/event', [
                'json' => compact('uid', 'event', 'payload', 'timestamp'),
                'headers' => [
                    'X-Stateful-Auth' => static::broadcastingAuthHeader($timestamp, $payload)
                ]
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
