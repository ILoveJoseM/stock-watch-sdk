<?php

namespace JoseChan\Stock\Watch\Sdk\Request;

use JoseChan\Stock\Watch\Sdk\Requester\SSERequester;
use Illuminate\Contracts\Support\Arrayable;

class SSERequest implements Arrayable
{
    /** @var int A股 */
    const STOCK_A = 1;
    /** @var int B股 */
    const STOCK_B = 2;
    /** @var int 科创板 */
    const STOCK_SCI = 8;

    /** @var int 板块 */
    protected $stockType;

    /** @var SSERequester $requester 请求 */
    protected $requester;

    public function __construct(SSERequester $requester)
    {
        $this->requester = $requester;
    }

    /**
     * @param mixed $stockType
     */
    public function setStockType($stockType)
    {
        $this->stockType = $stockType;
        return $this;
    }

    public function request()
    {
        $headers = [
            "Host" => "query.sse.com.cn",
            "Pragma" => "no-cache",
            "Referer" => "https://www.sse.com.cn/assortment/stock/list/share/",
            "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36",
        ];

        return $this->requester->query($this->toArray(), ["headers" => $headers]);
    }

    public function toArray()
    {
        return [
            "STOCK_TYPE" => $this->stockType,
            "REG_PROVINCE" => "",
            "CSRC_CODE" => "",
            "STOCK_CODE" => "",
            "sqlId" => "COMMON_SSE_CP_GPJCTPZ_GPLB_GP_L",
            "COMPANY_STATUS" => "2,4,5,7,8",
            "type" => "inParams",
            "isPagination" => "true",
            "pageHelp.cacheSize" => "1",
            "pageHelp.beginPage" => "1",
            "pageHelp.pageSize" => "10000",
            "pageHelp.pageNo" => "1",
            "pageHelp.endPage" => "1",
        ];
    }

}
