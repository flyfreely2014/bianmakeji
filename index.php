<?php
/*
变码科技自动回复测试
*/
header("Content-type:text/html; charset:utf-8");
define("TOKEN", "bianmakeji");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr))
        {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $event = $postObj->Event;
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if($keyword == "当前时间"||$keyword =="时间")
            {
                $msgType = "text";
                $contentStr = date("Y-m-d H:i:s",time());
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if($keyword =="你好"||$keyword =="尼嚎")
            {
                $msgType = "text";
                $contentStr = "您好，欢迎来到变码科技！";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if($keyword =="微店"||$keyword =="店铺")
            {
                $msgType ="text";
                $contentStr = '<a href="http://wd.koudai.com/?userid=165605294">点击这里</a>';
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if ($event =="subscribe")
            {
            	$msgType ="text";
            	$contentStr = "欢迎关注变码科技，最新优惠活动我们将及时推送给您！您可以尝试回复“时间”获取当前时间，也可以回复“你好”跟我们打个招呼~";
            	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            	echo $resultStr;
            }
        }
        else
        {
            echo "未接收到数据！";
            exit;
        }
    }
}
?>