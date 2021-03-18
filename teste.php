<?php

use CViniciusSDias\AnaliseSentimento\Chart\PieChartBuilder;
use CViniciusSDias\AnaliseSentimento\Twitter\TwitterApi;
use Ds\Map;
use GuzzleHttp\Client;
use Sentiment\Analyzer;

require_once 'vendor/autoload.php';

$twitterApi = new TwitterApi(new Client(), 'token');
$query = [
    '"Falcon and the winter soldier"',
    '-is:verified',
    '-is:retweet',
    'lang:en',
];
$recentTweets = $twitterApi->searchRecentTweets(implode(' ', $query));

$sentiments = new Map();
$sentiments->put('neg', 0);
$sentiments->put('neu', 0);
$sentiments->put('pos', 0);

$sentimentAnalyzer = new Analyzer();
foreach ($recentTweets as $tweet) {
    /** @var array{"neg": float, "neu": float, "pos": float, "compound": float} $sentiment */
    $sentiment = $sentimentAnalyzer->getSentiment((string) $tweet);

    if ($sentiment['compound'] > 0) {
        $key = 'pos';
    } elseif ($sentiment['compound'] < 0) {
        $key = 'neg';
    } else {
        $key = 'neu';
    }
    $sentiments[$key]++;
}

PieChartBuilder::createWithSize(500, 500)
    ->withTitle('Sentimento sobre o seriado')
    ->withSubTitle('The Falcon and the Winter Soldier')
    ->withValues($sentiments->values()->toArray())
    ->withLegends(['Negativo' => 'red', 'Neutro' => 'blue', 'Positivo' => 'green'])
    ->drawToImageFile(__DIR__ . '/sentimentos.png');
