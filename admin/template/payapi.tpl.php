<?php include mymps_tpl('inc_head');?>
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
    <tr style="font-weight:bold; background-color:#dff6ff">
      <td style="width:15%">接口名称</td>
      <td style="width:40%">接口描述</td>
      <td>开启状态</td>
      <td>接口类型</td>
      <td>编辑</td>
    </tr>
    <?php foreach($payapi as $k =>$value){?>
        <tr bgcolor="#ffffff">
          <td><b><?=$value[payname]?></b></td>
          <td><em><?=$value[paysay]?></em></td>
          <td><?=$value[isclose] == '0' ? '<font color=green>开启</font>' : '<font color=red>关闭</font>'?></td>
          <td><?=$value[paytype]?></td>
          <td><a href="?payid=<?=$value[payid]?>">详情</a></td>
        </tr>
    <?}?>
</table>
</div>
<?php if(!empty($payid)){?>
<form action="?" method="post">
<input type="hidden" name="payid" value="<?=$payid?>">
<input name="return_url" value="<?php echo GetUrl(); ?>" type="hidden" />
<div id="<?=MPS_SOFTNAME?>" style="margin-top:10px; clear:both">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
<tr class="firstr">
<td colspan="2">配置支付接口</td>
</tr>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">接口类型：</td>
    <td bgcolor="white">
    <input name="paytype" value="<?=$paydetail[paytype]?>" readonly class="text"/>
    </td>
  </tr>
  <?php if($paydetail[paytype] == 'alipay'){?>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">收款类型：</td>
    <td bgcolor="white">
    <label for="r1"><input type="radio" name="buytype" value="1" id="r1" <?php if($paydetail[buytype] == 1) echo 'checked';?>>即时到帐收款</label>
	<label for="r3"><input type="radio" name="buytype" value="3" id="r3" <?php if($paydetail[buytype] == 3) echo 'checked';?>>担保交易收款</label>
    <label for="r2"><input type="radio" name="buytype" value="2" id="r2" <?php if($paydetail[buytype] == 2) echo 'checked';?>>双功能收款</label>
    </td>
  </tr>
  <?php }?>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">接口名称：</td>
    <td bgcolor="white">
    <input name="payname" value="<?=$paydetail[payname]?>" class="text"/>
    </td>
  </tr>
  <?php if($paydetail['paytype'] == 'alipay' || $paydetail['paytype'] == 'alipay_h5'){?>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">参数一：</td>
    <td bgcolor="white">
    <input name="payemail" value="<?=$paydetail[payemail]?>" class="text"/>
    </td>
  </tr>
  <?php }?>
<?php if($paydetail['paytype'] == 'wxpay'){?>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">参数一：</td>
    <td bgcolor="white">
    <input name="appid" value="<?=$paydetail[appid]?>" class="text"/>
    </td>
  </tr>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">参数二：</td>
    <td bgcolor="white">
    <input name="appkey" value="<?=$paydetail[appkey]?>" class="text"/>
    </td>
  </tr>
  <?php }?>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25"><?php if($paydetail['paytype'] == 'alipay' || $paydetail['paytype'] == 'alipay_h5'){?>参数二<? }elseif($paydetail['paytype']=='wxpay'){?>参数三<? }else{?>参数一<?php }?>：</td>
    <td bgcolor="white">
    <input name="payuser" value="<?=$paydetail[payuser]?>" class="text"/> 
    </td>
  </tr>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25"><?php if($paydetail['paytype'] == 'alipay' || $paydetail['paytype'] == 'alipay_h5'){?>参数三<? }elseif($paydetail['paytype']=='wxpay'){?>参数四<? }else{?>参数二<?php }?>：</td>
    <td bgcolor="white">
    <input name="paykey" value="<?=$paydetail[paykey]?>" class="text"/>
    </td>
  </tr>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">接口状态：</td>
    <td bgcolor="white">
    <label for="0"><input name="isclose" type="radio" value="0" class="radio" id="0" <?php if($paydetail['isclose'] == '0') echo 'checked'; ?>/>开启</label>
    <label for="1"><input name="isclose" type="radio" value="1" class="radio" id="1" <?php if($paydetail['isclose'] == '1') echo 'checked'; ?>/>关闭</label>
    </td>
  </tr>
  <tr bgcolor="#f5fbff">
    <td width="15%" height="25">接口描述：</td>
    <td bgcolor="white">
    <textarea name="paysay" style="width:320px; height:100px">
    <?=clear_html($paydetail[paysay])?>
    </textarea>
    </td>
  </tr>
</table>
</div>
<center><input type="submit" name="<?=CURSCRIPT?>_submit" value="提 交" class="mymps large"/></center>
  </form>
<?php }?>
<?php mymps_admin_tpl_global_foot();?>