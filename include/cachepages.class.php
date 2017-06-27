<?php    
class cachepages {    
  //缓存目录    
  var $cacheRoot        = "";
  //缓存更新时间秒数，0为不缓存
  var $cacheLimitTime   = "";
  //缓存文件名    
  var $cacheFileName    = "";
  //缓存扩展名    
  var $cacheFileExt     = "php";    
     
  /*   
   * 构造函数   
   * int $cacheLimitTime 缓存更新时间   
   */   
  function cachepages( $cacheLimitTime,$curscript) {
    if( $cacheLimitTime )  {
		$this->cacheLimitTime = $cacheLimitTime;
	}
	if(CURSCRIPT == 'information'){
		$this->cacheRoot = MYMPS_DATA.'/pagesinfo/';
	}elseif(CURSCRIPT == 'category'){
		$this->cacheRoot = MYMPS_DATA.'/pageslist/';
	}else{
		$this->cacheRoot = MYMPS_DATA.'/pagesmymps/';
	}
	$this->cacheFileName = $this->getCacheFileName($curscript);
    ob_start();
  }    
      
  /*   
   * 检查缓存文件是否在设置更新时间之内   
   * 返回：如果在更新时间之内则返回文件内容，反之则返回失败   
   */   
  function cacheCheck(){
	global $timestamp;
    if( $this->cacheLimitTime > 0 && file_exists( $this->cacheFileName ) ) { 
      $cachePagesTime = $this->getFileCreateTime( $this->cacheFileName );
      if( $cachePagesTime + $this->cacheLimitTime > $timestamp ) {
        echo file_get_contents( $this->cacheFileName );
        ob_end_flush();
        exit;
      }
    }
  }    
     
  /*   
   * 缓存文件或者输出静态   
   * string $staticFileName 静态文件名（含相对路径）   
   */   
  function caching( $staticFileName = "" ){
	global $timestamp;

	if( $staticFileName ) {
	  $this->saveFile( $staticFileName, $cacheContent );    
	}
	
	$cacheContent = ob_get_contents();
 	//echo $cacheContent;
	ob_end_flush();
	$this->saveFile( $this->cacheFileName, $cacheContent );
      
  }    
      
  /*   
   * 清除缓存文件   
   * string $fileName 指定文件名(含函数)或者all（全部）   
   * 返回：清除成功返回true，反之返回false   
   */   
  function clearCache( $fileName = "all" ) {    
    if( $fileName != "all" ) {    
      $fileName = $this->cacheRoot . $fileName.".".$this->cacheFileExt;    
      if( file_exists( $fileName ) ) {    
        return @unlink( $fileName );    
      }else return false;    
    }    
    if ( is_dir( $this->cacheRoot ) ) {    
      if ( $dir = @opendir( $this->cacheRoot ) ) {    
        while ( $file = @readdir( $dir ) ) {    
          $check = is_dir( $file );
          if ( !$check )    
          @unlink( $this->cacheRoot . $file );    
        }    
        @closedir( $dir );    
        return true;    
      }else{    
        return false;    
      }    
    }else{    
      return false;    
    }    
  }    
     
  /*   
   * 根据当前动态文件生成缓存文件名   
   */   
  function getCacheFileName($curscript) {
    return  $this->cacheRoot . $curscript.".".$this->cacheFileExt;    
  }    
     
  /*   
   * 缓存文件建立时间   
   * string $fileName   缓存文件名（含相对路径）   
   * 返回：文件生成时间秒数，文件不存在返回0   
   */   
  function getFileCreateTime( $fileName ) {
    if( file_exists( $fileName ) ) {
      return filemtime( $fileName );
    }else return 0;    
  }    
      
  /*   
   * 保存文件   
   * string $fileName  文件名（含相对路径）   
   * string $text      文件内容   
   * 返回：成功返回ture，失败返回false   
   */   
  function saveFile($fileName, $text) {    
    if( ! $fileName || ! $text ) return false;    
     
    if( $this->makeDir( dirname( $fileName ) ) ) {    
      if( $fp = fopen( $fileName, "w" ) ) {    
        if( @fwrite( $fp, $text ) ) {    
          fclose($fp);    
          return true;    
        }else {    
          fclose($fp);    
          return false;    
        }
      }    
    }    
    return false;    
  }    
     
  /*   
   * 连续建目录   
   * string $dir 目录字符串   
   * int $mode   权限数字   
   * 返回：顺利创建或者全部已建返回true，其它方式返回false   
   */   
  function makeDir( $dir, $mode = "0777" ) {
    if (!file_exists($dir)){
		makeDir(dirname($dir));
		mkdir($dir, $mode);
		return true;
	} elseif(file_exists($dir)){
		return true;
	} else {
		return false;
	}
  }    
}    
?>