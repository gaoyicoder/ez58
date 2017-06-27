<?php include mymps_tpl('inc_head');?>

<form name="form1" action="member.php?do=member&part=payupdate" method="post">
<input name="userid" type="hidden" value="<?=$row['userid']?>"/>
<input name="id" type="hidden" value="<?=$id?>"/>
<div id="<?=MPS_SOFTNAME?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="vbm">
	<tr class="firstr">
    	<td colspan="2">
        给<?=$row['userid']?>充值金币
        </td>
    </tr>
	<tr bgcolor="#ffffff">
        <td class="altbg1" style="width:30%">用户编号：</td>
        <td>
            <b><?=$id?></b>
        </td>
    </tr>
    <tr bgcolor="#ffffff">
        <td class="altbg1">用户名：</td>
        <td>
            <b><?=$row['userid']?></b>
        </td>
    </tr>
    <? if($row['tname']){?>
    <tr bgcolor="#ffffff">
        <td class="altbg1">机构名：</td>
        <td>
            <b><?=$row['tname']?></b>
        </td>
    </tr>
    <? }?>
    <tr bgcolor="#ffffff">
        <td class="altbg1">当前余额：</td>
        <td>
           <img src="../member/images/mymps_icon_incomes.gif" align="absmiddle"> <b><?=$row['money_own']?></b>
        </td>
    </tr>
    <tr bgcolor="#ffffff">
        <td class="altbg1">充值金币数(请填写整数)：</td>
        <td>
            <input name="paymoney" type="text" class="txt"/>
        </td>
    </tr>
    
</table>
</div>
<center><input type="submit" class="mymps mini" value="提 交">
<input type="button" onClick="location.href='member.php?if_corp=<?=$row['if_corp']?>'"  class="mymps mini" value="返 回"></center>
</form>
<div class="clear"></div>
<div class="clear"></div>
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
	<tr class="firstr">
    <td colspan="7">
    <?=$row['userid']?>的充值记录
    </td>
    </tr>
    <tr style="font-weight:bold;">
      <td>订单号</td>
      <td>汇款者</td>
      <td>金额</td>
      <td>汇款时间</td>
      <td>汇款IP</td>
      <td>备注</td>
      <td>接口</td>
    </tr>
    <?php if(!empty($list)){
	foreach($list as $list){
	?>
    <tr bgcolor="white">
        <td><?=$list[orderid]?></td>
        <td><a href="javascript:void(0);" onclick="setbg('<?=MPS_SOFTNAME?>会员中心',400,110,'../box.php?part=member&userid=<?=$list[userid]?>&admindir=<?=$admindir?>')"><?=$list[userid]?></a></td>
        <td><em><font color="red"><?=$list[money]?></font></em></td>
        <td><?=GetTime($list[posttime])?></td>
        <td align="left"><?=$list[payip]?></td>
        <td align="left"><?=$list[paybz]?></td>
        <td align="left"><?=$list[type]?></td>
    </tr>
    <?php }}else{?>
    <tr bgcolor="white">
    	<td colspan="7">该用户暂无充值记录！</td>
    </tr>
	<?php }?>

</table>
</div>
<?php mymps_admin_tpl_global_foot();?>