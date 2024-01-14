<?php

    class Sigin{

        var $username;
        var $password;
        var $email;
        var $message;
        var $debug = false;

        public function __construct($username, $password, $email){
            $this->username = $username;
            $this->password = $password;
            $this->email = $email;
        }

        public function sigin(){
            if(!$this->checkUserName() || !$this->checkPassword() || !$this->checkEmail()){
                return false;
            }else{
                if($this->addData()){
                    return true;
                }else{
                    // debug模式提示的更多
                    $this->message = $this->debug ? '数据库写入失败' : '注册失败';
                    return false;
                }
            }
        }

        public function checkUserName(){
            //检测用户名是否合法,使用preg_match函数进行匹配
            $pattern = '/^[a-zA-Z][a-zA-Z0-9_]{3,15}$/';
            if (!preg_match($pattern, $this->username)) {
                $this->message = '用户名不合法';
                return false; // 非法用户名
            }
            //检测用户名是否存在
            /**
             * 数据库查询,略
             */
            return true;
        }

        public function checkPassword() {
            //检测密码是否合法
            $pattern = '/^[a-zA-Z0-9_]{6,16}$/';
            if (!preg_match($pattern, $this->password)) {
                $this->message = '密码不合法';
                return false; // 非法密码
            }
            return true;
        }

        public function checkEmail(){
            //检测邮箱是否合法
            $pattern = '/^[a-zA-Z0-9_]+@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)+$/';
            if (!preg_match($pattern, $this->email)) {
                $this->message = '邮箱不合法';
                return false; // 非法邮箱
            }
            return true;
        }

        public function addData(){
            // 将数据添加到数据库
            /**
             * $this->username
             * $this->password
             * $this->email
             * 略
             */
            return true;
        }

    }
