<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\ReportVerification;

class ReportController extends Controller
{
    private $result;
    private $reportverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->reportverification = new ReportVerification();
    }

    /**
     * 投诉
     * @param Request $request
     */
    public function report (Request $request)
    {
        return $this->reportverification->report($request);
    }

    /**
     * 群投诉
     * @param Request $request
     * @return mixed
     */
    public function reportGroup (Request $request)
    {
        return $this->reportverification->reportGroup($request);
    }
}
