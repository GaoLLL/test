<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 11:48
 */

namespace Admin\Controller;

class IndexController extends RbacController
{
    public function index()
    {
        $meun = MenuformatTree($_SESSION['admin']['Access'], 0);
        $this->assign('menu', $meun);
        $this->display();
    }
}