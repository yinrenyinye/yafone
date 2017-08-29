<?php

/**
 * @name UserController
 * @author micle
 * @desc 用户控制器
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserController extends Yaf_Controller_Abstract
{

    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yafone/index/index/index/name/root 的时候, 你就会发现不同
     */
    public function indexAction($name = "Stranger")
    {

        if (!empty($this->getRequest()->getQuery("page"))) {
            $page = intval($this->getRequest()->getQuery("page"));
        } else {
            $page = 1;
        }
        if ($page <= 0) $page = 1;

        $user = new UserModel();

        $total = $user->count();

        $pagesize = 5;

        $pagetotal = ceil($total['total'] / $pagesize);

        if ($page > $pagetotal) $page = $pagetotal;

        $offset = ($page - 1) * $pagesize;

        $data = $user->get_list($pagesize, $offset);

        if (1 < $pagetotal) {
            $pagination = new Common_Pagination();

            $pagination->config([
                'base_url' => 'http://yaf.brightdh.com/user/index',
                'pagetotal' => $pagetotal,
                'cur_page' => $page
            ]);

            $page_link = $pagination->create_links('html');
            var_dump($page_link);
        }

        var_dump($data);
        return false;
    }

    public function sendMailAction()
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.163.com;';                        // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'miclefengzss@163.com';             // SMTP username
            $mail->Password = 'Zss5494946';                       // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Recipients
            $mail->setFrom('miclefengzss@163.com', 'Micle');
            $mail->addAddress('1280054628@qq.com', 'MicleFeng');     // Add a recipient

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        return false;
    }
}
