<?php

namespace JoseChan\Stock\Watch\Sdk\Requester;

class BaiduNewsReportRequester extends BaseRequester
{
    protected $url = 'https://finance.pae.baidu.com/sapi/v1/financecalendar';
    protected $method = 'GET';
}
