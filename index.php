<?php

//定义自己的微信TOKEN
define("TOKEN", "coyqiuhaowechat1234");

$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
class wechatCallbackapiTest
{



    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //验证signature
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    //验证签名
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }


    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                default:
                    $result = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
    private function receiveEvent($object)
    {
        include 'Curl.php';
        $curl=new Curl();
        $fromusername = $object->FromUserName;
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "欢迎关注昊祥科技有限公司 ";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "MOBILE": //用户发送手机号码
                        $url="http://localhost/blog/index.php/Api/view/1" ;
                        $curl->get($url);
                        $content =  "尊敬的用户，您好！欢迎加入【昊祥科技有限公司】会员俱乐部，请回复您的手机号码，完成身份绑定。";
                        break;
//                    case 'MOBILE'://用户发送手机号码
//                        $content="您的手机号码是：";
//                        break;
//                    case 'CODE'://用户接收验证码
//                        if ($code==$keyword) {
//                            $content="验证码输入正确";
//                        }else{
//                            $content="验证码输入错误，请重新输入";
//                        }
//                    case 'LIST'://用户信息绑定
//                        if (存入成功) {
//                            $content="验证码已存入数据库中";
//                        }else{
//                            $content="验证码没能成功存入数据库，请重新生成验证码";
//                        }
//                    case 'SENDCODE'://发送验证码
//                        if (手机号码规则正确) {
//                            $content="手机号码规则正确，回复1确认绑定此手机号码".$phone.",重新输入请按2";
//                        }
//                        break;
//                    case 'CHECKMOBILE'://验证验证码
//                        if (已经绑定了){
//                        $content="尊敬的用户，你的微信号码已经绑定了手机";
//                    }else{
//                        $content="请输入你的手机号码进行绑定会员";
//                    }
//                        break;
//                    default:
//                        $content = "点击菜单：".$object->EventKey;
//                        break;
                }
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;

        }
        $result = $this->transmitText($object, $content);
        return $result;
    }
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|18[0-9]{9}$/",$keyword)){
            $_SESSION['phone']=$keyword;
            $content="回复2确认手机号码";
        }else{
            switch ($keyword) {
                case '2':
                    $content=$_SESSION['phone'];
                    break;

                default:
                    $content=$_SESSION['phone'];
                    break;
            }
        }
        //     $content = "尊敬的用户，您已确认需绑定的手机号码，我们将发送验证码至该手机号码，请您于5分钟内微信回复您收到的验证码，以确认绑定。";

        //     $code=$this->code();
        //     $time=time();

        //     $arr = array();
        //     //$arr['phone']=$_SESSION['phone'];
        //     $arr['openid'] = $object->FromUserName;
        //     $arr['code']=$code;
        //     $this->dbInsert('user',$arr);


        //     if ($num) {
        //         $time1=time();
        //         $time2=$time1-$time;

        //         if($time2>5*60){
        //             $content="验证码已超时，请您重新获取验证码！重新获取验证码请输入..(输入关键字)";

        //             if (trim($object->Content)=='关键字') {
        //                 $this->code();//重新生成验证码
        //             }
        //         }else{
        //             //调用selectCode函数
        //             $dbcode=$this->selectCode($object->FromUserName);
        //             if (trim($object->Content)==$dbcode) {
        //                 $content="您的验证码输入正确，您的手机成功绑定本公众平台";
        //                 $data=array();
        //                 $data['phone']=$_SESSION['phone'];
        //                 //$data['code']=$code;
        //                 $data['flag'] = 1;
        //                 $data['openid'] = $object->FromUserName;
        //                 $this->dbInsert('user',$data);
        //            }else{
        //                 $content="对不起，您的验证码输入有问题，请您重新绑定......";
        //            }
        //         }

        //     }

        //     }

        $result = $this->transmitText($object, $content);

        return $result;
    }
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_BAE_ENV_APPID'])){   //BAE
            require_once "BaeLog.class.php";
            $logger = BaeLog::getInstance();
            $logger ->logDebug($log_content);
        }else if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 10000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){
                unlink($log_filename);
            }
            file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
}
phpinfo():

?>