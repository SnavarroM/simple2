<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Log;

class ReCaptcha3Helper {
    
    /**
     * Validate the token reCaptcha3.
     *
     * @param  String $response_token

     * @return Array $reCaptchaResult
     * $reCaptchaResult['success']; // true or false
        $reCaptchaResult['chalenge_ts']; // date y-m-d h:i:s
        $reCaptchaResult['hostname']; // host definde on captcha config
        $reCaptchaResult['score']; // 0...1, 0=robot y 1=Human
        $reCaptchaResult['action']; // login, homepage, social, etc (events recaptcha3)
     */
    function recaptchaVerify($response_token){

        $reCaptchaResult = [];

        try {
            $client = new Client([
                'base_uri' => 'https://www.google.com/recaptcha/api/',
                'timeout'  => 10,
            ]);

            $response = $client->request('POST', 'siteverify', [
                'form_params' => [
                    'secret' => env('RECAPTCHA3_SECRET_KEY'),
                    'response' => $response_token
                ]
            ]);

            $result = $response->getBody();
            $reCaptchaResult = json_decode($result->getContents(), true);

        } catch (RequestException $e) {
            Log::info('[RECAPTCHA3] Ha ocurrido en error al itentar comunicarse con google y validar el recaptcha.', []);
            $reCaptchaResult = [
                'success' => false,
                'score' => 0
            ];
        }

        return $reCaptchaResult;
    }
}