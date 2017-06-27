<?php include mymps_tpl('inc_head');?>
<style>
.vtop{ background-color:#ffffff}
.smalltxt{ font-size:12px!important; color:#999!important; font-weight:100!important}
.altbg1{ background-color:#f1f5f8; width:45%;}
</style>
<div id="<?=MPS_SOFTNAME?>" style=" padding-bottom:0">
    <div class="mpstopic-category">
        <div class="panel-tab">
            <ul class="clearfix tab-list">
                <li><a href="?" class="current">基本设置</a></li>
                <li><a href="?type=nav">文字导航设置</a></li>
                <li><a href="?type=nav_ico">图标导航设置(首页)</a></li>
                <li><a href="?type=gg">幻灯片广告设置</a></li>
            </ul>
        </div>
    </div>
</div>
<form method="post" action="?">
<input type="hidden" name="type" value="<?=$type?>">
<input name="return_url" value="<?php echo GetUrl();?>" type="hidden">
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
    <tr class="firstr"><td colspan="2">手机版全局设置</td></tr>
    <tbody style="display: yes; background-color:white">
        <tr>
            <td class="altbg1" ><b>开启手机版:</b><br /><span class="smalltxt">开启本功能，用户将能访问手机版</span></td><td class="altbg2">
			<label for="allowmobile1"><input class="radio" type="radio" name="settings[allowmobile]" value="1" id="allowmobile1" onclick="$('hidden_settings_mobile').style.display = '';" <? if($mobile[allowmobile] == 1) echo 'checked';?>> 是</label> &nbsp; &nbsp; 
            <label for="allowmobile0"><input class="radio" type="radio"  name="settings[allowmobile]" value="0" id="allowmobile0" onclick="$('hidden_settings_mobile').style.display = 'none';" <? if(empty($mobile[allowmobile])) echo 'checked';?>> 否</label>
            </td>
        </tr>
    <tbody>
    <tbody id="hidden_settings_mobile" style="background-color:white;<?php if(empty($mobile[allowmobile])) echo 'display:none;'?>">
		<!--<tr>
			<td class="altbg1" ><b>开启手机浏览器自动跳转:</b><br /><span class="smalltxt">开启后用户使用手机浏览器访问网站功能页以外页面时自动跳转到网站首页进行访问。</span></td><td class="altbg2"><label for="autorefresh1"><input class="radio" type="radio" name="settings[autorefresh]" value="1" id="autorefresh1" <?php if($mobile[autorefresh] == 1){echo 'checked';} ?>> 是</label> &nbsp; &nbsp; 
			<label for="autorefresh0"><input class="radio" type="radio"  name="settings[autorefresh]" value="0" id="autorefresh0" <?php if(empty($mobile[autorefresh])){echo 'checked';} ?>> 否</label>
			</td>
		</tr>-->
		<tr>
			<td class="altbg1" ><b>是否允许手机版注册:</b><br /><span class="smalltxt">是否开启手机版注册功能，手机注册不会对用户栏目中的注册页必填项进行检测<br />请谨慎开启</span></td><td class="altbg2"><label for="register1"><input class="radio" type="radio" name="settings[register]" value="1" id="register1" <?php if($mobile[register] == 1){echo 'checked';} ?>> 是</label> &nbsp; &nbsp; 
			<label for="register0"><input class="radio" type="radio"  name="settings[register]" value="0" id="register0" <?php if(empty($mobile[register])){echo 'checked';} ?>> 否</label>
			</td>
		</tr>
		<tr>
			<td class="altbg1" ><b>是否允许手机版发布信息:</b><br /><span class="smalltxt">是否开启手机版发布信息功能<br />请谨慎开启</span></td><td class="altbg2"><label for="post1"><input class="radio" type="radio" name="settings[post]" value="1" id="post1" <?php if($mobile[post] == 1){echo 'checked';} ?>> 是</label> &nbsp; &nbsp; 
			<label for="post0"><input class="radio" type="radio"  name="settings[post]" value="0" id="post0" <?php if(empty($mobile[post])){echo 'checked';} ?>> 否</label>
			</td>
		</tr>
		<tr>
			<td class="altbg1" ><b>每页显示主题数:</b><br /><span class="smalltxt">主题列表页每页显示主题个数，推荐值为10</span></td><td class="altbg2"><input name="settings[mobiletopicperpage]" value="<?php echo $mobile[mobiletopicperpage] ? $mobile[mobiletopicperpage] : 10 ;?>" type="text" class="txt"   />
			</td>
		</tr>
		<tr>
			<td class="altbg1" ><b>手机版访问域名:</b><br /><span class="smalltxt">如http://wap.mymps.com.cn<br />需将您指定的二级域名绑定/wap目录</span></td><td class="altbg2"><input name="settings[mobiledomain]" type="text" class="text" value="<?php echo $mobile[mobiledomain]; ?>"/>
			</td>
		</tr>
        <!--<tr>
			<td class="altbg1" ><b>手机版logo路径:</b><br /><span class="smalltxt">默认<font color="red">/logowap.png</font><br />建议尺寸111*26<br /><font color="blue">如没有可留空</font></span></td><td class="altbg2"><input name="settings[mobilelogo]" type="text" class="text" value="<?php echo $mobile[mobilelogo]; ?>"/>
			</td>
		</tr>-->
    </tbody>
</table>
</div>
<center><input name="<?=CURSCRIPT?>_submit" type="submit" value="提 交" class="mymps large"/></center>
</form>
<?php mymps_admin_tpl_global_foot();?>