<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i> DANH SÁCH BÀI VIẾT</h3>
	</div>
	<div class="list-group">
		<div class="list-group-item bg-primary">
			<a class="label label-warning" href="<?php echo base_url() ?>/admin/blog/hiden">Chưa Duyệt</a> | 
			<a class="label label-success" href="<?php echo base_url() ?>/admin/blog/show">Đã Duyệt</a>
		</div>
		<?php if(!empty($data['list_blog'])): ?>
			<?php foreach ($data['list_blog'] as $value): ?>
				<div class="list-group-item">
					<a href="<?php echo base_url().'/'.$value['slug']; ?>.html">
						<?php echo $value['title']; ?>
					</a>
				</div>
			<?php endforeach; ?>
			<?php if(isset($data['page'])): ?>
				<?php echo $data['page'] ?>
			<?php endif; ?>
		<?php else: ?>
			<?php show_alert(4, array('Chưa có bài viết nào')) ?>
		<?php endif; ?>
	</div>
</div>
