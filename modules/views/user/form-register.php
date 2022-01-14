<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Đăng nhập </h3>
    </div>
    <div class="panel-body">
        <form action="<?php echo base_url(); ?>/user/register" method="post">
            <div class="form-group">
                <label for="fullname">Họ và tên</label> 
                <input class="form-control" type="text" name="fullName" id="fullname" value="">
            </div>
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input class="form-control" type="text" name="username" id="username" value="">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input class="form-control" type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="repassword">Nhập lại mật khẩu</label>
                <input class="form-control" type="password" name="rePassword" id="repassword">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="text" name="email" id="email" value="">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="login" value="Đăng Ký">
            </div>
        </form>
    </div>
</div>