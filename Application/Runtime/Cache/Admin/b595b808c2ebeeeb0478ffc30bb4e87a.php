<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <LINK rel="Bookmark" href="/favicon.ico" >
    <LINK rel="Shortcut Icon" href="/Public/admin//favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/admin/lib/html5.js"></script>
    <script type="text/javascript" src="/Public/admin/lib/respond.min.js"></script>
    <script type="text/javascript" src="/Public/admin/lib/PIE_IE678.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/lib/Hui-iconfont/1.0.7/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Huploadify/Huploadify.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/Public/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/ztree/zTreeStyle.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/page.css" />
    <link rel="stylesheet" type="text/css" href="/Public/kindeditor/themes/default/default.css" />

    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>后台管理系统</title>
</head>
<body>

<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top navbar_new">
        <div class="container-fluid cl">
            <a class="logo navbar-logo f-l mr-10 hidden-xs navbar_fa_font" href="<?php echo U('Index/index');?>">
				<img src="/Public/admin/static/h-ui/images/bar_logo.png">
				<span>智慧税务网签管理系统</span>
			</a>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl" style="margin-top: 30px;">
                    <!--<li class="dropDown dropDown_hover">-->
                    <!--<a href="#" class="dropDown_A"><i class="admin_icon"></i><?php echo ($_SESSION['admin']['username']); ?>-->
                    <!--<i class="Hui-iconfont">&#xe6d5;</i>-->
                    <!--</a>-->
                    <!--<ul class="dropDown-menu menu radius box-shadow">-->
                    <!--<li><a href="javascript:;">个人资料</a></li>-->
                    <!--</ul>-->
                    <!--</li>-->

                    <li class="dropDown dropDown_hover">
                        <a href="javascript:;" class="clearche">
<!--                            <i class="cache_icon"></i> -->
							更新缓存
                        </a>
                    </li>
                    <!--<li><a href="<?php echo U('Login/index');?>"><i class="switch_icon"></i>切换账户</a></li>-->
                    <li><a href="<?php echo U('Login/index');?>">
<!--						<i class="exit_icon"></i>-->
						退出</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<aside class="Hui-aside aside_fa_bg">
    <input runat="server" id="divScrollValue" type="hidden" value=""/>
    <div class="menu_dropdown bk_2">
        <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl id="menu-<?php echo ($vo["id"]); ?>">
                <dt class="aside_fa_dt">
                    <i class="aside_fa_sty fa fa-<?php echo ($vo["icon"]); ?>"></i>
                    <span class="aside_fa_nam"><?php echo ($vo["name"]); ?></span>
                    <i class="Hui-iconfont menu_dropdown-arrow aside_fa_arw">&#xe6d5;</i>
                </dt>
                <dd class="aside_fa_dd">
                    <ul>
                        <?php if(is_array($vo["items"])): $i = 0; $__LIST__ = $vo["items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
                                <a _href="/index.php/Admin/<?php echo ($v["controller"]); ?>/<?php echo ($v["action"]); ?>" data-title="<?php echo ($v["name"]); ?>" href="javascript:void(0)">
                                    <i class="fa fa-<?php echo ($v["icon"]); ?>"></i> <?php echo ($v["name"]); ?>
                                </a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </dd>
            </dl><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</aside>
<div class="dislpayArrow hidden-xs">
    <a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a>
</div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active">
                    <span title="我的桌面" data-href="<?php echo U('Index/welcome');?>">我的桌面</span>
                    <em></em>
                </li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group">
            <a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d4;</i>
            </a>
            <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d7;</i>
            </a>
        </div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="<?php echo U('Index/welcome');?>"></iframe>
        </div>
    </div>
</section>
<script type="text/javascript" src="/Public/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/Public/admin/lib/layer3/layer.js"></script>
<script type="text/javascript" src="/Public/admin/lib/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/Huploadify/jquery.Huploadify.js"></script>
<script type="text/javascript" src="/Public/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/Public/admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/Public/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="/Public/ztree/jquery.ztree.all.js"></script>
<script type="text/javascript" src="/Public/js/md5.js"></script>
<script type="text/javascript" src="/Public/kindeditor/kindeditor-all-min.js"></script>
</body>
</html>
<script>
    $('.clearche').click(function () {
        $.post('<?php echo U("Index/clearchache");?>', function (data) {
            if (data.status == 1) {
                layer.msg(data.msg);
            }
        }, 'json');
    })
    $('.view_window').click(function () {
        layer.open({
            type: 2,
            title: '商户设置',
            shadeClose: true,
            shade: 0.8,
            area: ['380px', '60%'],
            content: "<?php echo U('Admin/merchant');?>" //iframe的url
        });
    })
	$(".aside_fa_dd ul li a").each(function(index){
		$(".aside_fa_dd ul li a").eq(index).click(function(){
			$(".aside_fa_dd ul li a").removeClass("nav_dd_cur");
			$(".aside_fa_dd ul li a").eq(index).addClass("nav_dd_cur");
		})
	})
</script>