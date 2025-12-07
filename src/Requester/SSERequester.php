<?php

namespace JoseChan\Stock\Watch\Sdk\Requester;

class SSERequester extends BaseRequester
{
    protected $url = "https://query.sse.com.cn/sseQuery/commonQuery.do";
    protected $method = "GET";
}
