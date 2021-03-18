<?php

namespace CViniciusSDias\AnaliseSentimento\Twitter;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

class TwitterApi
{
    private const API_URL = 'https://api.twitter.com/2';

    public function __construct(private ClientInterface $httpClient, private string $token)
    {
    }

    /**
     * @param string $query
     * @param int $maxResults
     * @return Tweet[]
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function searchRecentTweets(string $query, int $maxResults = 100): array
    {
        $url = self::API_URL . '/tweets/search/recent?query=' . $query . '&max_results=' . $maxResults;
        $request = new Request('GET', $url, ['Authorization' => 'Bearer ' . $this->token]);
        $response = $this->httpClient->sendRequest($request);
        /** @var array{data: array{array{text: string}}} $jsonResponse */
        $jsonResponse = json_decode($response->getBody()->getContents(), true);

        return array_map(fn (array $tweet) => new Tweet($tweet['text']), $jsonResponse['data']);
    }
}
