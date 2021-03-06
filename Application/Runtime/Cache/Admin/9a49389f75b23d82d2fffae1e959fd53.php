<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
  <meta charset="utf-8">
  <title>layui</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/Public/framework/layui/css/layui.css"  media="all">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>组织架构</legend>
</fieldset>
<div style="float:left;width:250px">
<div class="layui-btn-container">
  
   <!--<button type="button" class="layui-btn layui-btn-sm" lay-demo="setChecked">勾选指定节点</button>-->
  <button type="button" class="layui-btn layui-btn-sm" lay-demo="reload">刷新组织架构</button>
  <button type="button" class="layui-btn layui-btn-sm" lay-demo="getChecked">确认选择</button>
</div>
<div id="test12" class="demo-tree-more"></div>
</div>
<div style="float:left;width:500px;margin-left:50px">
<form class="layui-form layui-form-pane" action="/admin/mes/add" method="post">

  <div class="layui-form-item">
    <label class="layui-form-label">消息类型</label>
    <div class="layui-input-block">
      <select name="type" lay-filter="aihao" id="type">
        <option value="0">文字</option>
        <option value="1">图片</option>
        <option value="2">链接</option>
      </select>
    </div>
  </div>

  <div class="layui-form-item layui-form-text" id="type0">
    <label class="layui-form-label">消息内容</label>
    <div class="layui-input-block">
      <textarea id="txt" placeholder="请输入内容" class="layui-textarea" name="txt" lay-verify="txt"></textarea>
    </div>
  </div>
  <div class="layui-form-item layui-form-text" id="type1" style="display:none">
    <label class="layui-form-label">上传图片</label>
    <div class="layui-input-block">
      <input type="file" id="image" name="image" lay-verify="image"/>
    </div>
  </div>
  <div id="type2" style="display:none">
	  <div class="layui-form-item layui-form-text">
		<label class="layui-form-label">链接地址</label>
		<div class="layui-input-block">
		  <input type="text" name="url" id="url" placeholder="请输入链接地址" class="layui-input" lay-verify="url">
		</div>
	  </div>
	  <div class="layui-form-item layui-form-text">
		<label class="layui-form-label">图片地址</label>
		<div class="layui-input-block">
		  <input type="text" name="urlimg" id="urlimg" placeholder="请输入图片链接地址" class="layui-input" lay-verify="url">
		</div>
	  </div>
	  <div class="layui-form-item layui-form-text">
		<label class="layui-form-label">消息标题</label>
		<div class="layui-input-block">
		  <input type="text" name="urltitle" id="urltitle" placeholder="请输入消息标题" class="layui-input" lay-verify="txt">
		</div>
	  </div>
	  <div class="layui-form-item layui-form-text">
		<label class="layui-form-label">消息描述</label>
		<div class="layui-input-block">
		  <input type="text" name="urltext" id="urltext" placeholder="请输入消息描述" class="layui-input" lay-verify="txt">
		</div>
	  </div>
  </div>
  
  <input type="hidden" id="uidall" name="uidall" value="" lay-verify="uidall">
  <div class="layui-form-item">
    <button class="layui-btn" lay-submit="" lay-filter="demo2">立即发送</button>
  </div>
</form>
<div>
<!-- 发送消息 -->

     
<script src="/Public/framework/js/jquery.min.js?v=2.1.4"></script>
<script src="/Public/framework/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/Public/framework/js/content.min.js?v=1.0.0"></script>
<script src="/Public/framework/layui/layui.all.js"></script>
<script src="/Public/framework/js/jquery.form.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;
  
  //日期
  laydate.render({
    elem: '#date'
  });
  laydate.render({
    elem: '#date1'
  });
  
  //创建一个编辑器
  var editIndex = layedit.build('LAY_demo_editor');
 
  //自定义验证规则
  form.verify({
    uidall: function(value){
      if(value.length == 0){
        return '请确认选择发送消息对象';
      }
    }
	,txt: function(value){
		if(value.length == 0){
			return '请输入内容';
		}
	}
    ,image: function(value){
	  if(value.length == 0){
			return '请上传图片';
		}
      if(value.size/1024 > 1024){
        return '图片大小不能大于1M，请重新上传';
      }
    }
  });
  
  //监听指定开关
  form.on('select(aihao)', function(data){
	  if(data.value==0){
			$('#type0').css('display', 'block');
			$('#type1').css('display', 'none');
			$('#type2').css('display', 'none');
			$('#txt').attr("lay-verify","txt");
			$('#image').attr("lay-verify","demo");
			$('#url').attr("lay-verify","demo");
			$('#urlimg').attr("lay-verify","demo");
			$('#urltitle').attr("lay-verify","demo");
			$('#urltext').attr("lay-verify","demo");
	  }
	  if(data.value==1){
			$('#type0').css('display', 'none');
			$('#type1').css('display', 'block'); 
			$('#type2').css('display', 'none');
			$('#txt').attr("lay-verify","demo");
			$('#image').attr("lay-verify","image");
			$('#url').attr("lay-verify","demo");
			$('#urlimg').attr("lay-verify","demo");
			$('#urltitle').attr("lay-verify","demo");
			$('#urltext').attr("lay-verify","demo");
	  }
	  if(data.value==2){
			$('#type0').css('display', 'none');
			$('#type1').css('display', 'none');
			$('#type2').css('display', 'block');
			$('#txt').attr("lay-verify","demo");
			$('#image').attr("lay-verify","demo");
			$('#url').attr("lay-verify","url");
			$('#urlimg').attr("lay-verify","url");
			$('#urltitle').attr("lay-verify","txt");
			$('#urltext').attr("lay-verify","txt");
	  }
	});  
  



  
var aa='';
$.post("/admin/mes/index", function(res) {
	aa =JSON.parse(res);
	console.log(aa);

layui.use(['tree', 'util'], function(){
  var tree = layui.tree
  ,layer = layui.layer
  ,util = layui.util
  //模拟数据
  ,data = aa;

 
  //基本演示
  tree.render({
    elem: '#test12'
    ,data: data
    ,showCheckbox: true  //是否显示复选框
    ,id: 'demoId1'
    ,isJump: true //是否允许点击节点时弹出新窗口跳转
    
  });
  
  //按钮事件
  util.event('lay-demo', {
    getChecked: function(othis){
      var checkedData = tree.getChecked('demoId1'); //获取选中节点的数据
      layer.alert('已选定发送人');
	  var uidall=JSON.stringify(checkedData);
      //console.log(checkedData);
	   $('#uidall').val(uidall);
    }
    ,setChecked: function(){
      tree.setChecked('demoId1', [12, 16]); //勾选指定节点
    }
    ,reload: function(){
      //重载实例
      tree.reload('demoId1', {
        
      });
      
    }
  });
 
  
});
});

  
  
});
</script>
</body>
</html>