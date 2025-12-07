<?php

namespace JoseChan\Stock\Watch\Sdk\Requester;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Support\Arrayable;

class BaseRequester
{
    private $client;
    private $uri;
    protected $url;
    protected $method;

    public function __construct()
    {
        $this->client = new Client();
        $this->uri = new Uri($this->url);
    }

    /**
     * 发送请求
     * @param array|Arrayable $params 请求参数
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function query($params, $options = [])
    {
        if(!is_array($params) && $params instanceof Arrayable){
            $params = $params->toArray();
        }

        if(strtoupper($this->method) == 'GET') {
            $this->uri = $this->uri->withQuery(http_build_query($params));
            return $this->client->request($this->method, $this->uri, [
                'headers' => $options['headers'] ?? []
            ]);
        }

        return $this->client->request($this->method, $this->uri, [
            'headers' => $options['headers'] ?? [],
            'json' => $params
        ]);

    }
}
