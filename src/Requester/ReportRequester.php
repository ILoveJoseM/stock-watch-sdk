<?php

namespace JoseChan\Stock\Watch\Sdk\Requester;

class ReportRequester extends BaseRequester
{
    protected $url = 'https://datacenter-web.eastmoney.com/api/data/v1/get';
    protected $method = 'GET';
}
