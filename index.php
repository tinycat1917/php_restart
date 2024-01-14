<?php

    session_start();
    include_once 'captchCode.php';
    include_once 'sigin.php';

    class controller{

        // 设置时间间隔和最大访问次数
        private $timeInterval = 60; // 60秒
        private $maxRequests = 10; // 10次
        protected $errMessage;
        protected $captchCode;

        public function __construct(){
            $this->check();
        }

        public function check(){
            // 检查请求，如果请求过于频繁直接返回错误信息
            if(!$this->limit()){
                $this->response($this->errMessage);
                return;
            }
            // 检查请求类型
            if($_GET['action'] == 'getCaptch'){
                $this->getCaptch();
                return;
            }
            if($_GET['action'] == 'sigin'){
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $this->sigin();
                    return;
                }else{
                    $this->response(array(
                        'code' => '404',
                        'message' => '请求方式错误!'
                    ));
                }
            }
            else{
                //没有匹配到路由则返回index.html
                header('Location: index.html');
            }
            return;
        }

        // 检测访问频率，频率过高则限制访问
        public function limit(){
            // 检查是否存在访问次数计数器
            if (!isset($_SESSION['api_requests'])) {
                $_SESSION['api_requests'] = 1;
                $_SESSION['api_last_request_time'] = time();
            }else{
                // 检查时间间隔是否已过
                $currentTime = time();
                $lastRequestTime = $_SESSION['api_last_request_time'];
                $elapsedTime = $currentTime - $lastRequestTime;
            
                if ($elapsedTime >= $this->timeInterval) {
                    // 时间间隔已过，重置计数器
                    $_SESSION['api_requests'] = 1;
                    $_SESSION['api_last_request_time'] = $currentTime;
                } else {
                    // 时间间隔未过，增加计数器
                    $_SESSION['api_requests']++;
                    // 检查是否超过最大访问次数
                    if ($_SESSION['api_requests'] > $this->maxRequests) {
                        // 返回错误信息或执行其他操作
                        $this->errMessage = array(
                            'code' => '404',
                            'message' => '请求过于频繁,请稍后再试!'
                        );
                        return false;
                    }
                }
            }
            return true;
        }

        public function sigin(){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $captchCode = $_POST['captchCode'];
            // 检查验证码是否正确
            if($captchCode != $_SESSION['captchCode']){
                $this->response(array(
                    'code' => '404',
                    'message' => '验证码错误!',
                    'captchCode' => $_SESSION['captchCode'],
                ));
                return false;
            }

            $sigin = new sigin($username, $password, $email);
            if($sigin->sigin()){
                $this->response(array(
                    'code' => '200',
                    'message' => '注册成功!'
                ));
                unset($_SESSION['captchCode']);
                return true;
            }else{
                $this->response(array(
                    'code' => '404',
                    'message' => $sigin->message
                ));
                return false;
            }
        }

        public function getCaptch(){
            // 生成验证码
            $captcha = new captch();
            $this->captchCode = $captcha->getCaptchImage();
            $_SESSION['captchCode'] = $this->captchCode;
        }

        public function response($data){
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }

    $controller = new controller();
