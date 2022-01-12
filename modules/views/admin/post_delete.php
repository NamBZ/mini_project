<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title">Xác nhận xóa</h3>
    </div>
    <div class="panel-body">
        Bạn muốn xóa <strong><?php echo $data ['title'] ?></strong> ???
    </div>
    <div class="panel-footer">
        <form action="<?php echo base_url(); ?>/admin/deletepost/<?php echo $data['id'] ?>" method="post">
            <button type="submit" class="btn btn-danger" name="delete">Xác nhận</button>
            <button type="button" class="btn btn-default" onclick="window.location.href ='<?php echo base_url()?>/<?php echo $data['slug'] ?>.html';">Hủy</button>
        </form>
    </div>
</div>