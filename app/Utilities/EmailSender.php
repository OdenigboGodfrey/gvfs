<?php

namespace App\Utilities;

use Mailgun\Mailgun;
use Bogardo\Mailgun\Facades\Mailgun as Bogardo;

class EmailSender
{
    /**
     * @var Mailgun
     */
    private $mailgun;

    /**
     * @var array
     */
    private static $config = [
        'key' => '0608ca4fa4c7d50eff99622d31553b8e-87cdd773-f05e3d58',
        'domain' => 'sandboxb7df03c11b91439a9c6e8c0988cf1dc5.mailgun.org',
        'api' => 'https://api.eu.mailgun.net'
    ];

    /**
     * EmailSender constructor.
     */
    public function __construct()
    {
        $this->mailgun = new Mailgun();
    }

    /**
     * @param $mailbag
     * @return \Mailgun\Model\Message\SendResponse
     */
    public function mailgun($mailbag)
    {
        $message = $this->mailgun->create(static::$config['key'], static::$config['api']);

        $response = $message->messages()->send(static::$config['domain'], [
            'from'    => $mailbag['from'],
            'to'      => $mailbag['to'],
            'subject' => $mailbag['subject'],
            'text'    => $mailbag['message']
        ]);

        return $response;
    }

    /**
     * @param $mailbag
     * @return mixed
     */
    public function bogardo($mailbag)
    {
        $response = Bogardo::send($mailbag['view'], $mailbag, function ($message) use ($mailbag) {
            $message
                ->subject($mailbag['subject'])
                ->to($mailbag['to'], config('app.name'))
                ->from($mailbag['from'], $mailbag['name'])
                ->trackClicks(true)
                ->trackOpens(true);
        });

        return $response;
    }

}