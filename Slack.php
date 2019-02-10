<?php
/**
 * Created by PhpStorm.
 * User: t0wqa
 * Date: 10.02.2019
 * Time: 10:19
 */

require_once __DIR__ . "/vendor/autoload.php";

class Slack
{

    const TOKEN = 'xoxp-546233192576-546364470913-548035990727-6941f20ec90dfdea8cae4becb731fc6b';

    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function inviteUserToChannel($channelId, $userId)
    {
        $result = $this->post('channels.invite', [
           'channel' => $channelId,
           'user' => $userId
        ]);

        print_r($result);
    }

    public function findUserIdByEmail($email)
    {
        $result = $this->get('users.lookupByEmail', [
            'email' => $email
        ]);

        if (!$result['ok']) {
            return false;
        }

        return $result['user']['id'];
    }

    private function get($apiMethod, $queryParams)
    {
        $queryParams['token'] = self::TOKEN;

        $response = $this->client->request(
            'GET',
            "https://slack.com/api/{$apiMethod}",
            [
                'query' => $queryParams
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    private function post($apiMethod, $requestParams)
    {
        $response = $this->client->request(
            'POST',
            "https://slack.com/api/{$apiMethod}",
            [
                'headers' => [
                    'Authorization' => "Bearer " . self::TOKEN
                ],
                'json' => $requestParams
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

}

$slack = new Slack();

$userId = $slack->findUserIdByEmail('anton.khomchenko.dev@gmail.com');

if ($userId) {
    $slack->inviteUserToChannel(123, $userId);
}

