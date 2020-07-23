/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
*
* @author Roddy <luolonghao@gmail.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/

KindEditor.plugin('hello', function(K) {
    var editor = this, name = 'hello';
    // 点击图标时执行
    editor.clickToolbar(name, function() {
        editor.insertHtml('你好');
    });
});
