<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;

use Illuminate\Support\Facades\DB;
use Validator;

use Illuminate\Http\Request;
use App\Models\SalaryVouchers;

//use Auth;


class TestController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    function getSalaryVoucher(){
        $data = SalaryVouchers::get();
        if($data){
            return $this->sendResponse($data, 'salary vouchers list');
        }else{
            return $this->sendError('data not found', []);       
        }
    }
    
}