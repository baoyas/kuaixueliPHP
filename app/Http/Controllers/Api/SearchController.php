<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\SearchVerification;

class SearchController extends Controller
{
    private $result;
    private $searchverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->searchverification = new SearchVerification();
    }
    /**
     * 搜索 - 我要买
     * @param Request $request
     */
    public function searchBusiness (Request $request)
    {
        return $this->searchverification->searchBusiness($request);
    }

    /**
     * 搜索  - 我要卖
     * @param Request $request
     */
    public function searchBusinessSell (Request $request)
    {
        return $this->searchverification->searchBusinessSell($request);
    }

    /**
     * 搜索 - 好友
     * @param Request $request
     */
    public function searchPeople (Request $request)
    {
        return $this->searchverification->searchPeople($request);
    }
}
