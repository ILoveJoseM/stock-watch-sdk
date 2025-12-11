<?php

namespace JoseChan\Stock\Watch\Sdk\Request;

use JoseChan\Stock\Watch\Sdk\Requester\ReportRequester;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\ResponseInterface;

class ReportRequest implements Arrayable
{
    /** @var string $sortColumns 排序字段，多个用逗号分隔 */
    protected $sortColumns;
    /** @var string $sortTypes 排序规则，-1倒序 1顺序，多个逗号分隔，需要和sortColumns对齐 */
    protected $sortTypes;
    /** @var string $pageSize 分页大小 */
    protected $pageSize = "500";
    /** @var string $pageNumber 页数 */
    protected $pageNumber = "1";
    /** @var string $reportName 报告名称 RPT_DMSK_FN_CASHFLOW-现金流表 RPT_DMSK_FN_BALANCE-资产负债表 RPT_DMSK_FN_INCOME-利润表 */
    protected $reportName;
    /** @var string $columns 查询字段 ALL-全部，更多的查出来自己看 */
    protected $columns = "ALL";
    /** @var string $filter 过滤条件 (字段 in ("值","值")) (字段=值) (字段!=值)，多个直接拼接 */
    protected $filter;

    protected $wheres = [];

    protected $requester;
    public function __construct(ReportRequester $requester)
    {
        $this->requester = $requester;
    }

    /** @var string 现金流表 */
    const CASHFLOW = 'RPT_DMSK_FN_CASHFLOW';
    /** @var string 资产负债表 */
    const BALANCE = 'RPT_DMSK_FN_BALANCE';
    /** @var string 利润表 */
    const INCOME = 'RPT_DMSK_FN_INCOME';
    /** @var string 业绩报表 */
    const PERFORMANCE = 'RPT_FCI_PERFORMANCEE';
    /** @var string 商誉、净资产 */
    const RPT_GOODWILL_STOCKDETAILS = 'RPT_GOODWILL_STOCKDETAILS';
    /** @var string PE/PB/PEG */
    const RPT_VALUEANALYSIS_DET = 'RPT_VALUEANALYSIS_DET';

    const RPT_LICO_FN_CPD = 'RPT_LICO_FN_CPD';

    /** @var string 一季度报 */
    const REPORT_SEASON_ONE = "03-31";
    /** @var string 半年报 */
    const REPORT_SEASON_MIDDLE = "06-30";
    /** @var string 三季度报 */
    const REPORT_SEASON_THREE = "09-30";
    /** @var string 年报 */
    const REPORT_SEASON_YEAR = "12-31";

    /**
     * 按指定字段升序排序
     * @param string $columns 多个字段用逗号分隔
     */
    public function orderBy($columns)
    {
        $this->sortColumns = $columns;
        $this->sortTypes = str_repeat('1,', substr_count($columns, ',') ) . '1';
        return $this;
    }

    /**
     * 按指定字段降序排序
     * @param string $columns 多个字段用逗号分隔
     */
    public function orderByDesc($columns)
    {
        $this->sortColumns = $columns;
        $this->sortTypes = str_repeat('-1,', substr_count($columns, ',') ) . '-1';
        return $this;
    }

    /**
     * 设置分页大小
     * @param string $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * 设置页数
     * @param string $pageNumber
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * 设置报告名称
     * @param string $reportName
     * @return ReportRequest
     */
    public function setReportName(string $reportName)
    {
        $this->reportName = $reportName;
        return $this;
    }

    /**
     * 设置查询字段
     * @param string $columns
     * @return ReportRequest
     */
    public function setColumns(string $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * 添加 in 过滤条件
     * @param string $column
     * @param array $values
     */
    public function whereIn($column, array $values)
    {
        $vals = array_map(function ($v) {
            return "\"{$v}\"";
        }, $values);
        $this->wheres[] = "({$column} in (" . implode(',', $vals) . "))";
        $this->filter = implode('', $this->wheres);
        return $this;
    }

    /**
     * 添加等于过滤条件
     * @param string $column
     * @param string $value
     */
    public function where($column, $value)
    {
        $this->wheres[] = "({$column}=\"{$value}\")";
        $this->filter = implode('', $this->wheres);
        return $this;
    }

    /**
     * 添加不等于过滤条件
     * @param string $column
     * @param string $value
     */
    public function whereNot($column, $value)
    {
        $this->wheres[] = "({$column}!=\"{$value}\")";
        $this->filter = implode('', $this->wheres);
        return $this;
    }

    /**
     * 添加报告日期过滤条件
     * @param int $year 年份
     * @param string $season 季度 "03-31","06-30","09-30","12-31"
     */
    public function reportDate($year, $season)
    {
        $this->wheres[] = "(REPORT_DATE='{$year}-{$season}')";
        $this->filter = implode('', $this->wheres);
        return $this;
    }

    public function buildSeason($year, $season)
    {
        return "{$year}-{$season}";
    }
    public function whereDate($column, $date)
    {
        $this->wheres[] = "({$column}='{$date}')";
        $this->filter = implode('', $this->wheres);
        return $this;
    }

    /**
     * 转换为数组
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'sortColumns' => $this->sortColumns,
            'sortTypes' => $this->sortTypes,
            'pageSize' => $this->pageSize,
            'pageNumber' => $this->pageNumber,
            'reportName' => $this->reportName,
            'columns' => $this->columns,
            'filter' => $this->filter,
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function request(): ResponseInterface
    {
        return $this->requester->query($this->toArray());
    }

}
