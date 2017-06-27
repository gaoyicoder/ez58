<?php

//decode by QQ:270656184 http://www.yunlu99.com/
require_once 'alipay_core.function.php';
require_once 'alipay_md5.function.php';
class AlipaySubmit
{
	var $alipay_config;
	var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';
	function __construct($_var_0)
	{
		$this->alipay_config = $_var_0;
	}
	function AlipaySubmit($_var_0)
	{
		$this->__construct($_var_0);
	}
	function buildRequestMysign($_var_1)
	{
		$_var_2 = createLinkstring($_var_1);
		$_var_3 = "";
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case 'MD5':
				$_var_3 = md5Sign($_var_2, $this->alipay_config['key']);
				break;
			default:
				$_var_3 = "";
		}
		return $_var_3;
	}
	function buildRequestPara($_var_4)
	{
		$_var_5 = paraFilter($_var_4);
		$_var_1 = argSort($_var_5);
		$_var_3 = $this->buildRequestMysign($_var_1);
		$_var_1['sign'] = $_var_3;
		$_var_1['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));
		return $_var_1;
	}
	function buildRequestParaToString($_var_4)
	{
		$_var_6 = $this->buildRequestPara($_var_4);
		$_var_7 = createLinkstringUrlencode($_var_6);
		return $_var_7;
	}
	function buildRequestForm($_var_4, $_var_8, $_var_9)
	{
		$_var_6 = $this->buildRequestPara($_var_4);
		$_var_10 = '<form id=\'alipaysubmit\' name=\'alipaysubmit\' action=\'' . $this->alipay_gateway_new . '_input_charset=' . trim(strtolower($this->alipay_config['input_charset'])) . '\' method=\'' . $_var_8 . '\'>';
		while (list($_var_11, $_var_12) = each($_var_6)) {
			$_var_10 .= '<input type=\'hidden\' name=\'' . $_var_11 . '\' value=\'' . $_var_12 . '\'/>';
		}
		$_var_10 = $_var_10 . '<input type=\'submit\'  value=\'' . $_var_9 . '\' style=\'display:none;\'></form>';
		$_var_10 = $_var_10 . '<script>document.forms[\'alipaysubmit\'].submit();</script>';
		return $_var_10;
	}
	function query_timestamp()
	{
		$_var_13 = $this->alipay_gateway_new . 'service=query_timestamp&partner=' . trim(strtolower($this->alipay_config['partner'])) . '&_input_charset=' . trim(strtolower($this->alipay_config['input_charset']));
		$_var_14 = "";
		$_var_15 = new DOMDocument();
		$_var_15->load($_var_13);
		$_var_16 = $_var_15->getElementsByTagName('encrypt_key');
		$_var_14 = $_var_16->item(0)->nodeValue;
		return $_var_14;
	}
	function getHtml($_var_4)
	{
		$_var_6 = $this->buildRequestPara($_var_4);
		$_var_17 = '';
		while (list($_var_11, $_var_12) = each($_var_6)) {
			$_var_17 .= '&' . $_var_11 . '=' . urlencode($_var_12);
		}
		$_var_17 = $this->alipay_gateway_new . '_input_charset=' . trim(strtolower($this->alipay_config['input_charset'])) . $_var_17;
		return $_var_17;
	}
}