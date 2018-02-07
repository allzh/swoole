<?php
namespace App\Controller;

use Swoole\Client\CURL;

class Page extends \Swoole\Controller
{
    function index()
    {
        $this->session->start();
        if (!empty($_SESSION['isLogin']))
        {
            chatroom:
            $this->http->redirect('/page/chatroom/');
            return;
        }

        if (!empty($_GET['token']))
        {
            $curl = new CURL();
            //$user = $curl->get($this->config['login']['get_user_info'] . '?token=' . urlencode($_GET['token']));
            $tokenUrl = $this->config['login']['get_user_info'] . '?token=' . urlencode($_GET['token']);
            //$user = file_get_contents($tokenUrl);
            $nickname = uniqid();
            $username = md5($nickname);
            $user = '{
	"username": "'.$username.'",
	"nickname": "'.$nickname.'",
	"avatar": "http://qzapp.qlogo.cn/qzapp/221403/00B00E102B5BDB994626DE606CB3FE15/100",
	"birth_year": "1989",
	"province": "江西",
	"city": "南昌",
	"sex": 1,
	"id": 4513
}';
            if (empty($user))
            {
                login:
                $this->http->redirect($this->config['login']['passport'] . '?return_token=1&refer=' . urlencode($this->config['webim']['server']['origin']));
            }
            else
            {
                $_SESSION['isLogin'] = 1;
                $_SESSION['user'] = json_decode($user, true);
                goto chatroom;
            }
        }
        else
        {
            goto login;
        }
    }

    function chatroom()
    {
        $this->session->start();
        if (empty($_SESSION['isLogin']))
        {
            $this->http->redirect('/page/index/');
            return;
        }
        $user = $_SESSION['user'];
        $this->assign('user', $user);
        $this->assign('debug', 'true');
        $this->display('page/chatroom.php');
    }

    /**
     * 用flash添加照片
     */
    function upload()
    {
        if ($_FILES)
        {
            global $php;
            $php->upload->thumb_width = 136;
            $php->upload->thumb_height = 136;
            $php->upload->thumb_qulitity = 100;
            $up_pic = $php->upload->save('Filedata');
            if (empty($up_pic))
            {
                echo '上传失败，请重新上传！ Error:' . $php->upload->error_msg;
            }
            echo json_encode($up_pic);
        }
        else
        {
            echo "Bad Request\n";
        }
    }
}