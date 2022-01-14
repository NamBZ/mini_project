<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">Đăng nhập </div>
    </div>
    <div class="panel-body">
        <form action="<?php echo base_url(); ?>/user/login" method="post">
            <div class="form-group">
                <label for="username">Tài khoản</label>
                <input class="form-control" type="text" id="username" name="username" value="" placeholder="Nhập tài khoản">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input class="form-control" type="password" id="password" name="password" value="" placeholder="Nhập mật khẩu">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="saveLogin" name="saveLogin">
                <label class="form-check-label" for="saveLogin">Ghi nhớ đăng nhập</label>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </form>
    </div>
</div>