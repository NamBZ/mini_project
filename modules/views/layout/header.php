<!DOCTYPE html>
<html lang="en-US">
<head><!--[if IE]><![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta name="HandheldFriendly" content="true">
<meta name="MobileOptimized" content="width">
<meta name="copyright" content="Copyright 2022 Nam.Name.Vn - All rights Reserved." />
<meta name="robots" content="noodp,index,follow" />
<meta name='revisit-after' content='1 days' />
<meta name="language" content="Vietnamese" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>/publics/css/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/publics/css/mod.css" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>/publics/images/favicon.ico" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<title><?php echo $meta['title']; ?></title>
<meta name="description" content="<?php echo $meta['description']; ?>" />
<meta property="og:locale" content="vi_VN" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $meta['title']; ?>" />
<meta property="og:description" content="<?php echo $meta['description']; ?>" />

<?php
    if (!empty($_SESSION['alert'])) {
        echo '<script>$(document).ready(function(){ toastr.success("'.$_SESSION['alert'].'"); });</script>';
        unset($_SESSION['alert']);
    }
?>
</head>

<body style="max-width:1200px; margin:0 auto; background-color: #fff;">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><i class="fa fa-gamepad"></i>MyBlog</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <?php if (!isLogin()) { ?>
                        <li><a href="<?php echo base_url(); ?>/user/register"><i class="fa fa-plus"></i> Đăng Ký</a></li>
                        <li><a href="<?php echo base_url(); ?>/user/login"><i class="fa fa-sign-in"></i> Đăng Nhập</a></li>
                        <li><a href="<?php echo base_url(); ?>/user/remember"><i class="fa fa-question"></i>
                                Quên MK?</a></li>
                    <?php } else { ?>
                        <?php if ($_SESSION["level"] == 9) { ?>
                            <li><a href="<?php echo base_url(); ?>/admin/"><i class="fa fa-user"></i> Quản trị</a></li>
                        <?php } else { ?>
                            <li><a href="<?php echo base_url(); ?>/admin/posts"><i class="fa fa-plus"></i> Tạo bài viết</a></li>
                        <?php } ?>
                        <li class="dropdown" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="caret"></span><?php echo ' '.$_SESSION['username'];?></a>
                            <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo base_url(); ?>/user/change_info"><i class="fa fa-cog"></i> Chỉnh sửa profile</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url(); ?>/user/logout"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php }  ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">