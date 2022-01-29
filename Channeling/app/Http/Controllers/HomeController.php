<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth; 
use App\Model\User;

class HomeController extends Controller
{
    public function redirect()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype=='1'){
                return view('user.home');
            }
            else{
                return view('admin.home');
            }

        }
        else {
            return redirect()->back();
        }
    }
    public function index()
    {
       return view('user.home') ;
    }
}
