<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

//require_once("alipay.config.php");
//require_once("lib/alipay_notify.class.php");

define('IN_HHS', true);
require(dirname(__FILE__) . '/../includes/init2.php');

require(ROOT_PATH . 'includes/lib_payment.php');
require(ROOT_PATH . 'includes/lib_order.php');
require_once(ROOT_PATH ."includes/modules/lib/alipay_notify.class.php");
 include_once ROOT_PATH."languages/zh_cn/wx_msg.php";
//计算得出通知验证结果
$payment = get_payment("alipay");
$alipay_config=array();
$alipay_config['partner']		= $payment['alipay_partner'];
$alipay_config['key']			= $payment['alipay_key'];
$alipay_config['sign_type']    = 'MD5';
$alipay_config['input_charset']= 'utf-8';
$alipay_config['cacert']='';

$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功

	$doc = new DOMDocument();	
	if ($alipay_config['sign_type'] == 'MD5') {
		$doc->loadXML($_POST['notify_data']);
	}
	if ($alipay_config['sign_type'] == '0001') {
		$doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
	}
	
	if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
		//openid
		$openid = $doc->getElementsByTagName( "subject" )->item(0)->nodeValue;
		//支付宝交易号
		$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
		//交易状态
		$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;

		$money = $doc->getElementsByTagName( "total_fee" )->item(0)->nodeValue;
		
		//$order_sn=trim(substr($out_trade_no,0,13));
		//pay_team_action($order_sn);
		
		if($trade_status == 'TRADE_FINISHED') {
			//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
			//注意：
			//该种交易状态只在两种情况下出现
			//1、开通了普通即时到账，买家付款成功后。
			//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
	
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			
			echo "success";		//请不要修改或删除
		}
		else if ($trade_status == 'TRADE_SUCCESS') {
			//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
			//注意：
			//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
	
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		   
		   if (! empty($body)) {
		   	 
				     

                    $sql = "select uname,user_id from ".$GLOBALS['hhs']->table('users')." where openid='$openid'";
                    $result = $GLOBALS['db']->getRow($sql);
                    $uid = $result['user_id'];
                    $uname = $result['uname'];
                    $sql="update ".$GLOBALS['hhs']->table('users')." set user_money=user_money+$total_fee where openid='$openid' ";
                    $rs = $GLOBALS['db']->query($sql);
                    if($rs){
                        $sql = "insert into ".$GLOBALS['hhs']->table('account_log')."(user_id,user_money,change_time,change_desc,change_type) values('$uid','$total_fee','".gmtime()."','会员余额充值','0')";
                        $GLOBALS['db']->query($sql);
    
                        $url = 'user.php?act=account_detail';
    
                        $desc = "充值金额：".$total_fee."\r\n充值状态：已充值";
     
                        $weixin=new class_weixin($GLOBALS['appid'],$GLOBALS['appsecret']);
    
                        $weixin->send_wxmsg($openid, '充值成功' , $url , $desc);
                    }		   			
		   	 
		   }
		 
		    
		    
			echo "success";		//请不要修改或删除
		}
	}

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>