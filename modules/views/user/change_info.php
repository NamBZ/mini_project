<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Thay đổi thông tin tài khoản </h3>
    </div>
    <div class="panel-body">
        <form action="<?php echo base_url(); ?>/user/change_info" method="post">
            <div class="form-group">
              <label for="">Họ Và Tên</label> 
              <input class="form-control" type="text" name="fullName" value="<?php echo $data['full_name'] ?>">
            </div>
            <div class="form-group">
              <label for="">Email </label> 
              <input class="form-control" type="text" name="email" value="<?php echo $data['email'] ?>">
            </div>
            <div class="form-group">
              <label for="">Mật Khẩu Cũ</label>
              <input class="form-control" type="password" name="password_old">
            </div>
            <div class="form-group">
              <label for="">Mật Khẩu Mới</label>
              <input class="form-control" type="password" name="password_new">
            </div>
            <div class="form-group">
              <label for="">Nhập Lại Mật Khẩu mới</label>
              <input class="form-control" type="password" name="rePassword_new">
            </div>
            <div class="form-group">
              
              <input class="btn btn-primary" type="submit" name="change_info" value="Lưu Thay Đổi">
            </div>
        </form>
    </div>
    <div class="alert alert-info" style="margin: 0;">
      Nếu không muốn thay đổi mật khẩu, hãy để trống 3 trường cuối!
    </div>
</div>