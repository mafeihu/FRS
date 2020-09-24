<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/14
 * Time: 9:26
 */
namespace app\api\controller;
use PHPMailer\PHPMailer\PHPMailer;
use think\Controller;
class Api extends HttpCurl
{
    /**
     * 发送邮件
     */
    public  function sendEmail($data = [])
    {
        $mail = new PHPMailer();//实例化
        $mail->isSMTP(); // 启用SMTP
        $mail->Host = 'smtp.qq.com'; //SMTP服务器 以qq邮箱为例子
        $mail->Port = 465;  //邮件发送端口
        $mail->SMTPAuth = true;  //启用SMTP认证
        $mail->SMTPSecure = "ssl";   // 设置安全验证方式为ssl
        $mail->CharSet = "UTF-8"; //字符集
        $mail->Encoding = "base64"; //编码方式
        $mail->Username = '1109091542@qq.com';  //发件人邮箱
        $mail->Password = 'zeppnzjlkrvejjhg';  //发件人密码 ==>重点：是授权码，不是邮箱密码
        $mail->Subject = '员工超时未归信息'; //邮件标题
        $mail->From = '1109091542@qq.com';  //发件人邮箱
        $mail->FromName = '****公司';  //发件人姓名

        $data['user_email'] = 'rltest@ssic.cn';//
        $data['content'] = '邮件主题内容';
        if($data && is_array($data))
        {
            $mail->AddAddress($data['user_email']); //添加收件人
            $mail->IsHTML(true); //支持html格式内容
            $mail->Body = $data['content']; //邮件主体内容
            $mail->send();
            //发送成功就删除
            if ($mail->ErrorInfo)
            {
                echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息,用以邮件发送不成功问题排查
                return 1;
            }
            else
            {
                return -1;
            }
        }
    }
}