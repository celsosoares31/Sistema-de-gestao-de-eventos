<?php

namespace App\class\controller;

abstract class Controller{
    protected static function isLoggedIn(){
        if (!isset($_SESSION['id'])){
            header("Location: ../../../../index.php");
        }
    }
    protected static function isAllowed($id){
        if (isset($_SESSION['id'])){

            if($_SESSION['id'] != $id){
                header("Location: index.php");
                exit;
            }
        }
    }
    protected function rrmdir($src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src.'/'.$file;
                if (is_dir($full)) {
                    $this->rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}