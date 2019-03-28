<?php

namespace BotMan\Drivers\Facebook\Commands;

use BotMan\BotMan\Http\Curl;
use Illuminate\Console\Command;

class GenerateMessengerCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:facebook:GenerateMessengerCode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Messenger Code';

    /**
     * @var Curl
    */
    private $http;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Curl $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $payload = config('botman.facebook.messenger_code');

        if (! $payload) {
            $this->error('Pls add payload.');
            exit;
        }

        $response = $this->http->post(
            'https://graph.facebook.com/v2.6/me/messenger_codes?access_token='.config('botman.facebook.token'),
            [], $payload);

        $responseObject = json_decode($response->getContent());

        if ($response->getStatusCode() == 200) {
             $this->info('This is your code url');
             $this->info($responseObject->uri);
        } else {
            $this->error('Something went wrong: '.$responseObject->error->message);
        }
    }
}
