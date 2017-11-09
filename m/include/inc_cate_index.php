<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 11/6/17
 * Time: 11:15 PM
 */
if(!$cat = get_cat_info($catid)){
    errormsg('您所指定的栏目不存在或者已被删除！');
}
include mymps_tpl('cate_index');
?>