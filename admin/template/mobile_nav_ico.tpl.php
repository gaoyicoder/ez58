<?php include mymps_tpl('inc_head');?>
<style>
.vtop{ background-color:#ffffff}
.smalltxt{ font-size:12px!important; color:#999!important; font-weight:100!important}
.altbg1{ background-color:#f1f5f8; width:45%;}
.ico{ width:28px; height:28px;}
</style>
<div id="<?=MPS_SOFTNAME?>" style=" padding-bottom:0">
    <div class="mpstopic-category">
        <div class="panel-tab">
            <ul class="clearfix tab-list">
                <li><a href="?">基本设置</a></li>
                <li><a href="?type=nav">文字导航设置</a></li>
                <li><a href="?type=nav_ico" class="current">图标导航设置(首页)</a></li>
                <li><a href="?type=gg">幻灯片广告设置</a></li>
            </ul>
        </div>
    </div>
</div>

<?php if($type == 'nav_ico'){?>
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
  <tr class="firstr">
  	<td colspan="2">技巧提示</td>
  </tr>
  <tr bgcolor="#ffffff">
    <td id="menu_tip">
  <li>图标导航显示在首页文字主导航下方，以图标形式展示，图标尺寸建议44×44</li>
  <li>您可以<a href="?type=restore" style="color:red; text-decoration:underline; font-weight:bold; font-size:18px">点此恢复默认图标导航&raquo;</a></li>
    </td>
  </tr>
</table>
</div>
<?php }?>

<form method="post" action="?">
<input type="hidden" name="type" value="<?=$type?>">
<input name="return_url" value="<?php echo GetUrl();?>" type="hidden">
<div id="<?=MPS_SOFTNAME?>">
    <table border="0" cellspacing="0" cellpadding="0" class="vbm">
    <tr class="firstr">
    <td colspan="8"><b>手机版导航设置</b></td>
    </tr>
    <tr style="font-weight:bold; background-color:#F5F8FF">
      <td width="50"><input class="checkbox" name="chkall" type="checkbox" onclick="AllCheck('prefix', this.form, 'delids')"/> 删?</td>
      <td>启用</td>
      <td>图标路径(<font color="red">*</font>)</td>
      <td>文字(<font color="red">*</font>)</td>
      <td>文字颜色</td>
      <td>链接地址(<font color="red">*</font>)</td>
      <td>显示顺序</td>
    </tr>
    <?php if(!$rows_num){?>
    <tr bgcolor="#ffffff">
         <td colspan="9"><br />目前并无手机图标导航，<a href="?type=restore" style="text-decoration:underline">点此应用默认手机图标导航&raquo;</a><br /><br /></td>
    </tr>
    <?php }else{foreach($url as $k =>$value){?>
		<tr bgcolor="#ffffff">
          <td bgcolor="white"><input class="checkbox" type='checkbox' name='delids[]' value='<?=$value[id]?>' id="<?=$value[id]?>"></td>
          <td bgcolor="white" width="60px">
          <input name="isviewids[<?=$value[id]?>]" value="2" type="checkbox" class="checkbox" <?php if($value['isview'] == '2'){echo 'checked';}?>></td>
          <td bgcolor="white">
<? if($value[ico]){?><img class="ico" src="<?=$mymps_global[SiteUrl]?><?=$value[ico]?>"> <? }?><input name="navico[<?=$value[id]?>]" value="<?=$value[ico]?>" type="text" class="text" style="width:280px"/>
          </td>
          <td bgcolor="white">
		  <input name="navtitle[<?=$value[id]?>]" value="<?=$value[title]?>" type="text" class="text" style="width:80px"/>
          </td>
          
          <td bgcolor="white">
            <select name="showcolor[<?=$value[id]?>]">
            <option value="">默认颜色</option>
            <?=get_color_options($value[color])?>
            </select>  
          </td>
          
          <td bgcolor="white">
<input name="navurl[<?=$value[id]?>]" value="<?=$value[url]?>" type="text" class="text" style="width:200px"/>
          </td>
          <td bgcolor="white"><input name="displayorder[<?=$value[id]?>]" value="<?=$value[displayorder] ? $value[displayorder] : 0?>" type="text" class="txt"/></td>
        </tr>
    <?php }}?>
    <tbody id="secqaabody" bgcolor="white">
    <tr bgcolor="#f5fbff">
      <td bgcolor="white" align="center">新增<a href="###" onclick="newnode = $('secqaabodyhidden').firstChild.cloneNode(true); $('secqaabody').appendChild(newnode)">[+]</a></td>
      <td bgcolor="white"><select name="newisview[]">
      <?=get_ifview_options(2)?>
      </select></td>
      <td bgcolor="white"><input name="newico[]" value="" type="text" class="text" style="width:280px"></td>
      <td bgcolor="white"><input name="newtitle[]" value="" type="text" class="text" style="width:80px;"/></td>
      <td bgcolor="white">
        <select name="newshowcolor[]">
        <option value="">默认颜色</option>
        <?=get_color_options()?>
        </select>  
        </td>
        
      <td bgcolor="white"><input name="newurl[]" value="" type="text" class="text" style="width:200px"/></td>
      <td bgcolor="white"><input name="newdisplayorder[]" value="" type="text" class="txt"/></td>
      
    </tr>
    </tbody>
    <tbody id="secqaabodyhidden" style="display:none">
      <tr bgcolor="#f5fbff">
      <td align="center" bgcolor="white">&nbsp;</td>
      <td bgcolor="white"><select name="newisview[]">
      <?=get_ifview_options(2)?>
      </select></td>
      <td bgcolor="white"><input name="newico[]" value="" type="text" class="text" style="width:280px"></td>
      <td bgcolor="white"><input name="newtitle[]" value="" type="text" class="text" style="width:80px;"/></td>
      <td bgcolor="white">
        <select name="newshowcolor">
        <option value="">默认颜色</option>
        <?=get_color_options($navurl[color])?>
        </select>  
      </td>
      
      <td bgcolor="white"><input name="newurl[]" value="" type="text" class="text" style="width:200px"/></td>
      <td bgcolor="white"><input name="newdisplayorder[]" value="" type="text" class="txt"/></td>
      
    </tr>
    </tbody>
</table>
</div>
<center><input name="<?=CURSCRIPT?>_submit" type="submit" value="提 交" class="mymps large"/></center>
</form>
<div class="pagination"><?=page2()?></div>
<?php mymps_admin_tpl_global_foot();?>