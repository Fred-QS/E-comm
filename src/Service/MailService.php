<?php

namespace App\Service;

use \Mailjet\Resources;
use \Mailjet\Client;

class MailService
{
    private string $api_key;
    private string $api_secret;

    public function __construct()
    {
        $this->api_key = $_ENV['MJ_APIKEY_PUBLIC'];
        $this->api_secret = $_ENV['MJ_APIKEY_PRIVATE'];
    }

    public function send(string $to_email, string $to_name, string $subject, string $content): void
    {
        $mj = new Client($this->api_key, $this->api_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "fred.geffray@gmail.com",
                        'Name' => "Mailjet Pilot"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name,
                        ]
                    ],
                    'TemplateID' => 4093403,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}