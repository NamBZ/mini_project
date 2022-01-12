<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil-square" aria-hidden="true"></i>
        <b>Chỉnh sửa bài viết</b>
    </div>
    <div class="list-group">
        <form action="" enctype="multipart/form-data" method="post" name="main-form">
        <div class="form-group list-group-item">
            <label for="title">Tiêu Đề </label>
            <input class="form-control" type="text" name="title" id="title" value="<?php echo stripslashes($data["title"]['value']); ?>">
        </div>
        <div class="form-group list-group-item">
            <label for="slug">Url Bài Viết </label>
            <input class="form-control" type="text" name="slug" id="slug" value="<?php echo $data["slug"]['value']; ?>">
        </div>
        <div class="form-group list-group-item">
            <label for="category">Chuyên Mục </label>
            <select class="form-control" name="category" id="category">
            <?php
            $selected = '';
            foreach($data['listCategory'] as $category){
                if($category['parent'] == 0){
                    echo '<option value="false" disabled>----------------</option>';
                    if($category['id'] == $data["id_category"]['value']){
                        $selected = 'selected="selected"';
                    }
                    echo '<option value="'.$category['id'].'" '.$selected.'><span class="main-cate"> +  '.$category['name'].'</span></option>';
                    $selected = '';
                    foreach ($data['listCategory'] as $scategory) {
                        if($category['id'] == $scategory["parent"]){
                            if($scategory['id'] == $data["id_category"]['value']){
                                $selected = 'selected="selected"';
                            }
                            echo '<option value="'.$scategory['id'].'" '.$selected.'><span class="sub-cate"> -    '.$scategory['name'].'</span></option>';
                        }
                    }
                }
            }
            ?>
            </select>
        </div>
        <div class="form-group list-group-item">
            <label for="content">Nội Dung </label>
            <textarea class="form-control" name="content" id="content" style="height:110px; display: table-cell;"><?php echo stripcslashes($data["content"]['value']); ?></textarea>
        </div>
        <div class="form-group list-group-item">
            <label for="description">Miêu Tả </label>
            <textarea class="form-control" name="description"><?php echo stripslashes($data["description"]['value']); ?></textarea>
        </div>
        <div class="form-group list-group-item">
            <label for="keyword">Từ Khóa </label>
            <textarea class="form-control" name="keyword"><?php echo stripslashes($data["keyword"]['value']); ?></textarea>
        </div>
        <?php if ($_SESSION['level'] == 9) { ?>
        <div class="form-check list-group-item">
            <label for="status">Hiển thị ra trang chủ </label>
                <input class="form-check-input" type="checkbox" name="status" value="1" checked="checked">Có 
        </div>
        <?php } ?>
        <div class="form-group list-group-item">
            <label for="thumbnail">Hình Đại Diện </label>
            <input class="form-control" type="text" name="thumbnail" id="thumbnail" value="<?php echo $data["thumbnail"]['value']; ?>">
        </div>
        <div class="form-group list-group-item">
            <label for="image-file">Tải Lên Đại Diện </label>
            <input class="form-control" type="file" name="image" id="image-file" accept="image/*">
        </div>
       <div class="form-group list-group-item">
            <input class="btn btn-primary" type="submit" name="save" value="Chỉnh Sửa"></span>
        </div>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.17.1/ckeditor.js" integrity="sha512-VXEKi5eNc7ECuyIueuledlqeUWiJ7hcxBe9fnsCiVzeZ0XwJxAPemnq01/LBIBnp3i0szhvKNd9Us7fqNPsRmQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $( "input#title" ).keyup(function() {
            var value = $( this ).val();
            var nodeUrl = $("input#slug");
            var tmp = nodeUrl.val();
            nodeUrl.val(change_alias(value));
            chaneIcon();
        }).keyup();
        CKEDITOR.replace('content');

        function chaneIcon()
        {
            var nodeCurent = $('i.glyphicon-random');
            nodeCurent.removeClass('glyphicon-random').removeClass('glyphicon');
            nodeCurent.addClass('fa').addClass('fa-spinner').addClass('fa-spin');
            setTimeout(function(){
                nodeCurent = $('i.fa-spin');
                nodeCurent.removeClass('fa-spinner').removeClass('fa').removeClass('fa-spin');
                nodeCurent.addClass('glyphicon').addClass('glyphicon-random');
            }, 1000);
        }

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