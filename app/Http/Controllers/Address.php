<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Session;

use App\Models\Address as AddressM;



class Address extends Controller
{

    public function lists()
    {
        $lists = AddressM::get(['id','province','city']);
        return response()->json(['success'=>'Y','msg'=>'','data'=>$lists]);
    }
    
    
}