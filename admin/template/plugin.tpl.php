<?php include mymps_tpl('inc_head');?>
<!--<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
  <tr class="firstr">
  	<td colspan="2">相关说明</td>
  </tr>
  <tr bgcolor="#ffffff">
    <td id="menu_tip">
 <li>插件目录是相对于/plugin目录下，安装新插件之前，请将插件目录上传至/plugin目录下</li>
    </td>
  </tr>
</table>
</div>-->
<?php if($mymps_global['cfg_if_corp'] != 1){?>
<div class="ccc2">
    <ul>
        <img src="../images/warn.gif" align="absmiddle"> 提示：当前你的<font color="red">商家功能已经关闭，团购插件，优惠券插件，商品插件已自动被禁用</font>，您可以在<a href="config.php">系统配置 -> 会员相关设置</a>里开启商家功能
    </ul>
</div>
<div class="clear"></div>
<?php }?>
<form action="?part=list" method="post">
<input name="url" type="hidden" value="<?=GetUrl()?>">
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
    <tr style="font-weight:bold; background-color:#dff6ff">
      <td>编号</td>
      <td>状态</td>
      <td>名称</td>
      <td>标识</td>
      <td>目录</td>
      <td>版本</td>
	  <td>核心</td>
      <td>操作</td>
    </tr>
    <?php foreach($plugin as $list){?>
    <tr bgcolor="white">
     	<td><i><?=$list[id]?>.</i></td>
		<td><?php if($list[disable] == 1){?><font color="red">&times;</font><?php }else{?><font color="green">√</font><?}?></td>
        <td><b><?=$list[name]?></b></td>
        <td><?=$list[flag]?></td>
        <td><?=$list[directory]?></td>
		<td><?=$list[version]?></td>
        <td><?php echo $list[iscore] == 1 ? '√' : '—'; ?></td>
        <td align="left"><?php if($list[iscore] != 1){?><a href="plugin.php?op=edit&id=<?=$list[id]?>">配置</a> | <a href="../<?=$list[flag]?>.php" target="_blank">预览首页</a> | <?php if($list[disable] == 0){?><a href="?op=disable&id=<?=$list[id]?>" style="color:red; text-decoration:underline;">禁用&raquo;</a><?}else{?><a href="?op=able&id=<?=$list[id]?>" style="color:green; text-decoration:underline;">启用&raquo;</a><?}?> <?php } else {?>N/A<?php }?></td>
    </tr>
    <?php }?>
	<!--<tr bgcolor="#FFFFFF" valign="top">
      <td align="center"><b>安装</b></td>
      <td bgcolor="#f5fbff" ><input name="add[name]" value="" type="text" class="text" style="width:100px"/></td>
      <td><input name="add[flag]" value="" type="text" class="text" style="width:100px"/></td>
      <td bgcolor="#f5fbff"><input name="add[directory]" type="text" class="text" style="width:100px; margin-bottom:2px"><br />相对/plugin目录</td>
      <td><input name="add[version]" type="text" class="text" style="width:100px"></td>
      <td bgcolor="#f5fbff"><input name="add[author]" type="text" class="text" style="width:100px"></td>
      <td colspan="3"> -> 请先将插件目录上传至/plugin目录下</td>
    </tr>-->
</table>
</div>
<center><input type="submit" value="更新插件缓存" class="mymps large" name="<?php echo CURSCRIPT; ?>_submit"/></center>
</form>
<?php mymps_admin_tpl_global_foot();?>