<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title">Chỉnh sửa chuyên mục</h3>
	</div>
	<div class="panel-body">
		<form action="<?php echo base_url(); ?>/admin/category/edit/<?php echo $data["main_slug"]['value']; ?>" method="post" name="main-form">
			<div class="form-group">
				<label for="name">Tên Chuyên Mục:</label>
				<input class="form-control" type="text" id="name" name="name" value="<?php echo stripslashes($data["name"]['value']); ?>">
			</div>
			<div class="form-group">
				<label for="slug">Url Chuyên Mục (Slug):</label>
				<input class="form-control" type="slug" id="slug" name="slug" placeholder="ten-chuyen-muc" value="<?php echo $data["slug"]['value']; ?>">
			</div>
			<div class="form-group">
				<label class="control-label" for="parent">Chuyên mục</label>
				<select id="parent" name="parent" class="form-control">
					<option value="0">Chuyên mục gốc</option>
					<?php
					$selected = '';
					if ($data["parent"]['value'] != 0) {
						foreach ($data['listcategories'] as $category) {
							if (empty($category['parent'])) {
								if ($data['parent']['value'] == $category['id'])
									$selected = 'selected="selected"';
								echo '<option value="' . $category['id'] . '" ' . $selected . '><span class="main-cate">' . $category['name'] . '</span></option>';
								$selected = '';
							}
						}
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label for="description">Miêu tả:</label>
				<textarea class="form-control" rows="5" id="description" name="description"><?php echo stripslashes($data["description"]['value']); ?></textarea>
			</div>
			<div class="form-group">
				<label for="keyword">Từ khóa:</label>
				<textarea class="form-control" rows="2" id="keyword" name="keyword"><?php echo stripslashes($data["keyword"]['value']); ?></textarea>
			</div>
			<button type="submit" name="save" class="btn btn-primary">Lưu chỉnh sửa</button>
			<button type="button" class="btn btn-default" onclick="window.location.href ='<?php echo base_url(); ?>/admin/category/list';">Hủy</button>
		</form>
	</div>
</div>
<script>
    $(document).ready(function() {
        $( "input#name" ).keyup(function() {
            var value = $( this ).val();
            var nodeUrl = $("input#slug");
            var tmp = nodeUrl.val();
            nodeUrl.val(change_alias(value));
        }).keyup();

        function change_alias( alias )
        {
            var str = alias;
            str= str.toLowerCase(); 
            str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
            str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
            str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
            str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ợ|ở|ỡ|ớ/g,"o"); 
            str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
            str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
            str= str.replace(/đ/g,"d"); 
            str= str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g,"-");
            /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
            str= str.replace(/-+-/g,"-"); //thay thế 2- thành 1-
            str= str.replace(/^\-+|\-+$/g,""); 
            //cắt bỏ ký tự - ở đầu và cuối chuỗi 
            return str;
        }
    });
</script>