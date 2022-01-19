<div class="col-sm-9">

<?php
if(empty($data['content'])){
    show_alert(3,array('Bài viết không tồn tại'));
}else{
    $arrBreadcrumb = array(
                        array(
                            'link'  => base_url().'/category/'.$data['url_cate'],
                            'title' => $data['category']
                        )
                    );
    echo breadcrumb($arrBreadcrumb);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title topic-name">
            <span class="label label-default"><?php echo $data['category']; ?></span> 
            <a href="<?php echo base_url().'/'.$data['slug']; ?>.html" title="<?php echo $data['title']; ?>"><?php echo $data['title']; ?></a>
        </h1>
    </div>
    <div class="list-group">
        <div class="list-group-item">
            <i class="fa fa-pencil-square-o"></i> Tác giả: <b><span style="color:red;"><?php echo $data['username']; ?></span></b> <br />
            <i class="fa fa-clock-o"></i> Thời gian: <?php echo convertTimeToString($data['times']); ?>
        </div>
        <?php if(isLogin() && ($data['id_author'] == $_SESSION['id'] || $_SESSION['level'] > 5)) { ?>
        <div class="list-group-item">
            <a class="btn btn-primary" href="<?php echo base_url(); ?>/admin/editpost/<?php echo $data['post_id']; ?>" title="Chỉnh sửa bài viết"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>

            <a class="btn btn-danger" href="<?php echo base_url(); ?>/admin/deletepost/<?php echo $data['post_id']; ?>" title="Xóa bài viết"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>

            <?php if($data['status'] == 0 && $_SESSION['level'] == 9){ ?>                    
                <a class="btn btn-success" href="<?php echo base_url(); ?>/admin/status/<?php echo $data['slug']; ?>/public" title="Phê duyệt bài viết"><i class="fa fa-check-square-o" aria-hidden="true"></i> Public</a>
            <?php }else if ($data['status'] == 1 && $_SESSION['level'] == 9){ ?>
                    
                    <a class="btn btn-default" href="<?php echo base_url(); ?>/admin/status/<?php echo $data['slug']; ?>/hide" title="Ẩn bài viết"><i class="fa fa-minus-square-o" aria-hidden="true"></i> Unpublic</a>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="list-group-item">
            <?php echo stripslashes($data['content']); ?>
        </div>
        <div class="list-group-item">
            Tags: <?php echo $data['tags']; ?>
        </div>

    </div>
</div>

</div>

<div class="col-sm-3">



<!--kết thúc show nội dung bài viết-->

<?php } ?>