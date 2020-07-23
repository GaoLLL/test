<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 10:16
 */
/*
 * 后台访问控制器类
 * 验证是否登录及权限验证类
 */

namespace Admin\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
		header("Content-type: text/html; charset=utf-8");
        if (!session('admin')) {
            IS_AJAX && ReturnJson(0, 10004);
            $this->error(errorCode(10004), U('Login/index'));
        }
        if (!RbacController::checkAccess(CONTROLLER_NAME, ACTION_NAME)) {
            IS_AJAX && ReturnJson(0, 10003);
            $this->error(errorCode(10003));
        }
        $ActBtns = RbacController::getAction(CONTROLLER_NAME, ACTION_NAME);
        if ($ActBtns !== false) {

            $this->assign('ActBtns', $ActBtns);
        }
    }

    public function clearchache()
    {
        unset($_SESSION['admin']['Access']);
        RbacController::getLoginAccess();
        ReturnJson(1, 10005);
    }

    public function set_admin_log($str=''){
        $logdata = array(
            'admin_id'=>$_SESSION['admin']['id'],
            'admin_name'=>$_SESSION['admin']['username'],
            'time'=>time(),
            'login_ip'=>get_client_ip() ,
            'details'=>$str
        );
        D('admin_log')->add($logdata);
    }

	/**
	 * 导出excel
	 * $filename导出的文件名
	 * $data是多维数组，array("sheet1"=>array(array(array("列名")),array("小明","18")))
	 */
	public function downExcel($filename,$data){
		import("Org.Util.PHPExcel");
		$phpexcel = new \PHPExcel();
		$sheet = array_keys($data);

		foreach ($sheet as $k=>$v){
			if($k > 0){
				$phpexcel->createSheet();
			}
			$phpexcel->setActiveSheetIndex($k);
			$phpexcel->getActiveSheet()->setTitle($v);

			//插入数据
			$data[$v] = array_values($data[$v]);
			$row = 1;
			foreach ($data[$v] as $kk=>$vv){
				$vv = array_values($vv);
				foreach ($vv as $key=>$value){
					$newkey = $this->change($key);
					if(is_numeric($value)){
						$value .= " ";
					}

					$phpexcel->getActiveSheet()->setCellValue($newkey.$row,$value);
				}
				$row += 1;
			}
		}

		$this->doDownExcel($phpexcel,$filename);
	}

	/**
	 * 执行下载excel部分
	 */
	public function doDownExcel($phpexcel,$filename){
		//下载excel部分
		$objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
		$savefile = $filename.".xls";
		ob_end_clean();
		ob_start();
		header('Content-Type: application/vnd.ms-excel');

		$encoded_filename = urlencode($savefile);
		$encoded_filename = str_replace("+", "%20", $encoded_filename);
		header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');

//		header('Content-Disposition: attachment;filename='.$savefile);
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');die;
	}

	/**
	 * 把数字转换成excel列名A，AB，CA这种的从0开始
	 */
	public function change($num){
		$num += 1;

		$letter = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		if($num/26 > 1){
			$one = floor($num/26);
			$two = $num%26;
			if($two == 0){
				$one -= 1;
				$two = 26;
			}
			$result = $letter[$one-1].$letter[$two-1];
		}else{
			$result = $letter[$num-1];
		}
		return $result;
	}

	/**
	 * 检查$_POST是否存在参数$field;存在则返回，不存在则返回空
	 */
	public function checkPost($field){
		if(isset($_POST[$field]) && $_POST[$field] != ""){
			return $_POST[$field];
		}else{
			return "";
		}
	}

	/**
	 * 检查$_GET是否存在参数$field;存在则返回，不存在则返回空
	 */
	public function checkGet($field){
		if(isset($_GET[$field]) && $_GET[$field] != ""){
			return $_GET[$field];
		}else{
			return "";
		}
	}

	/**
	 * 根据数组和键值返回结果
	 */
	public function getValueByKey($arr,$key,$type=1){
		if(isset($arr[$key])){
			return $arr[$key];
		}else{
			if($type == 1){
				return "";
			}else{
				return array();
			}
		}
	}

    /**
     * @param $table 数据表名
     * @param $where 条件
     * @return mixed 返回单条信息
     */
    public function _find($table,$where){
        $info = M($table)->where($where)->find();
        return $info;
    }

    /**
     * @param $table1 数据表一
     * @param $where 条件
     * @param string $filed 要查的数据字段
     * @param string $table2 数据表二
     * @return mixed 返回要查的数组
     */
    public function _list($table1,$where='1',$filed='*',$order='id',$table2='',$pagesizes='15'){

        $count = D($table1)->where($where)->count();
        if(floor($_REQUEST['pagesize'])==$_REQUEST['pagesize'] && $_REQUEST['pagesize'] > 0){
            $pagesize = $_REQUEST['pagesize'];
        }else{
            $pagesize = $pagesizes;
        }
        $Page = new \Think\Page($count,$pagesize);
        $show = $Page->show();

        if($table2==''){
            $list = M($table1)->where($where)->field($filed)->limit($Page->firstRow.','.$Page->listRows)->order($order)->select();
            //echo     M($table1)->getLastSql();
        }else {
            $list = M($table1.' a')
                ->join($table2.' b')
                ->where($where)
                ->select();
        }
        $this->assign('pagesize',$pagesize);
        $this->assign('page',$show);
        $this->assign('count',$count);
        return $list;
    }


    /**
     * @param $table 数据表名
     * @param $data 要添加的数据 一维数组类型,例:array('name'=>'测试');
     */
    public function _add($table,$data){

        $res = M($table)->add($data);
        if($res){
            $rebak['status'] = 1;
            $rebak['msg'] = '添加成功';
        }else{
            $rebak['status'] = 0;
            $rebak['msg'] = '添加失败';
        }

        echo  json_encode($rebak);

    }

    /**
     * @param $table 数据表名
     * @param $data  要修改的数据 一维数组类型,例:array('name'=>'测试');
     * @param $where 条件
     */
    public function _save($table,$data,$where){
        $res = M($table)->where($where)->save($data);
        if($res){
            $rebak['status'] = 1;
            $rebak['msg'] = '修改成功';
        }else{
            $rebak['status'] = 0;
            $rebak['msg'] = '修改失败';
        }

        echo json_encode($rebak);
    }

    /**
     * @param $table 数据表名
     * @param $id 要删除的数据id
     */
    public function _del($table,$id){
        $res = M($table)->where("id='$id'")->delete();
        if($res){
            $rebak['status'] = 1;
            $rebak['msg'] = '删除成功';
        }else{
            $rebak['status'] = 0;
            $rebak['msg'] = '删除失败';
        }

        echo json_encode($rebak);
    }
    //上传图片
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','doc','docx');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $bak['msg']  = $upload->getError();
            $bak['code'] = 0;
        }else{// 上传成功

            $bak['msg']  = '上传成功';
            $bak['code'] = 1;
            $bak['data'] =  '/Uploads/'.$info['imgFile']['savepath'].$info['imgFile']['savename'];
        }
        echo json_encode($bak);
    }
}