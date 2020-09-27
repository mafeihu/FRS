<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/24
 * Time: 16:33
 */
namespace app\api\controller;
use PHPMailer\PHPMailer\PHPMailer;
use think\Controller;
class BaseApi extends Controller
{
    /**
     * @param $config 发送邮件配置信息
     * @param string $content 发送邮件内容
     * @return int
     */
    protected function sendEmail($config,$content='')
    {
        $mail = new PHPMailer();//实例化
        $mail->isSMTP(); // 启用SMTP
        $mail->Host = 'smtp.263.net'; //SMTP服务器 以qq邮箱为例子
        $mail->Port = 465;  //邮件发送端口
        $mail->SMTPAuth = true;  //启用SMTP认证
        $mail->SMTPSecure = "ssl";   // 设置安全验证方式为ssl
        $mail->CharSet = "UTF-8"; //字符集
        $mail->Encoding = "base64"; //编码方式
        $mail->Username = $config['from_mail'];  //发件人邮箱
        $mail->Password = $config['password'];  //发件人密码 ==>重点：是授权码，不是邮箱密码
        $mail->Subject = $config['subject']; //邮件标题
        $mail->From = $config['from_mail'];  //发件人邮箱
        $mail->FromName = $config['from_name'];  //发件人姓名
        $mail->AddAddress($config['user_email']); //添加收件人
        $mail->IsHTML(true); //支持html格式内容
        $mail->Body = $content; //邮件主体内容
        $mail->send();
        //发送成功就删除
        if ($mail->ErrorInfo)
        {
            $log_file = date('YmdH').'-mail.txt';
            $message = "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息,用以邮件发送不成功问题排查
            $this->logCreate($log_file,$message);
            return false;

        }
        else
        {
            return true;
        }
    }

    //日志文件
    protected function logCreate($file,$log_str)
    {
        //创建目录
        $directory = 'log/'.date('Ymd');
        if(!is_dir($directory))
        {
            $result = mkdir(iconv("UTF-8", "GBK", $directory),0777,true);
            if($result)
            {
                $file_path = $directory.'/'.$file;
                file_put_contents($file_path, date("Y-m-d H:i:s").' w :'.$log_str.' ; '.PHP_EOL, FILE_APPEND);
            }
        }
        else
        {
            $file_path = $directory.'/'.$file;
            file_put_contents($file_path, date("Y-m-d H:i:s").' w :'.$log_str.' ; '.PHP_EOL, FILE_APPEND);
        }
    }
}
