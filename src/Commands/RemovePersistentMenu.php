<?php

namespace BotMan\Drivers\Facebook\Commands;

use BotMan\Drivers\Facebook\Http\Curl;
use Illuminate\Console\Command;

class RemovePersistentMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:facebook:RemoveMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove persistent Facebook menu';

    /**
     * @var Curl
     */
    private $http;

    /**
     * Create a new command instance.
     *
     * @param Curl $http
     */
    public function __construct(Curl $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $payload = ['fields' => ['persistent_menu']];

        $response = $this->http->delete('https://graph.facebook.com/v3.0/me/messenger_profile?access_token='.config('botman.facebook.token'),
            [], $payload);

        $responseObject = json_decode($response->getContent());

        if ($response->getStatusCode() == 200) {
            $this->info('Facebook menu has been removed.');
        } else {
            $this->error('Something went wrong: '.$responseObject->error->message);
        }
    }
}
