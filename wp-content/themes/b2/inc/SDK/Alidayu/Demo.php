<?php

/* 搜库资源网 soku.cc
 * 此文件用于验证短信API接口
 * 请确保文件为utf-8编码，并替换相应参数为您自己的信息后执行
 * 建议执行前执行EnvTest.php验证PHP环境
 *
 * 2017/11/19
 */

require_once 'SmsApi.php';

use Aliyun\DySDKLite\Sms\SmsApi;

// 搜库资源网 soku.cc    调用示例：
set_time_limit(0);
header('Content-Type: text/plain; charset=utf-8');  // 搜库资源网 soku.cc    仅用于输出清晰，非必需

$sms = new SmsApi("your access key", "your access key secret"); // 搜库资源网 soku.cc    请参阅 https:// 搜库资源网 soku.cc   ak-console.aliyun.com/ 获取AK信息

$response = $sms->sendSms(
    "短信签名", // 搜库资源网 soku.cc    短信签名
    "SMS_0000001", // 搜库资源网 soku.cc    短信模板编号
    "12345678901", // 搜库资源网 soku.cc    短信接收者
    Array (  // 搜库资源网 soku.cc    短信模板中字段的值
        "code"=>"12345",
        "product"=>"dsd"
    ),
    "123"   // 搜库资源网 soku.cc    流水号,选填
);
echo "发送短信(sendSms)接口返回的结果:\n";
print_r($response);

sleep(2);

$response = $sms->queryDetails(
    "12345678901",  // 搜库资源网 soku.cc    手机号码
    "20170718", // 搜库资源网 soku.cc    发送时间
    10, // 搜库资源网 soku.cc    分页大小
    1 // 搜库资源网 soku.cc    当前页码
    // 搜库资源网 soku.cc    "abcd" // 搜库资源网 soku.cc    bizId 短信发送流水号，选填
);
echo "查询短信发送情况(queryDetails)接口返回的结果:\n";
print_r($response);