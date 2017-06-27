<?php include mymps_tpl('inc_head');?>
<script type='text/javascript' src='js/vbm.js'></script>
<form action="seoset.php" method="post">
<div id="<?=MPS_SOFTNAME?>">
<table border="0" cellspacing="0" cellpadding="0" class="vbm">
  <tr class="firstr">
  	<td colspan="2">SEO基本设置</td>
  </tr>
 <tr bgcolor="#f1f5f8">
 <td style="width:35%; line-height:22px">网站标题，显示于title标签中网站名称之后，适当出现关键词</td>
 <td bgcolor="#ffffff"><input name="seo_sitename" value="<?=$seo['seo_sitename']?>" class="text"/></td>
 </tr>
 <tr bgcolor="#f1f5f8">
 <td style="width:35%; line-height:22px">网站关键词，多个关键词以“,”分隔开<br />
分站名用 <font color="red">{city}</font> 代替(未单独设置分站关键词时生效)</td>
 <td bgcolor="#ffffff"><input name="seo_keywords" value="<?=$seo['seo_keywords']?>" class="text"/></td>
 </tr>
 <tr bgcolor="#f1f5f8">
 <td style="width:35%; line-height:22px">网站描述，不超过255个字符<br />
分站名用 <font color="red">{city}</font> 代替(未单独设置分站描述时生效)</td>
 <td bgcolor="#ffffff"><textarea name="seo_description" style="height:100px; width:205px"><?=$seo['seo_description']?></textarea></td>
 </tr>
 <tr class="firstr">
  	<td colspan="2">SEO详细设置</td>
  </tr>
 <tr bgcolor="#f5f8ff" style="font-weight:bold">
      <td>针对页面</td>
      <td>显示方式</td>
    </tr>
<tr bgcolor="#f1f5f8">
 <td style="width:35%">站务/about.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_about],'seo_force_about')?></td>
 </tr>
  <tr bgcolor="#f1f5f8">
  <td >分类/category.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_category],'seo_force_category')?></td>
 </tr>
  <tr bgcolor="#f1f5f8">
  <td >信息/info.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_info],'seo_force_info')?></td>
 </tr>
 <tr bgcolor="#f1f5f8">
  <td >新闻/news.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_news],'seo_force_news')?></td>
  <tr bgcolor="#f1f5f8">
 </tr>
  <tr bgcolor="#f1f5f8">
  <td >空间/space.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_space],'seo_force_space')?></td>
 </tr>
  <tr bgcolor="#f1f5f8">
  <td >店铺/store.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_store],'seo_force_store')?></td>
 </tr>
   <tr bgcolor="#f1f5f8">
  <td >商品/goods.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_goods],'seo_force_goods')?></td>
 </tr>
  <tr bgcolor="#f1f5f8">
  <td>商家黄页/corp.php</td>
 <td bgcolor="#ffffff"><?=GetSeoType($seo[seo_force_yp],'seo_force_yp')?></td>
 </tr>
</table>
</div>
<center><input name="seoset_submit" value="提 交" class="mymps large" type="submit"/></center>
</form>
<?php mymps_admin_tpl_global_foot();?>