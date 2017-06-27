<?php include mymps_tpl('inc_head');?>
<script language=javascript>
function chkform(){
	if(document.form1.title.value==""){
		alert('请输入链接标题！');
		document.form1.title.focus();
		return false;
	}
	if(document.form1.cat.value==""){
		alert('请选择链接！');
		document.form1.cat.focus();
		return false;
	}
}
</script>
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
  <tr class="firstr">
  	<td colspan="2">技巧提示</td>
  </tr>
  <tr bgcolor="#ffffff">
    <td id="menu_tip">
  <li>启用状态不设置为启用时，仅作为一个链接方案保存，你可以在启用的时候开启它</li>
    </td>
  </tr>
</table>
</div>
<form method=post onSubmit="return chkform()" name="form1" action="?part=edit">
<input name="id" value="<?=$navurl[id]?>" type="hidden">
<div id="<?=MPS_SOFTNAME?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="vbm">
<tr class="firstr">
<td colspan="2"><a href="javascript:collapse_change('1')">导航基本信息</a>
</td>
</tr>
<tr bgcolor="#f5fbff">
  <td width="15%">链接文字： </td>
  <td><input name="title" type="text" class="text" id="title" value="<?=$navurl[title]?>" size="30"> 
  		<select name="showcolor">
          <option value="">默认颜色</option>
          <?=get_color_options($navurl[color])?>
        </select>
  		<font color="red">*</font></td>
</tr>
<tr bgcolor="#f5fbff">
  <td width="15%">链接地址： </td>
  <td><input name="url" type="text" class="text" id="title" value="<?=$navurl[url]?>" size="30">
  		<font color="red">*</font></td>
</tr>
<tr bgcolor="#f5fbff">
    <td>导航类型： </td>
    <td>
    <select name="typeid">
    <?=get_navtype_options($navurl[typeid])?>
    </select>  
    </td>
</tr>
<tr bgcolor="#f5fbff">
    <td>窗口打开形式： </td>
    <td>
    <select name="target">
    <?=get_target_options($navurl[target])?>
    </select>  
    </td>
</tr>
<tr bgcolor="#f5fbff">
  <td>是否启用： </td>
  <td>     <select name="isview">
    <?=get_ifview_options($navurl[isview])?>
    </select>  </td>
</tr>
<tr bgcolor="#f5fbff">
  <td>导航排序： </td>
  <td><input name="displayorder" type="text" class="txt" value="<?=$navurl[displayorder]?>" size="13"></td>
</tr>
</table>
</div>
<center>
<input type="submit" name="<?php echo CURSCRIPT; ?>_submit" value="保存修改" class="mymps mini" />　
<input type="button" onclick="location.href='?'" value="返 回" class="mymps mini">
</center>
</form>
<?php mymps_admin_tpl_global_foot();?>