<?php
namespace BotMan\Drivers\Facebook\Http;

use BotMan\BotMan\Http\Curl as BotmanCurl;
use Symfony\Component\HttpFoundation\Response;

class Curl extends BotmanCurl
{
    /**
     * Send a delete request to a URL.
     *
     * @param  string $url
     * @param  array $urlParameters
     * @param  array $postParameters
     * @param  array $headers
     * @param  bool $asJSON
     * @return Response
     */
    public function delete(
        $url,
        array $urlParameters = [],
        array $postParameters = [],
        array $headers = [],
        $asJSON = false
    ) {
        $request = $this->prepareRequest($url, $urlParameters, $headers);

        curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if ($asJSON === true) {
            curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($postParameters));
        } else {
            curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($postParameters));
        }

        return $this->executeRequest($request);
    }
}
