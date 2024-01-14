<?php

    class captch{

        private $width = 160; // 4的倍数
        private $height = 40;
        private $font = "simkai.ttf";
        private $fontsize = 24;
        private $image;
        private $captch_code = "";

        public function __construct(){
            $this->image = imagecreatetruecolor($this->width, $this->height);
            //为$image设置背景颜色为白色
            $bgcolor = imagecolorallocate($this->image, 255, 255, 255);
            //填充背景颜色
            imagefill($this->image, 0, 0, $bgcolor);
        }

        public function getCaptchImage(){
            $this->drawText();
            $this->drawLine();
            $this->drawPoint();
            //设置header图片格式为png
            header('content-type:image/png');
            //显示图片
            imagepng($this->image);
            //destory
            imagedestroy($this->image);
            return $this->captch_code;
        }

        public function drawText(){
            for($i=0; $i<4; $i++){
                $fontcolor=imagecolorallocate($this->image, rand(0,120), rand(0,120), rand(0, 120));
                $data="1234567890abcdefghigklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ";
                //设置每次产生的字符从$data中每次截取一个字符
                $fontcontent=substr($data, rand(0,strlen($data)), 1);
                //让产生的四个字符拼接起来
                $this->captch_code.=$fontcontent;
                //控制每次出现的字符的坐标防止相互覆盖即x->left y->top
                $x=($i*$this->width/4) + rand(5, 10);
                $y=rand(26, 30);
                //此函数用来将产生的字符在背景图上画出来
                imagettftext($this->image, $this->fontsize, 0, $x,$y, $fontcolor, $this->font, $fontcontent);
            }
        }

        // 为图片添加干扰线
        public function drawLine(){
            //产生2条干扰线
            for ($i=0; $i <2 ; $i++) {
                //干扰线的颜色
                $linecolor=imagecolorallocate($this->image, rand(80, 220), rand(80, 220), rand(80, 220));
                //画出每条干扰线
                imageline($this->image, rand(1, $this->width - 1), rand(1, $this->height - 1), rand(1, $this->width - 1), rand(1,$this->height - 1), $linecolor);
            }
        }

        // 为图片添加干扰点
        public function drawPoint(){
            for($i=0; $i<100; $i++){
                //干扰点的颜色
                $pointcolor=imagecolorallocate($this->image, rand(50,200), rand(50, 200), rand(50, 200));
                //该函数用来把每个干扰点在背景上描绘出来
                imagesetpixel( $this->image, rand(1, $this->width - 1), rand(1,$this->height - 1), $pointcolor);
            }
        }

    }

    // $a = new captch();
    // $a->getCaptchImage();
