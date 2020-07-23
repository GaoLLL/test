<?php

namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _redirect(){
        redirect('/home/index/index');
    }
}