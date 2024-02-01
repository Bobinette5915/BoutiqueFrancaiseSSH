<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'e1b1d2915ca630aef9476670a5ead9a0';
    private $api_key_secret = '921a8a0ede36a9569f05c65ef19582e0';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj=new Client($this->api_key, $this->api_key_secret,true, ['version'=>'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "chewie.59@hotmail.fr",
                        'Name' => "La Boutique Francaise"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name,
                        ]
                    ],
                    'TemplateID' => 5645939,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content'=>$content,
                        
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();#&& dd($response->getData());
    }
}
