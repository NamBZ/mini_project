<?php


/**
 * param of function show_alert
 * -Int $typeError
 *  case 1: success
 *  case 2: warning
 *  case 3: error
 *  case 4: info
 *  case 5: help
 * -array $arrAlert
*/
if ( ! function_exists('show_alert'))
{
    function show_alert($typeAlert, $arrAlert)
    {
        switch ($typeAlert) {
            case '1':
                foreach($arrAlert as $error){
                    echo '<div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <p><strong> '.$error.'.</strong></p>
                    </div>';
                }       
                break;
                    
            case '2':
                foreach($arrAlert as $error){
                    echo '  <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><strong> '.$error.'.</strong></p>
                        </div>';
                }
                break;
        
            case '3':
                foreach($arrAlert as $error){
                    echo '<div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><strong> '.$error.'.</strong></p>
                            </div>';
                }
                break;
            case '4':
                foreach($arrAlert as $error){
                    echo '<div class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><strong> '.$error.'.</strong></p>
                        </div>';
                }
                break;
            case '5':
                foreach($arrAlert as $error){
                    echo '<div class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><strong> '.$error.'.</strong> </p>
                        </div>';
                }
                break;
        }
    }
}

/**
 * 
 * Check đăng nhập người dùng
 */
if ( ! function_exists('isLogin'))
{
    function isLogin(){
        if(isset($_SESSION['permission']) && isset($_SESSION['username'])) {
            return true;
        }
        if(isset($_COOKIE['permission']) && isset($_COOKIE['username']) && isset($_COOKIE['password'])){
            $infoUser   = getInfo(urldecode($_COOKIE['username']), urldecode($_COOKIE['password']));
            if(!empty($infoUser)){
                $_SESSION['permission'] = true;
                $_SESSION['id']    = $infoUser['id'];
                $_SESSION['password']   = $infoUser['password'];
                $_SESSION['username']       = $infoUser['username'];
                $_SESSION['full_name']  = $infoUser['full_name'];
                $_SESSION['level']  = $infoUser['level'];
                return true;
            }else{
                return false;
            }
        }
        return false;
    }
}
/**
 * 
 * Lấy thông tin đăng nhập người dùng
 */
if ( ! function_exists('getInfo'))
{
    function getInfo($username, $password){
        global $DB;
        $query = $DB->table('users')->getAll('`username` = \''.$username.'\' AND `password` = \''.$password.'\'');
        $info = $query->fetch_array(MYSQLI_ASSOC);
        return $info;
    }
}


/**
 * param of function createPage
*/
if ( ! function_exists('createPage'))
{
    function createPage($config)
    {
        $page = '<div style="text-align:center;"><ul class="pagination pagination-sm" style="text-align:center">';
        $num_page   = ceil($config['total_record']/$config['limit']);
        $start      = $config['current_page'] - 2 ;
        $stop       = (int)($config['current_page'] + 2) ;
        $start      = ( $start < 1 ) ? 1 : $start ; 
        $stop       = ( $stop > $num_page ) ? $num_page : $stop ;

        if( $config['current_page'] > 1 )
        {
            if($config['current_page']>=4){
                $page .= '<li><a href="'.base_url().$config['link'].'1'.$config['endlink'].'" title="trang 1" class="first"><span aria-hidden="true">&laquo;</span></a><li>';
            }
        }
        
                
        for( $i = $start ; $i <= $stop ; $i++ )
        {
            if( $i == $config['current_page'] || ( empty($config['current_page']) && $i == 1 ) )
                $page .= '<li class="active"><a>'.$i.'</a></li>';
            else
                $page .= '<li><a href="'.base_url().$config['link'].$i.$config['endlink'].'" title="trang '.$i.'">'.$i.'</a></li>';
        }

        if( $config['current_page'] < $num_page )
        {
            //$page .= '<li><a href="'.__SITE_URL.$config['link'].($config['current_page']+1).'" title="trang '.($config['current_page']+1).'">next</a></li>';
            if($config['current_page'] < ($num_page - 2)){
                $page .= '<li><a href="'.base_url().$config['link'].$num_page.$config['endlink'].'" title="trang '.$num_page.'" class="last"><span aria-hidden="true">&raquo;</span></a></li>';
            }
        }

        return $page.'</ul></div>';
    }
}


/**
 * param function breadcrumb
 * $arrValue is array
 * ex $array = array(
                    array(
                        'title'     => 'chuyên mục 1',
                        'link'      => 'chuyen-muc-1'
                    )
                );
 * 
 */
if ( ! function_exists('breadcrumb'))
{
    function breadcrumb($arrValue){
        
        $result  = '<nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <span itemtype="http://data-vocabulary.org/Breadcrumb" itemscope>
                                    <a itemprop="url" href="'.__SITE_URL.'">
                                        <span itemprop="title"> Trang chủ</span>
                                    </a>
                                </span>
                            </li>';
                    
        foreach($arrValue as $value){
            $result .= '<li class="breadcrumb-item">
                            <span itemtype="http://data-vocabulary.org/Breadcrumb" itemscope>
                                <a itemprop="url" href="'.$value['link'].'">
                                    <span itemprop="title">'.$value['title'].'</span>
                                </a>
                            </span>
                        </li>';
            
        }
        $result .= '</nav>';
        return $result;
    }
}


