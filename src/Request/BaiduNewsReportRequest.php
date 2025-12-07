<?php

namespace JoseChan\Stock\Watch\Sdk\Request;

use JoseChan\Stock\Watch\Sdk\Requester\BaiduNewsReportRequester;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class BaiduNewsReportRequest implements Arrayable
{
    private $startDate;
    private $endDate;
    private $pageSize = 20;
    private $pageNumber = 1;
    private $category = "report_time";
    private $market = "all";
    private $requester;

    /** @var string 所有市场 */
    const MARKET_ALL = "all";
    /** @var string A股市场 */
    const MARKET_A = "ab";
    /** @var string 港股市场 */
    const MARKET_HK = "hk";
    /** @var string 美股市场 */
    const MARKET_US = "us";

    public function __construct(BaiduNewsReportRequester $requester)
    {
        $this->requester = $requester;
    }

    public function searchDate(Carbon $carbon)
    {
        $this->startDate = $carbon->format('Y-m-d');
        $this->endDate = $carbon->format('Y-m-d');
        return $this;
    }

    public function pagination($pageSize = 20, $pageNumber = 1)
    {
        $this->pageSize = $pageSize;
        $this->pageNumber = $pageNumber;
        return $this;
    }

    public function market($market)
    {
        $this->market = $market;
        return $this;
    }

    public function toArray()
    {
        return [
            "start_date" => $this->startDate,
            "end_date" => $this->endDate,
            "rn" => $this->pageSize,
            "pn" => $this->pageNumber - 1,
            "cate" => $this->category,
            "market" => $this->market,
        ];
    }

    public function request()
    {
        return $this->requester->query($this->toArray());
    }
}
