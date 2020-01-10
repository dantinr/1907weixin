<?php

namespace App\Http\Controllers\Git;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        $cmd = "cd /www/1907/weixin && git pull";
        shell_exec($cmd);
    }
}
