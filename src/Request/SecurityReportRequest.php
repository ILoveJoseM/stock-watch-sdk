<?php

namespace JoseChan\Stock\Watch\Sdk\Request;

use JoseChan\Stock\Watch\Sdk\Requester\SecurityReportRequester;

class SecurityReportRequest extends ReportRequest
{
    public function __construct(SecurityReportRequester $requester)
    {
        $this->requester = $requester;
    }

    /** @var string 业绩快报
     * "营业总收入-营业总收入",
     * "营业总收入-同比增长",
     * "营业总收入-季度环比增长",
     * "净利润-净利润",
     * "净利润-同比增长",
     * "净利润-季度环比增长",
     */
    const RPT_FCI_PERFORMANCEE = "RPT_FCI_PERFORMANCEE";
}
