<?php
/**
 * @package     Vikonapi
 * @author      nfpi
 * @link        https://db-nica.ru/
 * @copyright   Copyright(c) 2019
 * @version     1.0.0
 **/

namespace Nfpi\Vikonapi;

use Curl\Curl;

class Request
{
    private $_domenApi = 'https://db-nica.ru/';
    private $_apiKey;
    private $_version = '1';

    public function __construct($apiKey = '')
    {
        $this->_apiKey = $apiKey;
    }

    public function get($method, $item = '')
    {
        $curl = new Curl;
        $curl->setHeader('Authorization', 'Bearer ' . $this->_apiKey);
        $curl->get($this->_domenApi . 'api/v' . $this->_version . '/' . $method . '/' . $item);
        if ($curl->error) {
            return array(
                'success' => false,
                'error' => $curl->errorCode . ': ' . $curl->errorMessage,
                'description' => isset($curl->response->error) ? $curl->response->error : '',
            );
        } else {
            return $curl->response;
        }
    }

    public function put($method, $parameters)
    {
        $curl = new Curl();
        $curl->setHeader('Authorization', 'Bearer ' . $this->_apiKey);
        $curl->put($this->_domenApi . 'api/v' . $this->_version . '/' . $method, $parameters);
        if ($curl->error) {
            return array(
                'success' => false,
                'error' => $curl->errorCode . ': ' . $curl->errorMessage,
                'description' => isset($curl->response->error) ? $curl->response->error : '',
            );
        } else {
            return $curl->response;
        }
    }

    public function post($method, $item, $parameters)
    {
        $curl = new Curl();
        $curl->setHeader('Authorization', 'Bearer ' . $this->_apiKey);
        $curl->post($this->_domenApi . 'api/v' . $this->_version . '/' . $method . '/' . $item, $parameters);
        if ($curl->error) {
            return array(
                'success' => false,
                'error' => $curl->errorCode . ': ' . $curl->errorMessage,
                'description' => isset($curl->response->error) ? $curl->response->error : '',
            );
        } else {
            return $curl->response;
        }
    }

    public function delete($method, $item)
    {
        $curl = new Curl();
        $curl->setHeader('Authorization', 'Bearer ' . $this->_apiKey);
        $curl->delete($this->_domenApi . 'api/v' . $this->_version . '/' . $method . '/' . $item);
        if ($curl->error) {
            return array(
                'success' => false,
                'error' => $curl->errorCode . ': ' . $curl->errorMessage,
                'description' => isset($curl->response->error) ? $curl->response->error : '',
            );
        } else {
            return $curl->response;
        }
    }
}