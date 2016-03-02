<?php

class Upload
{
    protected $img;
    protected $types = array(
        1 => 'gif',
        2 => 'jpg',
        3 => 'png',
    );

    public function __construct($img = '')
    {
        !$img && $this->param($img);
    }

    public function param($img)
    {
        $this->img = $img;
        return $this;
    }

    public function getImageInfo($img)
    {
        $info = @getimagesize($img);
        if (isset($this->types[$info[2]])) {
            $info['ext'] = $info['type'] = $this->types[$info[2]];
        } else {
            $info['ext'] = $info['type'] = 'jpg';
        }
        $info['type'] == 'jpg' && $info['type'] = 'jpeg';
        $info['size'] = @filesize($img);
        return $info;
    }

    public function thumb($filename, $new_w = 160, $new_h = 120, $cut = 0, $big = 0)
    {
        $info = $this->getImageInfo($this->img);
        if (!empty($info[0])) {
            $old_w = $info[0];
            $old_h = $info[1];
            $type = $info['type'];
            $ext = $info['ext'];
            unset($info);
            if ($old_w < $new_h && $old_h < $new_w && !$big) {
                return false;
            }
            if ($cut == 0) {
                $scale = min($new_w / $old_w, $new_h / $old_h);
                $width = (int)($old_w * $scale);
                $height = (int)($old_h * $scale);
                $start_w = $start_h = 0;
                $end_w = $old_w;
                $end_h = $old_h;
            } elseif ($cut == 1) {
                $scale1 = round($new_w / $new_h, 2);
                $scale2 = round($old_w / $old_h, 2);
                if ($scale1 > $scale2) {
                    $end_h = round($old_w / $scale1, 2);
                    $start_h = ($old_h - $end_h) / 2;
                    $start_w = 0;
                    $end_w = $old_w;
                } else {
                    $end_w = round($old_h * $scale1, 2);
                    $start_w = ($old_w - $end_w) / 2;
                    $start_h = 0;
                    $end_h = $old_h;
                }
                $width = $new_w;
                $height = $new_h;
            } elseif ($cut == 2) {
                $scale1 = round($new_w / $new_h, 2);
                $scale2 = round($old_w / $old_h, 2);
                if ($scale1 > $scale2) {
                    $end_h = round($old_w / $scale1, 2);
                    $end_w = $old_w;
                } else {
                    $end_w = round($old_h * $scale1, 2);
                    $end_h = $old_h;
                }
                $start_w = 0;
                $start_h = 0;
                $width = $new_w;
                $height = $new_h;
            }
            $createFun = 'ImageCreateFrom' . $type;
            $oldimg = $createFun($this->img);
            if ($type != 'gif' && function_exists('imagecreatetruecolor')) {
                $newimg = imagecreatetruecolor($width, $height);
            } else {
                $newimg = imagecreate($width, $height);
            }
            if (function_exists("ImageCopyResampled")) {
                ImageCopyResampled($newimg, $oldimg, 0, 0, $start_w, $start_h, $width, $height, $end_w, $end_h);
            } else {
                ImageCopyResized($newimg, $oldimg, 0, 0, $start_w, $start_h, $width, $height, $end_w, $end_h);
            }
            $type == 'jpeg' && imageinterlace($newimg, 1);
            $imageFun = 'image' . $type;
            !@$imageFun($newimg, $filename) && die('Check Dir');
            ImageDestroy($newimg);
            ImageDestroy($oldimg);
            return $filename;
        }
        return false;
    }

    public function water($filename, $water, $pos = 0, $pct = 30)
    {
        $info = $this->getImageInfo($water);
        if (!empty($info[0])) {
            $water_w = $info[0];
            $water_h = $info[1];
            $type = $info['type'];
            $fun = 'imagecreatefrom' . $type;
            $waterimg = $fun($water);
        } else {
            return false;
        }
        $info = $this->getImageInfo($this->img);
        if (!empty($info[0])) {
            $old_w = $info[0];
            $old_h = $info[1];
            $type = $info['type'];
            $fun = 'imagecreatefrom' . $type;
            $oldimg = $fun($this->img);
        } else {
            return false;
        }
        $water_w > $old_w && $water_w = $old_w;
        $water_h > $old_h && $water_h = $old_h;
        switch ($pos) {
            case 0:
                $posX = rand(0, ($old_w - $water_w));
                $posY = rand(0, ($old_h - $water_h));
                break;
            case 1:
                $posX = 0;
                $posY = 0;
                break;
            case 2:
                $posX = ($old_w - $water_w) / 2;
                $posY = 0;
                break;
            case 3:
                $posX = $old_w - $water_w;
                $posY = 0;
                break;
            case 4:
                $posX = 0;
                $posY = ($old_h - $water_h) / 2;
                break;
            case 5:
                $posX = ($old_w - $water_w) / 2;
                $posY = ($old_h - $water_h) / 2;
                break;
            case 6:
                $posX = $old_w - $water_w;
                $posY = ($old_h - $water_h) / 2;
                break;
            case 7:
                $posX = 0;
                $posY = $old_h - $water_h;
                break;
            case 8:
                $posX = ($old_w - $water_w) / 2;
                $posY = $old_h - $water_h;
                break;
            case 9:
                $posX = $old_w - $water_w;
                $posY = $old_h - $water_h;
                break;
            default:
                $posX = rand(0, ($old_w - $water_w));
                $posY = rand(0, ($old_h - $water_h));
                break;
        }
        imagealphablending($oldimg, true);
        imagecopymerge($oldimg, $waterimg, $posX, $posY, 0, 0, $water_w, $water_h, $pct);
        $fun = 'image' . $type;
        !@$fun($oldimg, $filename) && die('Check dir!');
        imagedestroy($oldimg);
        imagedestroy($waterimg);
        return $filename;
    }
}