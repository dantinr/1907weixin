<?php

namespace App\Http\Controllers\Git;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        if(isset($_GET['key']))
        {
            if($_GET['key'] === '1907weixin'){
                $cmd = "cd /www/1907/weixin && git pull";
                shell_exec($cmd);   
            }
        }else{
            die("405");
        }
    }
}
