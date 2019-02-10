<?php
/**
 * THIS IS ALMOST WORKING SCRIPT
 * IN ORDER FOR THIS TO WORK COMPLETELY WRITE ME ON UPWORK
 * AND I WILL LET YOU KNOW HOW TO USE IT
 */

require_once __DIR__ . "/vendor/autoload.php";

class Slack
{

    const TOKEN = 'YOUR_TOKEN';

    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @param $channelId
     * @param $userId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function inviteUserToChannel($channelId, $userId)
    {
        $result = $this->post('channels.invite', [
           'channel' => $channelId,
           'user' => $userId
        ]);

        return $result;
    }

    /**
     * @param $email
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

    /**
     * @param $apiMethod
     * @param $queryParams
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

    /**
     * @param $apiMethod
     * @param $requestParams
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

$userId = $slack->findUserIdByEmail('email');

if ($userId) {
    $slack->inviteUserToChannel(123, $userId);
}

