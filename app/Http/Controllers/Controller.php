<?php

namespace App\Http\Controllers;

use App\Foundation\Facades\XXH;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function getCorpId()
    {
        return XXH::id();
//        return "1198";
        /*if (Auth::check()) {
            $contact = Contact::where(['user_id' => Auth::id()])->first()['corp_id'];
            return $contact;
        } else {
            return '0';
        }*/
    }
}
