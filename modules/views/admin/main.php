<div class="panel panel-default">
	<div class="panel-heading">
		<b>Admin Panel</b>
	</div>
	<div class="list-group">
		<div class="list-group-item">
			<i class="fa fa-plus-square" aria-hidden="true"></i>
			<a href="<?php echo base_url(); ?>/admin/posts" title="Đăng Bài Viết Mới"><span>Đăng Bài Viết Mới</span></a>
		</div>
		<?php if($_SESSION['level'] == 9) { ?>
		<div class="list-group-item">
			<i class="fa fa-book" aria-hidden="true"></i>
			<a href="<?php echo base_url(); ?>/admin/blog" title="Quản lý bài viết"><span>Quản lý bài viết</span></a>
		</div>
		<div class="list-group-item">
			<i class="fa fa-th-list" aria-hidden="true"></i>
			<a href="<?php echo base_url(); ?>/admin/category" title="Quản Lý Chuyên Mục"><span>Quản Lý Chuyên Mục</span></a>
		</div>
		<?php } ?>
		<div class="list-group-item">
			<i class="fa fa-user" aria-hidden="true"></i>
			<a href="<?php echo base_url(); ?>/user/change_info" title="Thông tin User"><span>Thông tin User</span></a>
		</div>
	</div>
</div>