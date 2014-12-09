<?php
class checkMobileController extends Controller{
    private function checkMobile($phone){
        if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|18[0-9]{9}$/",$phone)){
            $content = "您的手机号码为：".$phone.",确认请回复【1】。如果有误，请重新点击【会员专区】之【手机绑定】";
        }else{
            $content = "对不起，您输入的手机号码格式不正确。如需绑定请重新点击【会员专区】之【手机绑定】";
        }
        return $content;
    }
}