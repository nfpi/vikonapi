<?php
/**
 * @package     Vikonapi
 * @author      nfpi
 * @link        https://db-nica.ru/
 * @copyright   Copyright(c) 2019
 * @version     0.1.0
 **/

namespace Nfpi\Vikonapi;

use Curl\Curl;

class Request
{
    public function __construct()
    {
    }

    public function get()
    {
        $curl = new Curl;
        $curl->get('https://db-nica.ru/');
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            echo 'Response:' . "\n";
            var_dump($curl->response);
        }
    }
}