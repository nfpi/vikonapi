<?php
/**
 * @package     Vikonapi
 * @author      nfpi
 * @link        https://db-nica.ru/
 * @copyright   Copyright(c) 2019
 * @version     1.0.0
 **/

namespace Nfpi\Vikonapi;

class Request
{
    private $_domenApi = 'http://vii/';
    private $_apiKey;
    private $_version = '1';
    private $_curl;

    public function __construct($config = [])
    {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL library is not loaded');
        }
        $this->_curl = curl_init();
        if (isset($config['apiKey'])) {
            $this->_apiKey = $config['apiKey'];
        }
    }

    public function get($method, $item = '')
    {
        $url = $this->_domenApi . 'api/v' . $this->_version . '/' . $method . '/' . $item;
        $this->_setOpt('GET', $url);
        return $this->_exec();
    }

    public function put($method, $parameters)
    {
        $url = $this->_domenApi . 'api/v' . $this->_version . '/' . $method;
        $this->_setOpt('PUT', $url, $parameters);
        return $this->_exec();
    }

    public function post($method, $item, $parameters)
    {
        $url = $this->_domenApi . 'api/v' . $this->_version . '/' . $method . '/' . $item;
        $this->_setOpt('POST', $url, $parameters);
        return $this->_exec();
    }

    public function delete($method, $item)
    {
        $url = $this->_domenApi . 'api/v' . $this->_version . '/' . $method . '/' . $item;
        $this->_setOpt('DELETE', $url);
        return $this->_exec();
    }

    private function _setOpt($method, $url, $postParameters = [])
    {
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        $curlVersion = curl_version();
        $userAgent = 'nfpi/vikonapi/' . $this->_version . ' curl/' . $curlVersion['version'];
        curl_setopt($this->_curl, CURLOPT_USERAGENT, $userAgent);
        $headers = array(
            'Authorization: Bearer ' . $this->_apiKey,
        );
        switch ($method) {
            case 'GET':
                break;
            case 'PUT':
                curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                $postData = http_build_query($postParameters);
                curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $postData);
                $headers[] = 'Content-Length: ' . strlen($postData);
                break;
            case 'POST':
                curl_setopt($this->_curl, CURLOPT_POST , true);
                curl_setopt($this->_curl, CURLOPT_POSTFIELDS , http_build_query($postParameters));
                break;
            case 'DELETE':
                curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $headers);
    }

    private function _exec()
    {
        $response = curl_exec($this->_curl);
        $info = curl_getinfo($this->_curl);
        if ($errorCode = curl_errno($this->_curl)) {
            $response = array(
                'success' => false,
                'error' => $errorCode . ': ' . curl_error($this->_curl),
            );
        } else {
            if ($info['http_code'] >= 400) {
                $responseDecode = json_decode($response);
                $response = array(
                    'success' => false,
                    'error' => isset($responseDecode->error) ? $responseDecode->error : $info['http_code'],
                    'description' => isset($responseDecode->error_description)
                        ? $responseDecode->error_description
                        : 'Запрос вернул код ошибки: ' . $info['http_code'],
                );
            } else {
                return json_decode($response);
            }

        }
        curl_close($this->_curl);
        return $response;
    }
}