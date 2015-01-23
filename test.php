<?php
header("content-type:text/html; charset:utf-8");
define("TOKEN", "bianmakeji");
$default="无该关键字，请确认输入是否正确";
$rule=Array("目录"，"你好","店铺","报价","活动","手机","港版","日版","美版","韩版","国行","苹果","三星","小米","华为","魅族");
$Msg = new Msg();
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
$fromUsername = $postObj->FromUserName;
$toUsername = $postObj->ToUserName;
$keyword = trim($postObj->Content);
$welcome = '欢迎关注变码科技，您可以回复"目录"来了解功能,回复目录中的关键字了解相应信息';
$index= "店铺<br />报价<br />活动<br />手机";
$hello = "您好~欢迎来到变码科技！";
$shop = '<a href="http://wd.koudai.com/?userid=165605294">变码科技微店</a>';
$activity = '<a href="http://wd.koudai.com/?userid=165605294">变码科技微店</a>';
$phone = "土豪又要换手机了，恭喜恭喜，小店什么手机都有喔。港版、日版、美版、韩版、国行各种版本齐全；苹果、三星、小米、华为、魅族各大国内外品牌货源充足~买手机您来对了~";
$hk = "港版好啊，便宜实惠上档次";
$japan = "日版酷啊，拍照都不带消音的";
$america = "美版帅啊，那可是进口货啊";
$korea = "欧巴，选我没错哒";
$china = "哇塞，土豪，咱们做朋友吧";
$iphone = "哎呦，苹果最配您高大上的气质了！";
$samsung = "可以啊，三星绝对的贵族机，最适合你了";
$mi = "小米手机，销量第一哦";
$huawei = "华为，这可是民族的骄傲啊";
$meizu = "这手机性价比太高了";
$event = $postObj->Event;
$key=$Msg->match($rule,$keyword);
switch ( $key )
{
        case '目录':
            $Msg->txtMsg($fromUsername,$toUsername,$index);
            exit();
        case '你好':
            $Msg->txtMsg($fromUsername,$toUsername,$hello);
            exit();
        case '店铺':
            $Msg->txtMsg($fromUsername,$toUsername,$shop);
            exit();
        case '报价':
            $Msg->picMsg($fromUsername,$toUsername,$title,$PicUrl,$Discription,$Url);
            exit();
        case '活动':
            $Msg->txtMsg($fromUsername,$toUsername,$activity);
            exit();
        case '手机':
            $Msg->txtMsg($fromUsername,$toUsername,$phone);
            exit();
        case '港版':
            $Msg->txtMsg($fromUsername,$toUsername,$hk);
            exit();
        case '日版':
            $Msg->txtMsg($fromUsername,$toUsername,$japan);
            exit();
        case '美版':
            $Msg->txtMsg($fromUsername,$toUsername,$america);
            exit();
        case '韩版':
            $Msg->txtMsg($fromUsername,$toUsername,$korea);
            exit();
        case '国行':
            $Msg->txtMsg($fromUsername,$toUsername,$china);
            exit();
        case '苹果':
            $Msg->txtMsg($fromUsername,$toUsername,$iphone);
            exit();
        case '三星':
            $Msg->txtMsg($fromUsername,$toUsername,$samsung);
            exit();
        case '小米':
            $Msg->txtMsg($fromUsername,$toUsername,$mi);
            exit();
        case '华为':
            $Msg->txtMsg($fromUsername,$toUsername,$huawei);
            exit();
        case '魅族':
            $Msg->txtMsg($fromUsername,$toUsername,$meizu);
            exit();
        default:
            $Msg->txtMsg($fromUsername,$toUsername,$default);
}
/* class of msg */
class Msg
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature())
        {
         echo $echoStr;
         exit;
        }
    }
    function event($event)
    {
        if ($event =="subscribe")
            {
                $Msg->txtMsg($fromUsername,$toUsername,$welcome);
            }
    }
    function picMsg($fromUsername,$toUsername,$title,$PicUrl,$Discription,$Url)
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $time = time();
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <Content> </Content>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                        <item><Title><![CDATA[%s]]></Title>
                        <Discription><![CDATA[%s]]></Discription>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url></item>
                        </Articles>
                        <FuncFlag>1</FuncFlag>
                        </xml> ";
        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$title,$Discription,$PicUrl,$Url);
        echo $resultStr;
    }
    function txtMsg($fromUsername,$toUsername,$contentStr)
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $time = time();
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0<FuncFlag>
                        </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
        echo $resultStr;
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
        if( $tmpStr == $signature )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function match($rule,$keyword)
    {
        $num=count($rule)+1;
        for($i=0;$i<$num;$i++)
        {
            if(preg_match("/$rule[$i]/",$keyword))
            {
                return $rule[$i];
                exit();
            }
        }
    }
}
?>