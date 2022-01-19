<?php 

/**
 * 
 */
class Admin extends Controller
{
    //thuộc tính lưu trữ danh sách lỗi
    private $_listError = array(),
            $Madmin; 
     
    function __construct()
    {
        parent::__construct();
        $this->load->model('madmin');        
        $this->Madmin = new Madmin;
        
        $this->data['meta']       = array(
                                    'title'         => 'Admin Panel ',
                                    'description'   => 'Admin Panel ',
                                    'keyword'       => 'Admin Panel ',
                                );
        if(isLogin() == false){
            $this->load->header($this->data['meta']);
            show_alert(2,array('Bạn Không Quyền Vào Trang Này'));
            $this->load->footer($this->data['meta']);
            exit();
        }
    }
    
    function index()
    {
        $this->load->header($this->data['meta']);
        $this->load->view('admin/main');
        $this->load->footer($this->data['meta']);
    }

    //phương thức post bài mới
    function posts()
    {
        if($_SESSION['level'] < 1) {
            $this->data['meta']['title'] = 'Stop !!!';
            $this->load->header($this->data['meta']);
            show_alert(3,array('bạn không có quyền vào trang này'));
            $this->load->footer($this->data['meta']);
            die();
        }

        $this->data['meta']['title']  = 'Đăng bài mới';
        $this->load->header($this->data['meta']);
        
        $data["time"]['value']          =  time();
        $data["title"]['value']          =  (isset($_POST['name']))          ? addslashes(trim($_POST['name']))        : '';
        $data["id_category"]['value']   =  (isset($_POST['category']))      ? $_POST['category']                      : '';
        $data["id_author"]['value']     =  (isset($_POST['author']))        ? $_POST['author']                        : $_SESSION['id'];
        $data["content"]['value']       =  (isset($_POST['content']))       ? addslashes(trim($_POST['content']))     : '';
        $data["description"]['value']   =  (isset($_POST['description']))   ? addslashes(trim($_POST['description'])) : '';
        $data["keyword"]['value']       =  (isset($_POST['keyword']))       ? addslashes(trim($_POST['keyword']))     : '';
        $data["slug"]['value']         =  (isset($_POST['slug']) && $_POST['slug'] != null) ? convertString($_POST['slug']) : 'ten-bai-viet-'.time();
        $data["thumbnail"]['value']         =  (isset($_POST['thumbnail']))         ? addslashes(trim($_POST['thumbnail']))       : '';

        $data["name"]['title']          = 'Tên bài viết';
        $data["content"]['title']       = 'nội dung';
        $data["description"]['title']   = 'description';
        $data["keyword"]['title']       = 'keyword';

        
      
        if(isset($_FILES['image']['name']) && $_FILES['image']['name']  != null  && $_FILES['image']['size'] < 5242880){
            $client_id = "4f020a604f0858f";
            $image = file_get_contents($_FILES['image']['tmp_name']);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array( "Authorization: Client-ID $client_id" ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, array( 'image' => base64_encode($image) ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $reply = curl_exec($ch);

            curl_close($ch);
            $return = json_decode($reply);

            if (!empty($return->data->link)) {
                $data["thumbnail"]['value'] =trim($return->data->link);
            }else{
                $this->_listError[] = 'Upload hình đại diện bị lỗi';
            }
        }
        

        $checkAlias             = $this->Madmin->checkAlias($data['slug']['value']);
        $data['listCategory']   = $this->Madmin->getListCategory();
        
        if(isset($_POST['save'])){


            $data["status"]['value'] = isset($_POST['status']) ? 1: 0; 
            //validate data
            

            if (!is_string($data['title']['value']) || strlen($data['title']['value']) < 4 ||strlen($data['title']['value']) > 255 ) {
                $this->_listError[] = 'Tiêu đề bài viết phải là một chuỗi, lớn hơn 4 và nhỏ hơn 255 ký tự';
            }


            if(!empty($checkAlias) > 0 || $data['slug']['value'] == null){
                $this->_listError[]      = 'Url bài viết không hợp lệ(có thể đã tồn tại)';
                $data['slug']['value']  = convertString($data["title"]['value']);
            }

            if($data["id_category"]['value'] == 'false'){
                $this->_listError[]      = 'Chưa chọn chuyên mục';
            }

            if($data["thumbnail"]['value'] == null && $data["content"]['value'] != null){
                $data["thumbnail"]['value'] = getThumb(stripcslashes($data["content"]['value']));
            }
            if(!filter_var($data["thumbnail"]['value'], FILTER_VALIDATE_URL))
            {
                $this->_listError[]      = 'Hình đại diện phải là một URL ảnh hoặc file ảnh';
            }
            
            if($data["description"]['value'] == null && $data["content"]['value'] != null){
                $data["description"]['value'] = subWords($data["content"]['value'], 20);
            }
            if($data["keyword"]['value'] == null){
                $data["keyword"]['value'] = str_replace(' ', ',', $data["description"]['value']);
            }

            if (!is_string($data['description']['value']) || strlen($data['description']['value']) > 255 ) {
                $this->_listError[] = 'Mô tả bài viết phải là một chuỗi, nhỏ hơn 255 ký tự';
            }
            if (!is_string($data['keyword']['value']) || strlen($data['keyword']['value']) > 255 ) {
                $this->_listError[] = 'Keyword bài viết phải là một chuỗi, nhỏ hơn 255 ký tự';
            }

            if(count($this->_listError)){
                show_alert(2,$this->_listError);
                unset($this->_listError);
                unset($_POST);
            }else{

                if($this->Madmin->insertPost($data)){
                    $_SESSION['alert'] = 'Đăng bài viết thành công, bạn có thể xem lại bài viết của mình.';
                    redirect(base_url().'/'.$data['slug']['value'].'.html');
                    foreach ($data as $key => $value) {
                        if(isset($data[$key]['value']))
                            $data[$key]['value'] = '';
                    }
                }else{
                    show_alert(2,array('Đăng Bài Viết Thất Bại'));
                }
            }
        }
        $this->load->view('admin/posts',$data);
        $this->load->footer($this->data['meta']);
        
    }

    public function status($slug, $type)
    {
        if($_SESSION['level'] < 9) {
            $this->data['meta']['title'] = 'Stop !!!';
            $this->load->header($this->data['meta']);
            show_alert(3,array('bạn không có quyền vào trang này'));
            $this->load->footer($this->data['meta']);
            die();
        }

        $this->data['meta']['title']  = 'Phê duyệt - Ẩn bài viết';
        $this->load->header($this->data['meta']);

        $checkAlias = $this->Madmin->checkAlias($slug);

        if ($type == 'public') {
            $status = 1;
        } else if ($type == 'hide') {
            $status = 0;
        }
        $update = array(
            'status' => $status
        );

        if(empty($checkAlias)) {
            show_alert(2, array('Bài viết không tồn tại'));
        }elseif ($checkAlias['status'] == $status) {
            $_SESSION['alert'] = 'Bài viết đã được '. ($status == 1? 'phê duyệt' : 'ẩn') . ' trước đó rồi';
            redirect(base_url().'/'.$checkAlias['slug'].'.html');
        } else {
            if($this->Madmin->updateStatus($checkAlias['id'], $update)){
                $_SESSION['alert'] = ($status == 1? 'Phê duyệt' : 'Ẩn') . ' bài viết thành công';
                redirect(base_url().'/'.$checkAlias['slug'].'.html');
            }else{
                $_SESSION['alert'] = ($status == 1? 'Phê duyệt' : 'Ẩn') . ' bài viết thất bại';
                redirect(base_url().'/'.$checkAlias['slug'].'.html');
            }
        }
        $this->load->footer($this->data['meta']);
    }


    function editpost($id) 
    {
        $id = (int)$id;
        if($_SESSION['level'] < 1) {
            $this->data['meta']['title'] = 'Stop !!!';
            $this->load->header($this->data['meta']);
            show_alert(3,array('bạn không có quyền vào trang này'));
            $this->load->footer($this->data['meta']);
            die();
        }

        $this->data['meta']['title']  = 'Chỉnh sửa bài viết';
        $this->load->header($this->data['meta']);
        
        $infoBlog   = $this->Madmin->checkPostID($id);
        if(!empty($infoBlog)){
            if($infoBlog['id_author'] != $_SESSION['id'] && $_SESSION['level'] < 5) {
                $this->data['meta']['title'] = 'Stop !!!';
                show_alert(3,array('bạn không có quyền vào trang này'));
                $this->load->footer($this->data['meta']);
                die();
            }
            $data["main-slug"]['value']     = $infoBlog['slug'];
            $data["id"]['value']             = $infoBlog['id'];
            $data["time"]['value']           = $infoBlog['times'];
            $data["title"]['value']           = (isset($_POST['title']) )          ? addslashes(trim($_POST['title']))         : $infoBlog['title'];
            $data["id_category"]['value']    = (isset($_POST['category']))       ? $_POST['category']                       : $infoBlog['id_category'];
            $data["id_author"]['value']      = $infoBlog['id_author'];
            $data["content"]['value']        = (isset($_POST['content']))        ? addslashes(trim($_POST['content']))      : $infoBlog['content'];
            $data["description"]['value']    = (isset($_POST['description']))    ? addslashes(trim($_POST['description']))  : $infoBlog['description'];
            $data["keyword"]['value']        = (isset($_POST['keyword']))        ? addslashes(trim($_POST['keyword']))      : $infoBlog['keyword'];
            $data["slug"]['value']          = (isset($_POST['slug']))          ? convertString(trim($_POST['slug']))     : $infoBlog['slug'];
            $data["thumbnail"]['value']          = (isset($_POST['thumbnail']))          ? addslashes(trim($_POST['thumbnail']))        : $infoBlog['thumbnail'];
            $data["status"]['value'] = $infoBlog['status'];


            $data["title"]['title']           = 'Tên bài viết';
            $data["content"]['title']        = 'nội dung';
            $data["description"]['title']    = 'description';
            $data["keyword"]['title']        = 'keyword';

            if(isset($_FILES['image']['name']) && $_FILES['image']['name']  != null && $_FILES['image']['size'] < 5242880){
                $client_id = "4f020a604f0858f";
                $image = file_get_contents($_FILES['image']['tmp_name']);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array( "Authorization: Client-ID $client_id" ));
                curl_setopt($ch, CURLOPT_POSTFIELDS, array( 'image' => base64_encode($image) ));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $reply = curl_exec($ch);

                curl_close($ch);
                $return = json_decode($reply);

                if (!empty($return->data->link)) {
                    $data["thumbnail"]['value'] =trim($return->data->link);
                }else{
                    $this->_listError[] = 'Upload hình đại diện bị lỗi';
                }
            }

            $data['listCategory']   = $this->Madmin->getListCategory();
            $checkAlias             = $this->Madmin->checkAlias($data['slug']['value']);
            if(isset($_POST['save'])){

                if(( !empty($checkAlias) || $data['slug']['value'] == null) && $data['slug']['value'] != $data['main-slug']['value']){
                    $this->_listError[] = 'Url bài viết không hợp lệ(có thể đã tồn tại)';
                    $data["slug"]['value']  = convertString($data['title']['value']);
                }

                if($data["id_category"]['value'] == 'false'){
                    $this->_listError[]      = 'Chưa chọn chuyên mục';
                }

                if($data["thumbnail"]['value'] == null && $data["content"]['value'] != null){
                    $data["thumbnail"]['value'] = getThumb(stripcslashes($data["content"]['value']));
                }
                if(!filter_var($data["thumbnail"]['value'], FILTER_VALIDATE_URL))
                {
                    $this->_listError[]      = 'Hình đại diện phải là một URL ảnh hoặc file ảnh';
                }

                if($data["description"]['value'] == null && $data["content"]['value'] != null){
                    $data["description"]['value'] = subWords($data["content"]['value'], 20);
                }
                if($data["keyword"]['value'] == null){
                    $data["keyword"]['value'] = str_replace(' ', ',', $data["description"]['value']);
                }

                $data["status"]['value'] = isset($_POST['status']) ? 1: 0;
                
                //validate data

                if (!is_string($data['title']['value']) || strlen($data['title']['value']) < 4 ||strlen($data['title']['value']) > 255 ) {
                    $this->_listError[] = 'Tiêu đề bài viết phải là một chuỗi, lớn hơn 4 và nhỏ hơn 255 ký tự';
                }
                if (!is_string($data['description']['value']) || strlen($data['description']['value']) > 255 ) {
                    $this->_listError[] = 'Mô tả bài viết phải là một chuỗi, nhỏ hơn 255 ký tự';
                }
                if (!is_string($data['keyword']['value']) || strlen($data['keyword']['value']) > 255 ) {
                    $this->_listError[] = 'Keyword bài viết phải là một chuỗi, nhỏ hơn 255 ký tự';
                }


                if(count($this->_listError)){
                    show_alert(2,$this->_listError);
                    unset($this->_listError);
                    unset($_POST);
                }else{
                    if($this->Madmin->updatePost($data)){
                        show_alert(1,array('Update Bài Viết Thành Công, <a href="'.base_url().'/'.$data["slug"]['value'].'.html">Xem bài viết</a>'));
                    }else{
                        show_alert(2,array('Chỉnh Sửa Bài Viết Thất Bại'));
                    }
                }
            }
            
            $this->load->view('admin/post_edit',$data);
        }else{
            show_alert(2,array('Bài viết không tồn tại'));
        }
        
        $this->load->footer($this->data['meta']);
    }

    function deletepost($post_id)
    {
        if($_SESSION['level'] < 1) {
            $this->data['meta']['title'] = 'Stop !!!';
            $this->load->header($this->data['meta']);
            show_alert(3,array('bạn không có quyền vào trang này'));
            $this->load->footer($this->data['meta']);
            die();
        }

        $this->data['meta']['title']  = 'Xóa bài viết';
        
        $this->load->header($this->data['meta']);

        $checkID     = $this->Madmin->checkPostID($post_id);

        if(empty($checkID)) {
            $this->_listError[] = 'Bài viết không tồn tại';
        }

        if(count($this->_listError))
        {
            show_alert(2,$this->_listError);
            unset($this->_listError);
        }
        else
        {
            if(isset($_POST['delete'])){
            
                if($this->Madmin->deletePost($post_id)){
                    show_alert(1,array('Xóa Bài Viết Thành Công'));
                }else{
                    show_alert(3,array('Xóa Bài Viết Thất Bại'));
                }

            }else{
                $this->load->view('admin/post_delete', $checkID);
            }
        }

        $this->load->footer($this->data['meta']);
    }


    public function blog($type = null, $page = null) {
        if($_SESSION['level'] < 5) {
            $this->data['meta']['title'] = 'Stop !!!';
            $this->load->header($this->data['meta']);
            show_alert(3,array('bạn không có quyền vào trang này'));
            $this->load->footer($this->data['meta']);
            die();
        }
        if($type == null) 
            redirect(base_url().'/admin/blog/hiden');
        $status = ($type == 'hiden') ? 0 : 1; 
        $this->data['meta']['title']  = 'Quản Lý bài viết';
        $this->load->header($this->data['meta']);

        $limit = 10;
        $page = (isset($page)) ? $page : 1;
        if($page == null || $page <= 0){ $page = 1; }

        $total = $this->Madmin->coutPost($status);
        $total_page = ceil( $total/$limit);

        $page_current = abs((int)$page);
        if($page_current <= 0 || $page_current > $total_page )
            $page_current = 1 ;
        $record_current = ($page_current - 1) * $limit ;
        $data['list_blog'] = $this->Madmin->getAllPost($status, $record_current, $limit);
        if($total > $limit){
            $pagination = array(
                'limit'           => $limit,
                'total_record'    => $total,
                'current_page'    => $page_current,
                'link'            => '/admin/blog/'.$type.'/',
                'endlink'         => ''
            );
            $data['page'] = createPage($pagination);
        }
        $this->load->view('admin/post_list', $data);
        $this->load->footer($this->data['meta']);
    }


    public function category($type = '', $param = '')
    {
        if($_SESSION['level'] < 9) {
            $this->data['meta']['title'] = 'Stop !!!';
            $this->load->header($this->data['meta']);
            show_alert(3,array('bạn không có quyền vào trang này'));
            $this->load->footer($this->data['meta']);
            die();
        }
        $this->load->model('mcategory');
        $this->Mcategory = new Mcategory;
        $data['listcategories'] = $this->Mcategory->getListCategory();
        switch ($type) 
        {

            case 'list':
                $this->data['meta']['title']  = 'Danh Sách Chuyên Mục';
                $this->load->header($this->data['meta']);
                $data = $this->Madmin->getListCategory();
                $this->load->view('admin/category_list', $data);
                break;

            case 'create':
                $this->data['meta']['title']  = 'Tạo Chuyên Mục';
                $this->load->header($this->data['meta']);

                $data["name"]['value']          =  (isset($_POST['name']))          ? addslashes($_POST['name'])        : '';
                $data["description"]['value']   =  (isset($_POST['description']))   ? addslashes($_POST['description']) : '';
                $data["keyword"]['value']       =  (isset($_POST['keyword']))       ? addslashes($_POST['keyword'])     : '';
                $data["parent"]['value']        =  (isset($_POST['parent']))        ? $_POST['parent']                  : '';
                $data["slug"]['value']         =  (isset($_POST['slug']) && $_POST['slug'] != null) ? convertString($_POST['slug']) : 'ten-chuyen-muc';

                $data["name"]['title']          = 'Tên chuyên mục';
                $data["description"]['title']   = 'description';
                $data["keyword"]['title']       = 'keyword';

                if(isset($_POST['save'])){
                    if (!is_string($data['name']['value']) || strlen($data['name']['value']) < 4 ||strlen($data['name']['value']) > 255 ) {
                        $this->_listError[] = 'Tên chuyên mục phải là một chuỗi, lớn hơn 4 và nhỏ hơn 255 ký tự';
                    }
                    if (!is_string($data['description']['value']) || strlen($data['description']['value']) > 255 ) {
                        $this->_listError[] = 'Mô tả phải là một chuỗi, nhỏ hơn 255 ký tự';
                    }
                    if (!is_string($data['keyword']['value']) || strlen($data['keyword']['value']) > 255 ) {
                        $this->_listError[] = 'Keyword phải là một chuỗi, nhỏ hơn 255 ký tự';
                    }
                    if($data["parent"]['value'] == 0){
                        $data["parent"]['value'] = NULL;
                    }
                    if(!empty($checkAlias) > 0 || $data['slug']['value'] == null){
                        $this->_listError[]      = 'Url chuyên mục không hợp lệ(có thể đã tồn tại)';
                        $data['slug']['value']  = convertString($data["title"]['value']);
                    }

                    $checkAlias       = $this->Madmin->checkCategory($data['slug']['value']);

                    if($checkAlias > 0 || $data['slug']['value'] == null){
                        $this->_listError[]         = 'Url chuyên mục không hợp lệ(có thể đã tồn tại)';
                        $data["slug"]['value']     = convertString($data["name"]['value']);
                    }

                    if(count($this->_listError)){
                        show_alert(2,$this->_listError);
                        unset($this->_listError);
                        unset($_POST);
                    }else{

                        if($this->Madmin->insertCategory($data)){
                           show_alert(1,array('Tạo Chuyên Mục Thành Công, <a href="'.base_url().'/admin/category/list">Trở về</a>'));
                        }
                        else{
                            show_alert(2,array('Tạo Chuyên Mục Thất Bại'));
                        }
                    }
                }
                $this->load->view('admin/category_create', $data);
                break;

            case 'edit':

                $this->data['meta']['title']  = 'Chỉnh Sửa Chuyên Mục';
                $this->load->header($this->data['meta']);

                $infoCategory = $this->Madmin->getInfoCategory($param);
                if(!empty($infoCategory)){

                    $data["id"]['value']            =  $infoCategory['id'];
                    $data["main_slug"]['value']    =  $infoCategory['slug'];
                    $data["name"]['value']          =  (isset($_POST['name']))          ? addslashes($_POST['name'])        : $infoCategory['name'];
                    $data["parent"]['value']        =  (isset($_POST['parent']))        ? $_POST['parent']                  : $infoCategory['parent'];
                    $data["description"]['value']   =  (isset($_POST['description']))   ? addslashes($_POST['description']) : $infoCategory['description'];
                    $data["keyword"]['value']       =  (isset($_POST['keyword']))       ? addslashes($_POST['keyword'])     : $infoCategory['keyword'];
                    $data["slug"]['value']         =  (isset($_POST['slug']) && $_POST['slug'] != null) ? convertString($_POST['slug']) : $infoCategory['slug'];

                    $data["name"]['title']          = 'Tên chuyên mục';
                    $data["description"]['title']   = 'description';
                    $data["keyword"]['title']       = 'keyword';

                    $data['listCategory']   = $this->Madmin->getListCategory();
                    $checkAlias             = $this->Madmin->checkAlias($data['slug']['value']);
                    if(isset($_POST['save'])){
                        
                        if (!is_string($data['name']['value']) || strlen($data['name']['value']) < 4 ||strlen($data['name']['value']) > 255 ) {
                            $this->_listError[] = 'Tên chuyên mục phải là một chuỗi, lớn hơn 4 và nhỏ hơn 255 ký tự';
                        }
                        if (!is_string($data['description']['value']) || strlen($data['description']['value']) > 255 ) {
                            $this->_listError[] = 'Mô tả phải là một chuỗi, nhỏ hơn 255 ký tự';
                        }
                        if (!is_string($data['keyword']['value']) || strlen($data['keyword']['value']) > 255 ) {
                            $this->_listError[] = 'Keyword phải là một chuỗi, nhỏ hơn 255 ký tự';
                        }
                        if($data["parent"]['value'] == 0){
                            $data["parent"]['value'] = NULL;
                        }

                        $checkAlias       = $this->Madmin->checkCategory($data['slug']['value']);
    
                        if(( !empty($checkAlias) || $data['slug']['value'] == null) && $data['slug']['value'] != $data['main_slug']['value']){
                            $this->_listError[] = 'Url bài viết không hợp lệ(có thể đã tồn tại)';
                            $data["slug"]['value']  = convertString($data['title']['value']);
                        }
                        if(count($this->_listError)){
                            show_alert(2,$this->_listError);
                            unset($this->_listError);
                            unset($_POST);
                        }else{
                            if($this->Madmin->updateCategory($data)){
                                show_alert(1,array('Chỉnh Sửa Chuyên Mục Thành Công, <a href="'.base_url().'/admin/category/list">Trở về</a>'));
                            }else{
                                show_alert(2,array('Chỉnh Sửa Chuyên Mục Thất Bại'));
                            }
                        }
                    }
                    
                    $this->load->view('admin/category_edit', $data);
                }else{
                    show_alert(2,array('Bài viết không tồn tại'));
                }
                break;
            case 'delete':
                $this->data['meta']['title']  = 'Xóa Chuyên Mục';
                $this->load->header($this->data['meta']);
                $data = $this->Madmin->getInfoCategory($param);
                if(!empty($data)){
                    if(isset($_POST['delete'])){
                        if($this->Madmin->deleteCategory($param)){
                            show_alert(1, array('Xóa chuyên mục " ' . $data['name'] . ' " thành công, <a href="'.base_url().'/admin/category/list">Trở về</a>'));
                        }else{
                            show_alert(2, array('Xóa chuyên mục thất bại'));
                        }
                    }else{
                        $this->load->view('admin/category_delete', $data);
                    }
                }else{
                    show_alert(2, array('Chuyên mục không tồn tại'));
                }
                break;

            default:
                $this->data['meta']['title']  = 'Quản Lý Chuyên Mục';
                $this->load->header($this->data['meta']);
                $this->load->view('admin/category-list');
                break;
        }

        $this->load->footer($this->data['meta']);
    }
}
