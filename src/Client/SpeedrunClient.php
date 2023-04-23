<?php


namespace SpeedrunApi\Client;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class SpeedrunClient
{
    private const REQUEST_GET = 'GET';
    private const REQUEST_POST = 'POST';

    public function __construct(
        private ClientInterface $client,
        private Config $config
    ) {}

    public function sendRequest(
        string $method,
        string $uri,
        array $requestParams = []
    ): array {
        $response = $this->client->request(
            $method,
            $uri,
            [
                RequestOptions::QUERY => $requestParams
            ]
        );

        return $this->getResponse($response);
    }

    public function getUser(
        string $userName,
        array $query
    ): array {
        return $this->sendRequest(
            self::REQUEST_GET,
            'users',
            array_merge(['lookup' => $userName], $query)
        );
    }

    public function getPersonalBestForUser(
        string $user
    ): array {
        return $this->sendRequest(
            self::REQUEST_GET,
            sprintf('users/%s/personal-bests', $user)
        );
    }

    public function getLeaderBoards(
        string $game,
        string $category,
        array $query
    ): array {
        $requestUri = sprintf(
            'leaderboards/%s/category/%s',
            $game,
            $category
        );

        return $this->sendRequest(
            self::REQUEST_GET,
            $requestUri,
            $query
        );
    }

    public function getGame(
        string $name,
        array $query
    ): array {
        return $this->sendRequest(
            self::REQUEST_GET,
            'games',
            array_merge(['name' => $name], $query)
        );
    }

    public function getWorldRecord(
        string $game,
        string $category
    ): array {
        $responseData = $this->getLeaderBoards($game, $category, []);

        return Collection::make($responseData)->all();
    }

    private function getResponse(ResponseInterface $response): array
    {
        return json_decode(
            (string)$response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}