/**
 * param of function show_alert
 * -Int $typeError
 *  case 1: success
 *  case 2: warning
 *  case 3: error
 *  case 4: info
 *  case 5: help
 * -array $arrAlert
*/
if ( ! function_exists('redirect'))
{
    function redirect($url = null)
    {
        if(isset($url)){
            header('Location: '.$url);
        }else{
            header('Location: '.base_url());
        }        
    }
}

if ( ! function_exists('base_url'))
{
    function base_url()
    {
        return __SITE_URL;
    }
}

if ( ! function_exists('convertString'))
{
    function convertString($string = null){
        if($string == null){
            $string          = 'ten-bai-viet';
        }else{
            $a_str = array("ă", "ắ", "ằ", "ẳ", "ẵ", "ặ", "á", "à", "ả", "ã", "ạ", "â", "ấ", "ầ", "ẩ", "ẫ", "ậ", "Á", "À", "Ả", "Ã", "Ạ", "Ă", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ", "Â", "Ấ", "Ầ", "Ẩ", "Ẫ", "Ậ" );
            $e_str = array("é","è","ẻ","ẽ","ẹ","ê","ế","ề","ể","ễ","ệ","É","È","Ẻ","Ẽ","Ẹ","Ê","Ế","Ề","Ể","Ễ","Ệ");
            $d_str = array("đ","Đ");
            $o_str = array("ó","ò","ỏ","õ","ọ","ô","ố","ồ","ổ","ỗ","ộ","ơ","ớ","ờ","ở","ỡ","ợ","Ó","Ò","Ỏ","Õ","Ọ","Ô","Ố","Ồ","Ổ","Ỗ","Ộ","Ơ","Ớ","Ờ","Ở","Ỡ","Ợ");
            $u_str = array("ú","ù","ủ","ũ","ụ","ư","ứ","ừ","ữ","ử","ự","Ú","Ù","Ủ","Ũ","Ụ","Ư","Ứ","Ừ","Ử","Ữ","Ự");
            $i_str = array("í","ì","ỉ","ị","ĩ","Í","Ì","Ỉ","Ị","Ĩ");
            $y_str = array("ý","ỳ","ỷ","ỵ","ỹ","Ý","Ỳ","Ỷ","Ỵ","Ỹ");
            $da_str = array("́","̀","̉","̃","̣");
            $string = str_replace($i_str,"i",$string);
            $string = str_replace($da_str,"",$string);
            $string = str_replace($y_str,"y",$string);
            $string = str_replace($a_str,"a",$string);
            $string = str_replace($e_str,"e",$string);
            $string = str_replace($d_str,"d",$string);
            $string = str_replace($o_str,"o",$string);
            $string = str_replace($u_str,"u",$string);
            $string=strtolower($string);
            $string=preg_replace('/[^a-z0-9]/',' ',$string);
            $string=preg_replace('/\s\s /',' ',$string);
            $string=trim($string);
            $string=str_replace(' ','-',$string);
        }
        return $string;
    }
}


if ( ! function_exists('convertTimeToString'))
{
    function convertTimeToString($time)
    {
        $distance = time() - $time;
        switch ($distance) {
            case ($distance < 60):
                $result = $distance . ' giây trước';
                break;
            case ($distance >= 60 && $distance < 3600):
                $result = round($distance/60) . ' phút trước';
                break;
            case ($distance >= 3600 && $distance < 86400):
                $result = round($distance/3600) . ' giờ trước';
                break;
            case (round($distance/86400) == 1):
                $result =  '1 ngày trước';
                break;
            default:
                $result =  date('d/m/y \l\ú\c H:s:i', $time);
                break;
        }
        return $result;
    }
}

if ( ! function_exists('getThumb'))
{
    function getThumb($str)
    {
        preg_match('#<img.+?src="(.+?)".*?>#is',$str,$thumb);
        $tt   =count($thumb);
        if($tt!=0)
            return end($thumb);
        else
        {
            preg_match('#\[img\](.+?)\[\/img\]#is',$str,$thumb);
            $tt    = count($thumb);
            if($tt != 0)
                return end($thumb);
        }
        return 'https://i.imgur.com/XXSBZG9.jpg';
    }
}

if (!function_exists('subWords')) 
{

    function subWords($str, $n = 10){
        $str = trim(preg_replace("/\s+/", " ", strip_tags($str)));

        $word_array = explode(" ", $str);

        if (count($word_array) <= $n)

            return implode(" ", $word_array);

        else {

            $str = '';
            foreach ($word_array as $length => $word) {

                $str .= $word;
                if ($length == $n) break;
                else $str .= " ";
            }
        }
        return $str;
    }
}