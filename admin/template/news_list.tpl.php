<?php include mymps_tpl('inc_head');?>

<div id="<?=MPS_SOFTNAME?>" style="padding-bottom:0">
	<div class="mpstopic-category">
		<div class="panel-tab">
			<ul class="clearfix tab-list">
				<li><a href="news.php" <?php if($part == 'list'){?>class="current"<?php }?>>新闻列表</a></li>
				<li><a href="news.php?part=add" <?php if($part == 'add'){?>class="current"<?php }?>>添加新闻</a></li>
			</ul>
			<ul style="float:right; margin-right:10px">
			<form action="?" method="get">
<input name="cityid" value="<?=$cityid?>" type="hidden">
<input name="title" type="input" value="<?php echo $title; ?>" class="text" style="width:120px;"/>
<select name="catid">
<option value="">请选择所属分类</option>
<?=cat_list('channel',0,$catid)?>
</select>
<?php if(!$admin_cityid){?><select name="cityid">
<option value="">所属分站</option>
<?php echo get_cityoptions($cityid); ?>
</select>
<? }else{ ?>
<input name="cityid" value="<?php echo $admin_cityid?>" type="hidden" />
<? }?>
<input type="submit" class="gray mini" value="检索新闻"> 
</form>
			</ul>
		</div>
	</div>
</div>
<form action="?part=list" method="post">
<input name="url" type="hidden" value="<?=GetUrl()?>">
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm" >
    <tr class="firstr">
    <td><input class="checkbox" name="checkall" type="checkbox" id="checkall" onClick="CheckAll(this.form)"/>删?</td>
    <td>分站</td>
    <td>分类</td>
    <td>新闻标题</td>
    <td>新闻状态</td>
    <td>浏览次数</td>
    <td>发布时间</td>
    <td>管理项</td>
  </tr>
<tbody onmouseover="addMouseEvent(this);">
<?php foreach($news AS $row){?>
    <tr bgcolor="white" >
    <td width="40"><input type='checkbox' name='delids[]' value="<?=$row['id']?>" class='checkbox' id="<?=$row['id']?>"></td>
    <td width="40"><?=$row['cityname']?></td>
    <td width="70"><a href="../news.php?catid=<?=$row['catid']?>"><?=$row['catname']?></a></td> 
    <td align="left"><a href="../news.php?id=<?=$row[id]?>" target="_blank" title="<?=$row['title']?>"><?=cutstr($row['title'],42)?></a></td>
    <td><?=$iscommend_arr[$row['iscommend']]?></td>
    <td><?=$row['hit']?> 次</td>
    <td><em><?=GetTime($row['begintime'])?></em></td>
    <td>
     <a href="?part=edit&id=<?=$row['id']?>">编辑</a>
    </td>
  </tr>
<?}?>
</tbody>
</table>
</div>
<center><input type="submit" value="提 交" class="mymps large" name="news_submit"/></center>
</form>
<div class="clear"></div>
<div class="pagination"><?php echo page2();?></div>
<?php mymps_admin_tpl_global_foot();?>