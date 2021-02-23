<?php

use App\Language;
use App\Menu;
use App\ProductRatings;
use App\StaticOption;
use App\WorksCategory;
use App\Works;
use Illuminate\Support\Facades\Auth;
use App\MediaUpload;
use App\Page;
use Illuminate\Support\Facades\Session;

function active_menu($url)
{

    return $url == request()->path() ? 'active' : '';
}

function active_menu_frontend($url)
{

    return $url == request()->path() ? 'current-menu-item' : '';
}


function check_image_extension($file)
{
    $extension = strtolower($file->getClientOriginalExtension());
    if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension = 'gif') {
        return false;
    }
    return true;
}

function sendSubscriberEmail($to, $subject, $message, $from = '')
{

    $from = get_static_option('site_global_email');
    $headers = "From: " . $from . " \r\n";
    $headers .= "Reply-To: <$from> \r\n";
    $headers .= "Return-Path: " . ($from) . "\r\n";;
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "X-Priority: 2\nX-MSmail-Priority: high";;
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    }

}

function sendEmail($to, $name, $subject, $message, $from = '')
{
    $template = get_static_option('site_global_email_template');
    $from = get_static_option('site_global_email');

    $headers = "From: " . $from . " \r\n";
    $headers .= "Reply-To: <$from> \r\n";
    $headers .= "Return-Path: " . ($from) . "\r\n";;
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "X-Priority: 2\nX-MSmail-Priority: high";;
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

    $mm = str_replace("@username", $name, $template);
    $message = str_replace("@message", $message, $mm);
    $message = str_replace("@company", get_static_option('site_title'), $message);

    if (mail($to, $subject, $message, $headers)) {
        return true;
    }

}

function sendPlanEmail($to, $name, $subject, $message, $from)
{

    $headers = "From: " . $from . " \r\n";
    $headers .= "Reply-To: <$from> \r\n";
    $headers .= "Return-Path: " . ($from) . "\r\n";;
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "X-Priority: 2\nX-MSmail-Priority: high";;
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";
    $message = "\nThis mail send by " . $name;
    if (mail($to, $subject, $message, $headers)) {
        return true;
    }
}


function set_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    }
    return false;
}

function get_static_option($key)
{
    global $option_name;
    $option_name = $key;
    $value = \Illuminate\Support\Facades\Cache::remember($option_name,86400, function () {
        global $option_name;
        return StaticOption::where('option_name', $option_name)->first();
    });

    return !empty($value) ? $value->option_value : null;
}

function update_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    } else {
        StaticOption::where('option_name', $key)->update([
            'option_name' => $key,
            'option_value' => $value
        ]);
        \Illuminate\Support\Facades\Cache::forget($key);
        return true;
    }
    return false;
}

function delete_static_option($key)
{
    StaticOption::where('option_name', $key)->delete();
    return true;
}

function single_post_share($url, $title, $img_url)
{
    $output = '';
    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);

    //all social share link generate
    $facebook_share_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;
    $twitter_share_link = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;via=' . get_static_option('site_' . get_default_language() . '_title');
    $linkedin_share_link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&amp;title=' . $post_title;
    $pinterest_share_link = 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&amp;media=' . $img_url . '&amp;description=' . $post_title;

    $output .= '<li><a class="facebook" href="' . $facebook_share_link . '"><i class="fab fa-facebook-f"></i></a></li>';
    $output .= '<li><a class="twitter" href="' . $twitter_share_link . '"><i class="fab fa-twitter"></i></a></li>';
    $output .= '<li><a class="linkedin" href="' . $linkedin_share_link . '"><i class="fab fa-linkedin-in"></i></a></li>';
    $output .= '<li><a class="pinterest" href="' . $pinterest_share_link . '"><i class="fab fa-pinterest-p"></i></a></li>';

    return $output;
}


function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}


function licnese_cheker()
{
    $data = array(
        'action' => env('XGENIOUS_API_ACTION'),
        'purchase_code' => get_static_option('item_purchase_key'),
        'author' => env('XGENIOUS_API_AUTHOR'),
        'site_url' => url('/'),
        'item_unique_key' => env('XGENIOUS_API_KEY'),
    );
    //item_license_status
    $api_url = env('XGENIOUS_API_URL') . '?' . http_build_query($data);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($result);
    update_static_option('item_license_status', $result->license_status);
    $type = 'verified' == $result->license_status ? 'success' : 'danger';
    $license_info = [
        "item_license_status" => $result->license_status,
        "last_check" => time(),
        "purchase_code" => get_static_option('item_purchase_key'),
        "xgenious_app_key" => env('XGENIOUS_API_KEY'),
        "author" => env('XGENIOUS_API_AUTHOR'),
        "message" => $result->message
    ];
    file_put_contents('@core/license.json', json_encode($license_info));
}

function get_work_category_by_id($id, $output = 'array')
{
    $category_id = Works::find($id)->categories_id;
    $cat_list = [];
    $cat_list_string = '';
    $cat_list_slug = '';


    foreach ($category_id as $key => $data) {
        $separator = $key != 0 ? ', ' : '';
        $cat_item = WorksCategory::find($data);
        if (!empty($cat_item)){
            $cat_list[$cat_item->id] = $cat_item->name;
            $cat_list_string .= $separator . $cat_item->name;
            $cat_list_slug .= Str::slug($cat_item->name) . ' ';
        }

    }



    switch ($output) {
        case ("string"):
            return $cat_list_string;
            break;
        case ("slug"):
            return $cat_list_slug;
            break;
        default:
            return $cat_list;
            break;
    }

}

function get_child_menu_count($menu_content, $parent_id)
{
    $return_val = 0;
    foreach ($menu_content as $data) {
        if ($parent_id == $data->parent_id) {
            $return_val++;
        }
    }
    return $return_val;
}

function minify_css_lines($css)
{
    // some of the following functions to minimize the css-output are directly taken
    // from the awesome CSS JS Booster: https://github.com/Schepp/CSS-JS-Booster
    // all credits to Christian Schaefer: http://twitter.com/derSchepp
    // remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    // backup values within single or double quotes
    preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($hit[1]); $i++) {
        $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
    }
    // remove traling semicolon of selector's last property
    $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
    // remove any whitespace between semicolon and property-name
    $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
    // remove any whitespace surrounding property-colon
    $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
    // remove any whitespace surrounding selector-comma
    $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
    // remove any whitespace surrounding opening parenthesis
    $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
    // remove any whitespace between numbers and units
    $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
    // shorten zero-values
    $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
    // constrain multiple whitespaces
    $css = preg_replace('/\p{Zs}+/ims', ' ', $css);
    // remove newlines
    $css = str_replace(array("\r\n", "\r", "\n"), '', $css);
    // Restore backupped values within single or double quotes
    for ($i = 0; $i < count($hit[1]); $i++) {
        $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
    }

    return $css;
}

function google_captcha_check($token)
{
    $captha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $captha_url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('secret' => get_static_option('site_google_captcha_v3_secret_key'), 'response' => $token)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    return $result;
}

function load_google_fonts()
{
    //google fonts link;
    $fonts_url = 'https://fonts.googleapis.com/css?family=';

    //body fonts
    $body_font_family = !empty(get_static_option('body_font_family')) ? get_static_option('body_font_family') : 'Open Sans';
    $heading_font_family = !empty(get_static_option('heading_font_family')) ? get_static_option('heading_font_family') : 'Montserrat';

    $load_body_font_family = str_replace(' ', '+', $body_font_family);
    $body_font_variant_selected_arr = !empty(get_static_option('body_font_variant')) ? unserialize(get_static_option('body_font_variant')) : ['regular'];
    $load_body_font_variant = is_array($body_font_variant_selected_arr) ? implode(',', $body_font_variant_selected_arr) : '400';
    $fonts_url .= $load_body_font_family . ':' . $load_body_font_variant;
    $load_heading_font_family = str_replace(' ', '+', $heading_font_family);
    $heading_font_variant_selected_arr = !empty(get_static_option('heading_font_variant')) ? unserialize(get_static_option('heading_font_variant')) : ['regular'];
    $load_heading_font_variant = is_array($heading_font_variant_selected_arr) ? implode(',', $heading_font_variant_selected_arr) : '400';

    if (!empty(get_static_option('heading_font')) && $heading_font_family != $body_font_family) {
        $fonts_url .= '|' . $load_heading_font_family . ':' . $load_heading_font_variant;
    }

    return sprintf(' <link href="%1$s&display=swap" rel="stylesheet">', $fonts_url);
}

function get_language_by_slug($slug)
{
    $lang_details = \App\Language::where('slug', $slug)->first();
    return !empty($lang_details) ? $lang_details->name : '';
}

function get_default_language()
{
    $defaultLang = Language::where('default', 1)->first();
    return $defaultLang->slug;
}

function get_all_language()
{
    $all_lang = Language::orderBy('default', 'DESC')->get();
    return $all_lang;
}

function get_user_lang()
{
    $default = Language::where('default', '1')->first();
    return !empty(session()->get('lang')) ? session()->get('lang') : $default->slug;
}

function get_user_lang_direction()
{
    $default = Language::where('default', 1)->first();
    $user_direction = Language::where('slug', session()->get('lang'))->first();
    return !empty(session()->get('lang')) ? $user_direction->direction : $default->direction;
}

function get_field_by_type($type, $name, $placeholder, $options = [], $requried = null, $mimes = null)
{
    $markup = '';
    $required_markup_html = 'required="required"';
    switch ($type) {
        case('email'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="email" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case('tel'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="tel" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case('date'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="date" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case('url'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="url" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case('textarea'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group textarea"><textarea name="' . $name . '" id="' . $name . '" cols="30" rows="10" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></textarea></div>';
            break;
        case('file'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $mimes_type_markup = str_replace('mimes:', __('Accept File Type:') . ' ', $mimes);
            $markup = ' <div class="form-group file"> <label for="' . $name . '">' . __($placeholder) . '</label> <input type="file" id="' . $name . '" name="' . $name . '" ' . $required_markup . ' class="form-control" > <span class="help-info">' . $mimes_type_markup . '</span></div>';
            break;
        case('checkbox'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group checkbox">  <input type="checkbox" id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '> <label for="' . $name . '">' . __($placeholder) . '</label></div>';
            break;
        case('select'):
            $option_markup = '';
            $required_markup = !empty($requried) ? $required_markup_html : '';
            foreach ($options as $opt) {
                $option_markup .= '<option value="' . Str::slug($opt) . '">' . $opt . '</option>';
            }
            $markup = ' <div class="form-group select"> <label for="' . $name . '">' . __($placeholder) . '</label> <select id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '>' . $option_markup . '</select></div>';
            break;
        default:
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="text" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
    }

    return $markup;
}

function check_page_permission($page)
{
    if (Auth::check()) {
        $id = auth()->user()->id;
        $role_id = \App\Admin::where('id', $id)->first();
        $user_role = \App\AdminRole::where('id', $role_id->role)->first();
        $all_permission = json_decode($user_role->permission);
        if (in_array($page, $all_permission)) {
            return true;
        }
    }
    return false;
}
function check_page_permission_by_string($page)
{
    $page = strtolower(str_replace(' ','_',$page));
    if (Auth::check()) {
        $id = auth()->user()->id;
        $role_id = \App\Admin::where('id', $id)->first();
        $user_role = \App\AdminRole::where('id', $role_id->role)->first();
        $all_permission = json_decode($user_role->permission);
        if (in_array($page, $all_permission)) {
            return true;
        }
    }
    return false;
}


function get_user_role_name_by_id($id)
{
    $name = \App\AdminRole::where('id', $id)->first();
    return $name->name;
}

function get_topic_name_by_id($id)
{
    $name = \App\KnowledgebaseTopic::where('id', $id)->first();
    return !empty($name) ? $name->title : '';
}

/*
 * php delete function that deals with directories recursively
 */
function delete_dir_with_file($target)
{
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

        foreach ($files as $file) {
            delete_dir_with_file($file);
        }

        if (file_exists($target)) {
            rmdir($target);
        }
    } elseif (is_file($target)) {
        unlink($target);
    }
}

function chmod_r($path)
{
    if (is_dir($path)) {
        $files = glob($path . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

        foreach ($files as $file) {
            chmod($file, 0777);
        }

    } elseif (is_file($path)) {
        chmod($path, 0777);
    }
}

function chmod_file_folder($dir)
{
    $dh = @opendir($dir);
    chmod($dir, 0777);
    if ($dh) {

        while (false !== ($file = readdir($dh))) {

            if ($file != "." && $file != "..") {

                $fullpath = $dir . '/' . $file;
                if (!is_dir($fullpath)) {
                    chmod($fullpath, 0777);
                } else {
                    if (chmod($fullpath, 0777)) {
                        chmod_file_folder($fullpath);
                    }
                }
            }
        }
        closedir($dh);
    }
}

function copy_dir_with_files($src, $dst)
{

    // open the source directory
    $dir = opendir($src);

    // Make the destination directory if not exist
    @mkdir($dst);

    // Loop through the files in source directory
    while ($file = readdir($dir)) {

        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {

                // Recursively calling custom copy function
                // for sub directory
                custom_copy($src . '/' . $file, $dst . '/' . $file);

            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }

    closedir($dir);
}

function get_attachment_image_by_id($id, $size = null, $default = false)
{
    $image_details = MediaUpload::find($id);
    $return_val = [];
    $image_url = '';

    if (!empty($id) && !empty($image_details)) {
        switch ($size) {
            case "large":
                if (file_exists('assets/uploads/media-uploader/large-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/large-' . $image_details->path);
                }
                break;
            case "grid":
                if (file_exists('assets/uploads/media-uploader/grid-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/grid-' . $image_details->path);
                }
                break;
            case "thumb":
                if (file_exists('assets/uploads/media-uploader/thumb-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/thumb-' . $image_details->path);
                }
                break;
            default:
                if (is_numeric($id) && file_exists('assets/uploads/media-uploader/' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/' . $image_details->path);
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $image_details->id;
        $return_val['path'] = $image_details->path;
        $return_val['img_url'] = $image_url;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = asset('assets/uploads/no-image.png');
    }

    return $return_val;
}

function render_ratings($ratings)
{
    $return_val = '';
    switch ($ratings) {
        case(1):
            $return_val = '<i class="fas fa-star"></i>';
            break;
        case(2):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case(3):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case(4):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case(5):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        default:
            break;
    }
    return $return_val;
}

function get_product_ratings_avg_by_id($id)
{
    $average_ratings = ProductRatings::Where('product_id', $id)->pluck('ratings')->avg();
    return $average_ratings;
}

function setEnvValue(array $values)
{

    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            // If key does not exist, add it
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }
    }

    $str = substr($str, 0, -1);
    if (!file_put_contents($envFile, $str)) return false;
    return true;
}

//function nexelit_general_info(){
//    $all_fields = [
//        '' => url('/')
//    ];
//    $install_path = url('/')'';
//    $admin_path = '';
//    $frontend_path = '';
//    $nexelit_version = '';
//    $site_script_unique_key = 'NB2GLtODUjYOc9bFkPq2pKI8uma3G6WX';
//    update_static_option('site_install_path',url('/'));
//    update_static_option('site_admin_path',route('admin.home'));
//    update_static_option('site_frontend_path',route('homepage'));
//    $nexelit_version = version_compare(get_static_option('site_script_version'),'>=',getenv('XGENIOUS_NEXELIT_VERSION')) ? get_static_option('site_script_version') : getenv('XGENIOUS_NEXELIT_VERSION');
//    update_static_option('site_script_version',$nexelit_version);
//    update_static_option('site_script_unique_key',getenv('XGENIOUS_API_KEY'));
//}


function getJson($url)
{
    // cache files are created like cache/abcdef123456...
    $cacheFile = 'cache' . DIRECTORY_SEPARATOR . md5($url);

    if (file_exists($cacheFile)) {
        $fh = fopen($cacheFile, 'r');
        $cacheTime = trim(fgets($fh));

        // if data was cached recently, return cached data
        if ($cacheTime > strtotime('-60 minutes')) {
            return fread($fh);
        }

        // else delete cache file
        fclose($fh);
        unlink($cacheFile);
    }

    $json = file_get_contents($url);

    $fh = fopen($cacheFile, 'w');
    fwrite($fh, time() . "\n");
    fwrite($fh, $json);
    fclose($fh);

    return $json;
}

function render_menu_by_id($id)
{
    $default_lang = get_user_lang();
    $mega_menu_enable = '';

    if (empty($id)) {
        //load default home page if menu is empty
        return '<li><a href="' . url('/') . '">' . __('Home') . '</a></li>';
    }
    $output = '';
    $menu_details_from_db = Menu::find($id);


    $menu_content = json_decode($menu_details_from_db->content);
    if (empty($menu_content)) {
        //load default home page if menu is empty
        return '<li><a href="' . url('/') . '">' . __('Home') . '</a></li>';
    }
    foreach ($menu_content as $menu_item) {
        $li_class = '';
        //set li class if page is current page

        $mega_menu_ids = [];
        if (property_exists($menu_item, 'items_id')) {
            $mega_menu_ids = explode(',', $menu_item->items_id);
        }


        if ($menu_item->ptype == 'static') {
            $menu_title =  get_static_option($menu_item->pname);
            $menu_slug = url('/') . '/' . get_static_option($menu_item->pslug);
            $li_class .= (request()->path() == get_static_option($menu_item->pslug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'dynamic') {
            $menu_title = '';
            $menu_slug = '';
            $page_details = Page::find($menu_item->pid);
            if (!empty($page_details)){
                $menu_title = $page_details->title;
                $menu_slug = route('frontend.dynamic.page', $page_details->slug);
                $li_class .= (request()->is(route('frontend.dynamic.page', $page_details->slug))) ? ' current-menu-item ' : '';
            }

        } elseif ($menu_item->ptype == 'custom') {
            $menu_title = __($menu_item->pname);
            $menu_slug = str_replace('@url', url('/'), $menu_item->purl);
            $li_class .= (request()->is($menu_slug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'service' || $menu_item->ptype == 'event' || $menu_item->ptype == 'work' || $menu_item->ptype == 'blog' || $menu_item->ptype == 'job' || $menu_item->ptype == 'knowledgebase' || $menu_item->ptype == 'product' || $menu_item->ptype == 'donation') {

            if ($menu_item->ptype == 'service') {
                $menu_title = '';
                $menu_slug = '';

                $page_details = \App\Services::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.services.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.services.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }


            } elseif ($menu_item->ptype == 'event') {
                $menu_title = '';
                $menu_slug = '';

                $page_details = \App\Events::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.events.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.events.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }


            } elseif ($menu_item->ptype == 'work') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = Works::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.work.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.work.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }


            } elseif ($menu_item->ptype == 'blog') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Blog::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.blog.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.blog.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }


            } elseif ($menu_item->ptype == 'job') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Jobs::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.jobs.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.jobs.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }


            } elseif ($menu_item->ptype == 'knowledgebase') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Knowledgebase::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.knowledgebase.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.knowledgebase.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }


            } elseif ($menu_item->ptype == 'product') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Products::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.products.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.products.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }


            } elseif ($menu_item->ptype == 'donation') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Donation::find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.donations.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.donations.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            }
        } elseif ($menu_item->ptype == 'service_mega_menu') {
            $mega_menu_enable = 'mega_menu';
            $menu_title = get_static_option('service_page_' . $default_lang . '_name');
            $menu_raw_path = get_static_option('service_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';

            $mega_menu_items = \App\Services::find($mega_menu_ids)->groupBy('categories_id');
        } elseif ($menu_item->ptype == 'work_mega_menu') {
            $mega_menu_enable = 'mega_menu';
            $menu_title = get_static_option('work_page_' . $default_lang . '_name');
            $menu_raw_path = get_static_option('work_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Works::find($mega_menu_ids)->groupBy('categories_id');
        } elseif ($menu_item->ptype == 'event_mega_menu') {
            $mega_menu_enable = 'mega_menu';
            $menu_title = get_static_option('events_page_' . $default_lang . '_name');
            $menu_raw_path = get_static_option('work_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Events::find($mega_menu_ids)->groupBy('category_id');
        } elseif ($menu_item->ptype == 'product_mega_menu') {
            $menu_title = get_static_option('product_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('product_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Products::find($mega_menu_ids)->groupBy('category_id');
        } elseif ($menu_item->ptype == 'donation_mega_menu') {
            $menu_title = get_static_option('donation_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('donation_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Donation::find($mega_menu_ids);
        } elseif ($menu_item->ptype == 'blog_mega_menu') {
            $menu_title = get_static_option('blog_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('blog_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Blog::find($mega_menu_ids)->groupBy('blog_categories_id');
        } elseif ($menu_item->ptype == 'job_mega_menu') {
            $menu_title = get_static_option('career_with_us_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('career_with_us_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Jobs::find($mega_menu_ids)->groupBy('category_id');
        }

        $li_class .= property_exists($menu_item, 'children') ? ' menu-item-has-children ' : '';
        $li_class .= property_exists($menu_item, 'items_id') ? ' menu-item-has-mega-menu ' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        $li_class_markup = !empty($li_class) ? 'class="' . $li_class . '"' : '';
        //set li class if it has submenu
        $icon_value = property_exists($menu_item, 'icon') ? '<i class="' . $menu_item->icon . '"></i>' : '';
        $link_target = property_exists($menu_item, 'antarget') ? 'target="' . $menu_item->antarget . '"' : '';

        if (!empty($menu_slug) && !empty($menu_title)){//start condition

        $output .= $indent_tab . '<li ' . $li_class_markup . '>' . $indent_line;
        $output .= $indent_tab . '<a href="' . $menu_slug . '" '.$link_target.'>' . $icon_value . $menu_title . '</a>' . $indent_line;
        $user_select_lang_slug = get_user_lang();

        //check for megamenu
        if (!empty($mega_menu_enable)) {
            $output .= '<div class="xg_mega_menu_wrapper ' . $menu_item->ptype . '">';
            $output .= '<div class="xg-mega-menu-container"><div class="row">';
                if ($menu_item->ptype == 'donation_mega_menu'){
                    foreach ($mega_menu_items as $post) {
                        $output .= '<div class="col-lg-4 col-md-6">';
                        $mega_menu_item_slug = get_mege_menu_item_url($menu_item->ptype,$post->slug);
                        $output .= '<div class="single-donation-mega-menu-item">';
                        $output .= '<div class="thumbnail"><a href="'.$mega_menu_item_slug.'">'.render_image_markup_by_attachment_id($post->image,'','thumb').'</a></div>';
                        $output .= '<div class="content">';
                        $output .= '<a href="'.$mega_menu_item_slug.'"><h4 class="title">'.$post->title.'</h4></a>';

                        $output .= '<div class="goal">';
                        $output .=  '<h4 class="raised">'.get_static_option('donation_raised_'.$user_select_lang_slug.'_text');
                        if(!empty($post->raised)){
                            $output .= amount_with_currency_symbol($post->raised);
                        }else{
                            $output .= amount_with_currency_symbol(0);
                        }
                        $output .='</h4>';
                        $output .= '<h4 class="raised">'.get_static_option('donation_goal_'.$user_select_lang_slug.'_text').''.amount_with_currency_symbol($post->amount).'</h4>';
                        $output .= '</div>';
                        $output .= ' <a href="'.route('frontend.donations.single',$post->slug).'" class="boxed-btn">'.get_static_option('donation_button_'.$user_select_lang_slug.'_text').'</a>';
                        $output .= '</div></div></div>';
                    }
                }elseif($menu_item->ptype == 'product_mega_menu'){
                    foreach ($mega_menu_items as $cat => $posts) {
                        $output .= '<div class="col-lg-3 col-md-6"><div class="xg-mega-menu-single-column-wrap">';
                        $output .= '<h4 class="mega-menu-title">' . get_mega_menu_cat_name_by_id($menu_item->ptype, $cat) . '</h4>';
                        $output .= '<ul>';
                        foreach ($posts as $post) {
                            $mega_menu_item_slug = get_mege_menu_item_url($menu_item->ptype,$post->slug);
                            $output .= '<li class="single-mega-menu-product-item">';
                            $output .= '<div class="thumbnail"><a href="'.$mega_menu_item_slug.'">'.render_image_markup_by_attachment_id($post->image,'','thumb').'</a></div>';
                            $output .= '<div class="content">';
                            $output .= '<a href="'.$mega_menu_item_slug.'"><h4 class="title">'.$post->title.'</h4></a>';
                            $output .= '<div class="price-wrap"><span class="price">'.amount_with_currency_symbol($post->sale_price).'</span>';
                            if(!empty($post->regular_price)){
                                $output .= '<del class="del-price">'.amount_with_currency_symbol($post->regular_price).'</del>';
                            }
                            $output .= '</div></div>';
                            $output .= '</li>';
                        }
                        $output .= '</ul>';
                        $output .= '</div></div>';
                    }
                }else{
                    foreach ($mega_menu_items as $cat => $posts) {
                        $output .= '<div class="col-lg-3 col-md-6"><div class="xg-mega-menu-single-column-wrap">';
                        $output .= '<h4 class="mega-menu-title">' . get_mega_menu_cat_name_by_id($menu_item->ptype, $cat) . '</h4>';
                        $output .= '<ul>';
                        foreach ($posts as $post) {
                            $mega_menu_item_slug = get_mege_menu_item_url($menu_item->ptype,$post->slug);
                            $output .= '<li><a href="'.$mega_menu_item_slug.'">' . $post->title . '</a></li>';
                        }
                        $output .= '</ul>';
                        $output .= '</div></div>';
                    }
                }
            $output .= '</div></div></div>';
            $mega_menu_enable = '';
        }
        //check it has submenu
        if (property_exists($menu_item, 'children')) {
            $output .= render_submenu_children($menu_item->children);
        }
        //load li end tag
        $output .= $indent_tab . '</li>' . $indent_line;
        }// end condition
    }

    return $output;
}

/* render submenu */


function render_submenu_children($menu_children)
{
    $indent_line = "\n";
    $indent_tab = "\t";

    $output = $indent_tab . '<ul class="sub-menu">' . $indent_line;
    foreach ($menu_children as $menu_item) {

        $li_class = '';
        //set li class if page is current page

        if ($menu_item->ptype == 'static') {
            $menu_title = get_static_option($menu_item->pname);
            $menu_slug = url('/') . '/' . get_static_option($menu_item->pslug);
            $li_class .= (request()->path() == get_static_option($menu_item->pslug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'dynamic') {
            $page_details = Page::find($menu_item->pid);
            $menu_title = !empty($page_details) ? $page_details->title: '';
            $menu_slug = !empty($page_details) ? route('frontend.dynamic.page', $page_details->slug) : '';
            if (!empty($page_details)){
                $li_class .= (request()->is(route('frontend.dynamic.page', $page_details->slug)))   ? ' current-menu-item ' : '';
            }
        } elseif ($menu_item->ptype == 'custom') {
            $menu_title = __($menu_item->pname);
            $menu_slug = str_replace('@url', url('/'), $menu_item->purl);
            $li_class .= (request()->is($menu_slug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'service' || $menu_item->ptype == 'event' || $menu_item->ptype == 'work' || $menu_item->ptype == 'blog' || $menu_item->ptype == 'job' || $menu_item->ptype == 'knowledgebase' || $menu_item->ptype == 'product' || $menu_item->ptype == 'donation') {
            $menu_title = '';
            $menu_slug = '';
            if ($menu_item->ptype == 'service') {

                $page_details = \App\Services::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.services.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.services.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'event') {

                $page_details = \App\Events::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.events.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.events.single', $page_details->slug))) ? ' current-menu-item ' : '';
                 }

            } elseif ($menu_item->ptype == 'work') {

                $page_details = Works::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.work.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.work.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'blog') {

                $page_details = \App\Blog::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.blog.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.blog.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'job') {

                $page_details = \App\Jobs::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.jobs.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.jobs.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'knowledgebase') {

                $page_details = \App\Knowledgebase::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = !empty($page_details) ? $page_details->title : '';
                    $menu_slug = route('frontend.knowledgebase.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.knowledgebase.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'product') {

                $page_details = \App\Products::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = !empty($page_details) ? $page_details->title : '';
                    $menu_slug = route('frontend.products.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.products.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'donation') {
                $page_details = \App\Donation::find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.donations.single', $page_details->slug);
                    $li_class .= (request()->is(route('frontend.donations.single', $page_details->slug))) ? ' current-menu-item ' : '';
                }

            }
        }


        $li_class .= property_exists($menu_item, 'children') ? ' menu-item-has-children ' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        $li_class_markup = !empty($li_class) ? 'class="' . $li_class . '"' : '';
        //set li class if it has submenu
        $icon_value = property_exists($menu_item, 'icon') ? '<i class="' . $menu_item->icon . '"></i>' : '';
        $link_target = property_exists($menu_item, 'antarget') ? 'target="' . $menu_item->antarget . '"' : '';

        if (!empty($menu_slug) && !empty($menu_title)){
            $output .= $indent_tab . '<li ' . $li_class_markup . '>' . $indent_line;
            $output .= $indent_tab . '<a href="' . $menu_slug . '" '.$link_target.'>' . $icon_value . $menu_title . '</a>' . $indent_line;
        }
        //check it has submenu
        if (property_exists($menu_item, 'children')) {
            $output .= render_submenu_children($menu_item->children);
        }
        //load li end tag
        $output .= $indent_tab . '</li>' . $indent_line;
    }
    $output .= $indent_tab . '</ul>' . $indent_line;
    return $output;
}



/* render menu for drag & drop menu in admin panel */
function render_draggable_menu_by_id($id)
{
    $default_lang = get_default_language();


    $mega_menu_enable = '';
    $mega_menu_items = '';
    $output = '';
    $menu_details_from_db = Menu::find($id);
    $default_lang = !empty($menu_details_from_db) ? $menu_details_from_db->lang : $default_lang;

    $menu_data = json_decode($menu_details_from_db->content);

    $page_id = 0;
    foreach ($menu_data as $menu):
        $page_id++;

        $menu_title = '';
        $menu_attr = 'data-ptype="' . $menu->ptype . '" ';

        if ($menu->ptype == 'static') {
            $menu_attr .= ' data-pname="' . $menu->pname . '"';
            $menu_attr .= ' data-pslug="' . $menu->pslug . '"';
            $menu_title = get_static_option($menu->pname);

        } elseif ($menu->ptype == 'dynamic') {

            $menu_attr .= ' data-pid="' . $menu->pid . '"';
            $menu_details = Page::find($menu->pid);
            $menu_title = !empty($menu_details) ? $menu_details->title : '' ;

        } elseif ($menu->ptype == 'custom') {
            $menu_attr .= ' data-purl="' . $menu->purl . '"';
            $menu_attr .= ' data-pname="' . $menu->pname . '"';
            $menu_title = $menu->pname;
        } elseif ($menu->ptype == 'service' || $menu->ptype == 'event' || $menu->ptype == 'work' || $menu->ptype == 'blog' || $menu->ptype == 'job' || $menu->ptype == 'knowledgebase' || $menu->ptype == 'product' || $menu->ptype == 'donation') {
            $menu_attr .= ' data-pid="' . $menu->pid . '"';

            if ($menu->ptype == 'service') {
                $menu_details = \App\Services::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '' ;
            } elseif ($menu->ptype == 'event') {
                $menu_details = \App\Events::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '' ;
            } elseif ($menu->ptype == 'work') {
                $menu_details = \App\Works::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title :'' ;
            } elseif ($menu->ptype == 'blog') {
                $menu_details = \App\Blog::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '' ;
            } elseif ($menu->ptype == 'job') {
                $menu_details = \App\Jobs::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '' ;
            } elseif ($menu->ptype == 'knowledgebase') {
                $menu_details = \App\Knowledgebase::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '' ;
            } elseif ($menu->ptype == 'product') {
                $menu_details = \App\Products::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '' ;
            } elseif ($menu->ptype == 'donation') {
                $menu_details = \App\Donation::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '' ;
            }

        } elseif ($menu->ptype == 'service_mega_menu') {
            $menu_title = get_static_option('service_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Services::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($menu->ptype == 'work_mega_menu') {
            $menu_title = get_static_option('work_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Works::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($menu->ptype == 'event_mega_menu') {
            $menu_title = get_static_option('events_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Events::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($menu->ptype == 'product_mega_menu') {
            $menu_title = get_static_option('product_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Products::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($menu->ptype == 'donation_mega_menu') {
            $menu_title = get_static_option('donation_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Donation::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($menu->ptype == 'blog_mega_menu') {
            $menu_title = get_static_option('blog_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Blog::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($menu->ptype == 'job_mega_menu') {
            $menu_title = get_static_option('career_with_us_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Jobs::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        }

        $mega_menu_ids = [];
        if (property_exists($menu, 'items_id')) {
            $mega_menu_ids = explode(',', $menu->items_id);
            $menu_attr .= ' data-items_id="' . $menu->items_id . '" ';
        }

        $icon_value = property_exists($menu, 'icon') ? 'value="' . $menu->icon . '"' : '';
        $link_target = property_exists($menu, 'antarget') ? 'value="' . $menu->antarget . '"' : '';

        $icon_data = property_exists($menu, 'icon') ? 'data-icon="' . $menu->icon . '"' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        if (!empty($menu_title)){
            $output .= '<li class="dd-item" data-id="' . $page_id . '" ' . $menu_attr . ' ' . $icon_data . '>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-handle">' . $menu_title . '</div>' . $indent_line;
            $output .= $indent_tab . '<span class="remove_item">x</span>' . $indent_line;
            $output .= $indent_tab . '<span class="expand"><i class="ti-angle-down"></i></span>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-body hide">';
        }

        //add mega menu extra field here
        if (!empty($mega_menu_enable)) {
            $output .= '<label for="items_id">' . __('Select Items') . '</label>';
            $output .= '<select name="items_id" multiple class="form-control">';
            foreach ($mega_menu_items as $data):
                $selected = in_array($data->id, $mega_menu_ids) ? 'selected' : '';
                $output .= '<option value="' . $data->id . '" ' . $selected . ' >' . $data->title . '</option>';
            endforeach;
            $output .= '</select>';
            $mega_menu_enable = '';
        } else {
            if (!empty($menu_title)){
                $output .= '<input type="text" class="anchor_target" placeholder="eg: _target" ' . $link_target . '/>';
                $output .= '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook" ' . $icon_value . '/>';
            }
        }
        if (!empty($menu_title)) {
            $output .= '</div>' . $indent_line;
        }

        //check it has children or not
        if (property_exists($menu, 'children')) {
            $output .= render_draggable_menu_children($menu->children, $page_id);
        }
        $output .= '</li>' . $indent_line;

    endforeach;
    return $output;
}

/* render submenu of menu for drag & drop menu in admin panel */
function render_draggable_menu_children($children, $page_id)
{
    $indent_line = "\n";
    $indent_tab = "\t";

    $output = $indent_tab . '<ol class="dd-list">' . $indent_line;
    foreach ($children as $item) {
        $page_id++;
        $menu_title = '';
        $menu_attr = 'data-ptype="' . $item->ptype . '" ';

        if ($item->ptype == 'static') {

            $menu_attr .= ' data-pname="' . $item->pname . '"';
            $menu_attr .= ' data-pslug="' . $item->pslug . '"';
            $menu_title = get_static_option($item->pname);

        } elseif ($item->ptype == 'dynamic') {

            $menu_attr .= ' data-pid="' . $item->pid . '"';
            $menu_details = Page::find($item->pid);
            $menu_title = !empty($menu_details) ? $menu_details->title : '';

        } elseif ($item->ptype == 'custom') {
            $menu_attr .= ' data-purl="' . $item->purl . '"';
            $menu_attr .= ' data-pname="' . $item->pname . '"';
            $menu_title = $item->pname;
        } elseif ($item->ptype == 'service' || $item->ptype == 'event' || $item->ptype == 'work' || $item->ptype == 'blog' || $item->ptype == 'job' || $item->ptype == 'knowledgebase' || $item->ptype == 'product' || $item->ptype == 'donation') {
            $menu_attr .= ' data-pid="' . $item->pid . '"';

            if ($item->ptype == 'service') {
                $menu_details = \App\Services::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            } elseif ($item->ptype == 'event') {
                $menu_details = \App\Events::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            } elseif ($item->ptype == 'work') {
                $menu_details = \App\Works::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            } elseif ($item->ptype == 'blog') {
                $menu_details = \App\Blog::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            } elseif ($item->ptype == 'job') {
                $menu_details = \App\Jobs::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            } elseif ($item->ptype == 'knowledgebase') {
                $menu_details = \App\Knowledgebase::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            } elseif ($item->ptype == 'product') {
                $menu_details = \App\Products::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            } elseif ($item->ptype == 'donation') {
                $menu_details = \App\Donation::find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->title : '';
            }
        }
        $icon_value = property_exists($item, 'icon') ? 'value="' . $item->icon . '"' : '';
        $icon_data = property_exists($item, 'icon') ? 'data-icon="' . $item->icon . '"' : '';
        $link_target = property_exists($item, 'antarget') ? 'value="' . $item->antarget . '"' : '';

        if (!empty($menu_title)){
            $output .= $indent_tab . $indent_tab . '<li class="dd-item" data-id="' . $page_id . '" ' . $menu_attr . ' ' . $icon_data . '>' . $indent_line;
            $output .= $indent_tab . $indent_tab . $indent_tab . '<div class="dd-handle">' . $menu_title . '</div>' . $indent_line;
            $output .= $indent_tab . $indent_tab . $indent_tab . '<span class="remove_item">x</span>' . $indent_line;
            $output .= $indent_tab . '<span class="expand"><i class="ti-angle-down"></i></span>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-body hide">';

            $output .= '<input type="text" class="anchor_target" placeholder="eg: _blank" ' . $link_target . '/>';
            $output .= '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook" ' . $icon_value . '/>';
            $output .= '</div>' . $indent_line;
        }

        if (property_exists($item, 'children')) {
            $output .= render_draggable_menu_children($item->children, $page_id);
        }
        if (!empty($menu_title)) {
            $output .= $indent_tab . $indent_tab . '</li>' . $indent_line;
        }
    }
    $output .= $indent_tab . '</ol>' . $indent_line;
    return $output;
}

function render_mega_menu_item_select_markup($type = '',$menu_id = null)
{
    $output = '';
    $default_lang = get_default_language();
    $menu_details = !empty($menu_id) ? Menu::find($menu_id) : '';
    $default_lang = !empty($menu_details) ? $menu_details->lang : $default_lang;
    if (!empty($type)) {
        if ($type == 'service_mega_menu') {
            $mega_menu_items = \App\Services::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($type == 'work_mega_menu') {
            $mega_menu_items = \App\Works::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($type == 'event_mega_menu') {
            $mega_menu_items = \App\Events::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($type == 'product_mega_menu') {
            $mega_menu_items = \App\Products::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($type == 'donation_mega_menu') {
            $mega_menu_items = \App\Donation::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($type == 'blog_mega_menu') {
            $mega_menu_items = \App\Blog::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        } elseif ($type == 'job_mega_menu') {
            $mega_menu_items = \App\Jobs::where(['lang' => $default_lang, 'status' => 'publish'])->get();
        }
        $output .= '<label for="items_id">' . __('Select Items') . '</label>';
        $output .= '<select name="items_id" multiple class="form-control">';
        foreach ($mega_menu_items as $data):
            $output .= '<option value="' . $data->id . '" >' . $data->title . '</option>';
        endforeach;
        $output .= '</select>';
    }
    return $output;
}

function render_image_markup_by_attachment_id($id, $class = null, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $class_list = !empty($class) ? 'class="' . $class . '"' : '';
        $output = '<img src="' . $image_details['img_url'] . '" ' . $class_list . ' alt=""/>';
    }
    return $output;
}

function render_background_image_markup_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = 'style="background-image: url(' . $image_details['img_url'] . ');"';
    }
    return $output;
}

function render_og_meta_image_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = ' <meta property="og:image" content="' . $image_details['img_url'] . '" />';
    }
    return $output;
}


function render_embed_google_map($address, $zoom = 10)
{
    if (empty($address)) {
        return;
    }
    printf(
        '<div class="elementor-custom-embed"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe></div>',
        rawurlencode($address),
        $zoom,
        $address
    );
}

function render_drag_drop_form_builder_markup($content = '')
{
    $output = '';

    $form_fields = json_decode($content);
    $output .= '<ul id="sortable" class="available-form-field main-fields">';
    if (!empty($form_fields)) {
        $select_index = 0;
        foreach ($form_fields->field_type as $key => $ftype) {
            $args = [];
            $required_field = '';
            if (property_exists($form_fields, 'field_required')) {
                $filed_requirement = (array)$form_fields->field_required;
                $required_field = !empty($filed_requirement[$key]) ? 'on' : '';
            }
            if ($ftype == 'select') {
                $args['select_option'] = isset($form_fields->select_options[$select_index]) ? $form_fields->select_options[$select_index] : '';
                $select_index++;
            }
            if ($ftype == 'file') {
                $args['mimes_type'] = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            }
            $output .= render_drag_drop_form_builder_field_markup($key, $ftype, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $required_field, $args);
        }
    } else {
        $output .= render_drag_drop_form_builder_field_markup('1', 'text', 'your-name', 'Your Name', '');
    }

    $output .= '</ul>';
    return $output;
}

function render_drag_drop_form_builder_field_markup($key, $type, $name, $placeholder, $required, $args = [])
{
    $required_check = !empty($required) ? 'checked' : '';
    $output = '<li class="ui-state-default">
                     <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="remove-fields">x</span>
                    <a data-toggle="collapse" href="#fileds_collapse_' . $key . '" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        ' . ucfirst($type) . ': <span
                                class="placeholder-name">' . $placeholder . '</span>
                    </a>';
    $output .= '<div class="collapse" id="fileds_collapse_' . $key . '">
            <div class="card card-body margin-top-30">
                <input type="hidden" class="form-control" name="field_type[]"
                       value="' . $type . '">
                <div class="form-group">
                    <label>' . __('Name') . '</label>
                    <input type="text" class="form-control " name="field_name[]"
                           placeholder="' . __('enter field name') . '"
                           value="' . $name . '" >
                </div>
                <div class="form-group">
                    <label>' . __('Placeholder/Label') . '</label>
                    <input type="text" class="form-control field-placeholder"
                           name="field_placeholder[]" placeholder="' . __('enter field placeholder/label') . '"
                           value="' . $placeholder . '" >
                </div>
                <div class="form-group">
                    <label ><strong>' . __('Required') . '</strong></label>
                    <label class="switch">
                        <input type="checkbox" class="field-required" ' . $required_check . ' name="field_required[' . $key . ']">
                        <span class="slider onff"></span>
                    </label>
                </div>';
    if ($type == 'select') {
        $output .= '<div class="form-group">
                        <label>' . __('Options') . '</label>
                            <textarea name="select_options[]" class="form-control max-height-120" cols="30" rows="10"
                                required>' . $args['select_option'] . '</textarea>
                           <small>' . __('separate option by new line') . '</small>
                    </div>';
    }
    if ($type == 'file') {
        $output .= '<div class="form-group"><label>' . __('File Type') . '</label><select name="mimes_type[' . $key . ']" class="form-control mime-type">';
        $output .= '<option value="mimes:jpg,jpeg,png"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:jpg,jpeg,png') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:jpg,jpeg,png') . '</option>';

        $output .= '<option value="mimes:txt,pdf"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:txt,pdf') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:txt,pdf') . '</option>';

        $output .= '<option value="mimes:doc,docx"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:mimes:doc,docx') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:mimes:doc,docx') . '</option>';

        $output .= '</select></div>';
    }
    $output .= '</div></div></li>';

    return $output;
}


function render_form_field_for_frontend($form_content)
{
    if (empty($form_content)) {
        return;
    }
    $output = '';
    $form_fields = json_decode($form_content);
    $select_index = 0;
    $options = [];

    foreach ($form_fields->field_type as $key => $value) {
        if (!empty($value)) {
            if ($value == 'select') {
                $options = explode("\n", $form_fields->select_options[$select_index]);
            }
            $required = isset($form_fields->field_required->$key) ? $form_fields->field_required->$key : '';
            $mimes = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            $output .= get_field_by_type($value, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $options, $required, $mimes);
            if ($value == 'select') {
                $select_index++;
            };
        }
    }

    return $output;
}


function render_favicon_by_id($id)
{

    $site_favicon = get_attachment_image_by_id($id, "full", false);
    $output = '';
    if (!empty($site_favicon)) {
        $output .= '<link rel="icon" href="' . $site_favicon['img_url'] . '" type="image/png">';
    }
    return $output;
}

function get_user_name_by_id($id)
{
    $user = \App\User::find($id);
    return $user;
}

function get_price_plan_category_name_by_id($id)
{
    $cat = \App\PricePlanCategory::findOrFail($id);
    return $cat->name;
}

function get_percentage($amount, $numb)
{
    if ($amount > 0) {
        return round($numb / ($amount / 100), 2);
    }
    return 0;
}

function get_cart_items()
{
    $old_cart_item = session()->get('cart_item');
    $return_val = !empty($old_cart_item) ? $old_cart_item : [];

    return $return_val;
}

function render_cart_table()
{
    $ajax_preloader = '<div class="ajax-loading-wrap hide">
                        <div class="sk-fading-circle">
                            <div class="sk-circle1 sk-circle"></div>
                            <div class="sk-circle2 sk-circle"></div>
                            <div class="sk-circle3 sk-circle"></div>
                            <div class="sk-circle4 sk-circle"></div>
                            <div class="sk-circle5 sk-circle"></div>
                            <div class="sk-circle6 sk-circle"></div>
                            <div class="sk-circle7 sk-circle"></div>
                            <div class="sk-circle8 sk-circle"></div>
                            <div class="sk-circle9 sk-circle"></div>
                            <div class="sk-circle10 sk-circle"></div>
                            <div class="sk-circle11 sk-circle"></div>
                            <div class="sk-circle12 sk-circle"></div>
                        </div>
                    </div>';

    $output = '';
    $all_cart_item = session()->get('cart_item');
    if (!empty($all_cart_item)) {
        $output = '<div class="table-responsive cart-table"><form id="cart_update_form" method="post"><table class="table table-bordered">';
        $output .= "\t" . '<thead><tr>';

        $output .= "\n\t" . '<th>' . __('Serial') . '</th>';
        $output .= "\n\t" . '<th>' . __('Thumbnail') . '</th>';
        $output .= "\n\t" . '<th>' . __('Product Name') . '</th>';
        $output .= "\n\t" . '<th>' . __('Quantity') . '</th>';
        $output .= "\n\t" . '<th>' . __('Unit Price') . '</th>';
        if (get_static_option('product_tax_type') == 'individual') {
            $output .= "\n\t" . '<th>' . __('Tax') . '</th>';
        }
        $output .= "\n\t" . '<th>' . __('Subtotal') . '</th>';
        $output .= "\n\t" . '<th>' . __('Action') . '</th>';
        $output .= "\n\t" . '</tr></thead>';

        $output .= "\n\t" . '<tbody>';
        $a = 1;
        foreach ($all_cart_item as $id => $item) {


            $single_product = \App\Products::find($id);
            $colspan = 7;
            if (empty($single_product)) {
                continue;
            }

            $tax_markup = !empty($single_product->tax_percentage) ? '<small>+' . __('Tax') . ' (' . $single_product->tax_percentage . '%)</small>' : '';
            $output .= '<tr>';
            $output .= '<td>' . $a . '<input name="product_id[]" type="hidden" value="' . $id . '">' . '</td>';
            $output .= '<td><div class="thumbnail">' . render_image_markup_by_attachment_id($single_product->image, '', 'thumb') . '</div></td>';
            $output .= '<td><h4 class="product-title"><a href="' . route('frontend.products.single', $single_product->slug) . '">' . $single_product->title . '</a></h4></td>';
            $output .= '<td><input type="number" name="product_quantity[]" class="quantity" value="' . $item['quantity'] . '"></td>';
            $output .= '<td class="unit_price">' . amount_with_currency_symbol($single_product->sale_price) . '</td>';
            $tax_amount = 0;
            if (get_static_option('product_tax_type') == 'individual') {
                $tax_amount = ($single_product->sale_price / 100) * $single_product->tax_percentage;
                $output .= '<td class="tax_amount">' . amount_with_currency_symbol($tax_amount) . '(' . $single_product->tax_percentage . '%)</td>';
                $colspan = 8;
            }
            $subtotal = (get_static_option('product_tax_type') == 'individual') ? $item['price'] + ($tax_amount * $item['quantity']) : $item['price'];
            $output .= '<td>' . amount_with_currency_symbol($subtotal) . '</td>';
            $output .= '<td><div class="cart-action-wrap"><a href="#" class="btn btn-sm btn-danger ajax_remove_cart_item"  data-product_id="' . $single_product->id . '"><i class="fas fa-trash-alt"></i></a>' . $ajax_preloader . '</div></td>';
            $output .= '</tr>';
            $a++;
        }

        $output .= "\n\t" . '</tbody>';
        $output .= "\n\t" . '<tfoot>';
        $output .= '<tr><td colspan="' . $colspan . '">';
        $output .= '<div class="cart-table-footer-wrap">';
        $output .= '<div class="coupon-wrap"><input type="text" class="form-control" name="coupon_code" placeholder="' . __('Coupon Code') . '"><button class="btn-boxed add_coupon_code_btn">' . __('Submit') . '</button>' . $ajax_preloader . '</div>';
        $output .= '<div class="update-cart-wrap">' . $ajax_preloader . '<button class="btn-boxed update_cart_items_btn">' . __('Update Cart') . '</button></div>';
        $output .= '</div>';
        $output .= '</td></tr>';
        $output .= "\n\t" . '</tfoot>';

        $output .= '</table></form></div>';
        return $output;
    }

    $output = '<div class="alert alert-warning">' . __('No Item In Cart!') . '</div>';
    return $output;
}

function cart_destroy($key)
{
    session()->forget($key);
}

function cart_total_items()
{
    $return_val = session()->get('cart_item');
    return !empty($return_val) ? array_sum(array_column($return_val, 'quantity')) : 0;
}
function is_shipping_available(){
    $all_cart_item = session()->get('cart_item');
    $return_val = true;
    $cart_item_type = !empty($all_cart_item) ? array_unique(array_column($all_cart_item,'type')) : [];
    if (count($cart_item_type)  == 1 && in_array('digital',$cart_item_type)){
        $return_val = false;
    }

    return $return_val;
}
function get_cart_tax()
{
    $tax_percentage = get_static_option('product_tax_percentage') ? get_static_option('product_tax_percentage') : 0;
    $cart_sub_total = get_cart_subtotal(false);
    $get_coupon_discount = session()->get('coupon_discount');

    $return_val = $cart_sub_total;

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $discount_bal = ($cart_sub_total / 100) * intval($coupon_details->discount);
            $return_val = $cart_sub_total - $discount_bal;
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $cart_sub_total - intval($coupon_details->discount);
        }
    }

    $tax_amount = ($return_val / 100) * intval($tax_percentage);

    if (get_static_option('product_tax_type') == 'individual') {
        //write code for all individual tax amount and sum all of them
        $all_cart_items = session()->get('cart_item');
        $all_individual_tax = [];
        foreach ($all_cart_items as $item) {
            $product_details = \App\Products::find($item['id']);
            if (empty($product_details)) {
                continue;
            }
            $price = $product_details->sale_price * $item['quantity'];
            $tax_percentage = ($price / 100) * $product_details->tax_percentage;
            array_push($all_individual_tax, $tax_percentage);
        }
        $tax_amount = array_sum($all_individual_tax);

    }

    return $tax_amount;
}

function render_cart_total_table()
{
    $output = '';

    $car_total = cart_total_items();
    if ($car_total > 0) {
        $output .= '<h4 class="title">' . __('Order Summery') . '</h4><div class="cart-total-table-wrap">';
        $output .= ' <div class="cart-total-table table-responsive"><table class="table table-bordered"> <tbody>';
        $output .= ' <tr><th>' . __('Subtotal') . '</th><td>' . get_cart_subtotal() . '</td></tr>';
        $output .= ' <tr><th>' . __('Coupon Discount') . '</th><td>-' . get_cart_coupon_discount() . '</td></tr>';
        if (is_tax_enable()) {
            $tax_percentage = get_static_option('product_tax_type') == 'total' ? ' (' . get_static_option('product_tax_percentage') . '%)' : '';
            $output .= ' <tr><th>' . __('Tax') . $tax_percentage . '</th><td>+ ' . amount_with_currency_symbol(get_cart_tax()) . '</td></tr>';
        }
        if (is_shipping_available()) {
            $output .= ' <tr><th>' . __('Shipping Cost') . '</th><td>+ ' . get_cart_shipping_cost() . '</td></tr>';
        }

        $output .= ' <tr><th>' . __('Total') . '</th><td><strong>' . get_cart_total_cost() . '</strong></td></tr>';
        $output .= '</tbody></table></div>';
        $output .= '</div><a href="' . route('frontend.products.checkout') . '" class="btn-boxed">' . __('Process To Checkout') . '</a></div>';
    }

    return $output;
}
function is_tax_enable()
{
    return get_static_option('product_tax') && get_static_option('product_tax_system') == 'exclusive'  ? true : false;
}
function get_cart_subtotal($currency_symbol = true)
{
    $total_cart_items = session()->get('cart_item');
    $return_val = $currency_symbol ? amount_with_currency_symbol(0) : 0;
    if (!empty($total_cart_items)) {
        $return_val = 0;
        foreach ($total_cart_items as $product_id => $cat_data) {
            $return_val += intval($cat_data['price']);
        }
        return $currency_symbol ? amount_with_currency_symbol($return_val) : $return_val;
    }

    return $return_val;
}

function get_cart_coupon_discount_by_code($symbol = true, $code)
{
    $return_val = $symbol ? amount_with_currency_symbol(0) : 0;
    if (!empty($code)) {
        return $return_val;
    }
    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $code)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $return_val = $symbol ? $coupon_details->discount . '%' : intval($coupon_details->discount);
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $symbol ? amount_with_currency_symbol($coupon_details->discount) : intval($coupon_details->discount);
        }
    }

    return $return_val;
}

function get_cart_coupon_discount($symbol = true)
{
    $get_coupon_discount = session()->get('coupon_discount');
    $return_val = $symbol ? amount_with_currency_symbol(0) : 0;

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $return_val = $symbol ? $coupon_details->discount . '%' : intval($coupon_details->discount);
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $symbol ? amount_with_currency_symbol($coupon_details->discount) : intval($coupon_details->discount);
        }
    }

    return $return_val;
}

function get_cart_shipping_cost($symbol = true)
{
    $get_shipping_charge = session()->get('shipping_charge');
    $return_val = $symbol ? amount_with_currency_symbol(0) : 0;

    if (!empty($get_shipping_charge)) {
        $shipping_details = \App\ProductShipping::where('id', $get_shipping_charge)->first();
        $shipping_details = !empty($shipping_details) ? $shipping_details : 0;
        $return_val = $symbol ? amount_with_currency_symbol($shipping_details->cost) : intval($shipping_details->cost);
    }
    return is_shipping_available() ? $return_val : 0;
}


function get_cart_total_cost($symbol = true)
{
    $cart_sub_total = get_cart_subtotal(false);
    $get_coupon_discount = session()->get('coupon_discount');
    $get_shipping_id = session()->get('shipping_charge');
    $shipping_details = \App\ProductShipping::where('id', $get_shipping_id)->first();
    $get_shipping_charge = !empty($shipping_details) && is_shipping_available() ? $shipping_details->cost : 0;

    $return_val = $symbol ? amount_with_currency_symbol($cart_sub_total + $get_shipping_charge + get_cart_tax()) : $cart_sub_total + $get_shipping_charge + get_cart_tax();

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $discount_bal = ($cart_sub_total / 100) * intval($coupon_details->discount);
            $return_val = $cart_sub_total - $discount_bal;
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $cart_sub_total - intval($coupon_details->discount);
        }

        $total_cost = $return_val + $get_shipping_charge + get_cart_tax();
        return $symbol ? amount_with_currency_symbol($total_cost) : $total_cost;
    }

    return $return_val;
}

function get_country_field($name, $id, $class)
{
    return '<select name="' . $name . '" id="' . $id . '" class="' . $class . '"><option value="">' . __('Select Country') . '</option><option value="Afghanistan" >Afghanistan</option><option value="Albania" >Albania</option><option value="Algeria" >Algeria</option><option value="American Samoa" >American Samoa</option><option value="Andorra" >Andorra</option><option value="Angola" >Angola</option><option value="Anguilla" >Anguilla</option><option value="Antarctica" >Antarctica</option><option value="Antigua and Barbuda" >Antigua and Barbuda</option><option value="Argentina" >Argentina</option><option value="Armenia" >Armenia</option><option value="Aruba" >Aruba</option><option value="Australia" >Australia</option><option value="Austria" >Austria</option><option value="Azerbaijan" >Azerbaijan</option><option value="Bahamas" >Bahamas</option><option value="Bahrain" >Bahrain</option><option value="Bangladesh" >Bangladesh</option><option value="Barbados" >Barbados</option><option value="Belarus" >Belarus</option><option value="Belgium" >Belgium</option><option value="Belize" >Belize</option><option value="Benin" >Benin</option><option value="Bermuda" >Bermuda</option><option value="Bhutan" >Bhutan</option><option value="Bolivia" >Bolivia</option><option value="Bosnia and Herzegovina" >Bosnia and Herzegovina</option><option value="Botswana" >Botswana</option><option value="Bouvet Island" >Bouvet Island</option><option value="Brazil" >Brazil</option><option value="British Indian Ocean Territory" >British Indian Ocean Territory</option><option value="Brunei Darussalam" >Brunei Darussalam</option><option value="Bulgaria" >Bulgaria</option><option value="Burkina Faso" >Burkina Faso</option><option value="Burundi" >Burundi</option><option value="Cambodia" >Cambodia</option><option value="Cameroon" >Cameroon</option><option value="Canada" >Canada</option><option value="Cape Verde" >Cape Verde</option><option value="Cayman Islands" >Cayman Islands</option><option value="Central African Republic" >Central African Republic</option><option value="Chad" >Chad</option><option value="Chile" >Chile</option><option value="China" >China</option><option value="Christmas Island" >Christmas Island</option><option value="Cocos (Keeling) Islands" >Cocos (Keeling) Islands</option><option value="Colombia" >Colombia</option><option value="Comoros" >Comoros</option><option value="Cook Islands" >Cook Islands</option><option value="Costa Rica" >Costa Rica</option><option value="Croatia (Hrvatska)" >Croatia (Hrvatska)</option><option value="Cuba" >Cuba</option><option value="Cyprus" >Cyprus</option><option value="Czech Republic" >Czech Republic</option><option value="Democratic Republic of the Congo" >Democratic Republic of the Congo</option><option value="Denmark" >Denmark</option><option value="Djibouti" >Djibouti</option><option value="Dominica" >Dominica</option><option value="Dominican Republic" >Dominican Republic</option><option value="East Timor" >East Timor</option><option value="Ecuador" >Ecuador</option><option value="Egypt" >Egypt</option><option value="El Salvador" >El Salvador</option><option value="Equatorial Guinea" >Equatorial Guinea</option><option value="Eritrea" >Eritrea</option><option value="Estonia" >Estonia</option><option value="Ethiopia" >Ethiopia</option><option value="Falkland Islands (Malvinas)" >Falkland Islands (Malvinas)</option><option value="Faroe Islands" >Faroe Islands</option><option value="Fiji" >Fiji</option><option value="Finland" >Finland</option><option value="France" >France</option><option value="France, Metropolitan" >France, Metropolitan</option><option value="French Guiana" >French Guiana</option><option value="French Polynesia" >French Polynesia</option><option value="French Southern Territories" >French Southern Territories</option><option value="Gabon" >Gabon</option><option value="Gambia" >Gambia</option><option value="Georgia" >Georgia</option><option value="Germany" >Germany</option><option value="Ghana" >Ghana</option><option value="Gibraltar" >Gibraltar</option><option value="Greece" >Greece</option><option value="Greenland" >Greenland</option><option value="Grenada" >Grenada</option><option value="Guadeloupe" >Guadeloupe</option><option value="Guam" >Guam</option><option value="Guatemala" >Guatemala</option><option value="Guernsey" >Guernsey</option><option value="Guinea" >Guinea</option><option value="Guinea-Bissau" >Guinea-Bissau</option><option value="Guyana" >Guyana</option><option value="Haiti" >Haiti</option><option value="Heard and Mc Donald Islands" >Heard and Mc Donald Islands</option><option value="Honduras" >Honduras</option><option value="Hong Kong" >Hong Kong</option><option value="Hungary" >Hungary</option><option value="Iceland" >Iceland</option><option value="India" >India</option><option value="Indonesia" >Indonesia</option><option value="Iran (Islamic Republic of)" >Iran (Islamic Republic of)</option><option value="Iraq" >Iraq</option><option value="Ireland" >Ireland</option><option value="Isle of Man" >Isle of Man</option><option value="Israel" >Israel</option><option value="Italy" >Italy</option><option value="Ivory Coast" >Ivory Coast</option><option value="Jamaica" >Jamaica</option><option value="Japan" >Japan</option><option value="Jersey" >Jersey</option><option value="Jordan" >Jordan</option><option value="Kazakhstan" >Kazakhstan</option><option value="Kenya" >Kenya</option><option value="Kiribati" >Kiribati</option><option value="Korea, Democratic People\'s Republic of" >Korea, Democratic People\'s Republic of</option><option value="Korea, Republic of" >Korea, Republic of</option><option value="Kosovo" >Kosovo</option><option value="Kuwait" >Kuwait</option><option value="Kyrgyzstan" >Kyrgyzstan</option><option value="Lao People\'s Democratic Republic" >Lao People\'s Democratic Republic</option><option value="Latvia" >Latvia</option><option value="Lebanon" >Lebanon</option><option value="Lesotho" >Lesotho</option><option value="Liberia" >Liberia</option><option value="Libyan Arab Jamahiriya" >Libyan Arab Jamahiriya</option><option value="Liechtenstein" >Liechtenstein</option><option value="Lithuania" >Lithuania</option><option value="Luxembourg" >Luxembourg</option><option value="Macau" >Macau</option><option value="Madagascar" >Madagascar</option><option value="Malawi" >Malawi</option><option value="Malaysia" >Malaysia</option><option value="Maldives" >Maldives</option><option value="Mali" >Mali</option><option value="Malta" >Malta</option><option value="Marshall Islands" >Marshall Islands</option><option value="Martinique" >Martinique</option><option value="Mauritania" >Mauritania</option><option value="Mauritius" >Mauritius</option><option value="Mayotte" >Mayotte</option><option value="Mexico" >Mexico</option><option value="Micronesia, Federated States of" >Micronesia, Federated States of</option><option value="Moldova, Republic of" >Moldova, Republic of</option><option value="Monaco" >Monaco</option><option value="Mongolia" >Mongolia</option><option value="Montenegro" >Montenegro</option><option value="Montserrat" >Montserrat</option><option value="Morocco" >Morocco</option><option value="Mozambique" >Mozambique</option><option value="Myanmar" >Myanmar</option><option value="Namibia" >Namibia</option><option value="Nauru" >Nauru</option><option value="Nepal" >Nepal</option><option value="Netherlands" >Netherlands</option><option value="Netherlands Antilles" >Netherlands Antilles</option><option value="New Caledonia" >New Caledonia</option><option value="New Zealand" >New Zealand</option><option value="Nicaragua" >Nicaragua</option><option value="Niger" >Niger</option><option value="Nigeria" >Nigeria</option><option value="Niue" >Niue</option><option value="Norfolk Island" >Norfolk Island</option><option value="North Macedonia" >North Macedonia</option><option value="Northern Mariana Islands" >Northern Mariana Islands</option><option value="Norway" >Norway</option><option value="Oman" >Oman</option><option value="Pakistan" >Pakistan</option><option value="Palau" >Palau</option><option value="Palestine" >Palestine</option><option value="Panama" >Panama</option><option value="Papua New Guinea" >Papua New Guinea</option><option value="Paraguay" >Paraguay</option><option value="Peru" >Peru</option><option value="Philippines" >Philippines</option><option value="Pitcairn" >Pitcairn</option><option value="Poland" >Poland</option><option value="Portugal" >Portugal</option><option value="Puerto Rico" >Puerto Rico</option><option value="Qatar" >Qatar</option><option value="Republic of Congo" >Republic of Congo</option><option value="Reunion" >Reunion</option><option value="Romania" >Romania</option><option value="Russian Federation" >Russian Federation</option><option value="Rwanda" >Rwanda</option><option value="Saint Kitts and Nevis" >Saint Kitts and Nevis</option><option value="Saint Lucia" >Saint Lucia</option><option value="Saint Vincent and the Grenadines" >Saint Vincent and the Grenadines</option><option value="Samoa" >Samoa</option><option value="San Marino" >San Marino</option><option value="Sao Tome and Principe" >Sao Tome and Principe</option><option value="Saudi Arabia" >Saudi Arabia</option><option value="Senegal" >Senegal</option><option value="Serbia" >Serbia</option><option value="Seychelles" >Seychelles</option><option value="Sierra Leone" >Sierra Leone</option><option value="Singapore" >Singapore</option><option value="Slovakia" >Slovakia</option><option value="Slovenia" >Slovenia</option><option value="Solomon Islands" >Solomon Islands</option><option value="Somalia" >Somalia</option><option value="South Africa" >South Africa</option><option value="South Georgia South Sandwich Islands" >South Georgia South Sandwich Islands</option><option value="South Sudan" >South Sudan</option><option value="Spain" >Spain</option><option value="Sri Lanka" >Sri Lanka</option><option value="St. Helena" >St. Helena</option><option value="St. Pierre and Miquelon" >St. Pierre and Miquelon</option><option value="Sudan" >Sudan</option><option value="Suriname" >Suriname</option><option value="Svalbard and Jan Mayen Islands" >Svalbard and Jan Mayen Islands</option><option value="Swaziland" >Swaziland</option><option value="Sweden" >Sweden</option><option value="Switzerland" >Switzerland</option><option value="Syrian Arab Republic" >Syrian Arab Republic</option><option value="Taiwan" >Taiwan</option><option value="Tajikistan" >Tajikistan</option><option value="Tanzania, United Republic of" >Tanzania, United Republic of</option><option value="Thailand" >Thailand</option><option value="Togo" >Togo</option><option value="Tokelau" >Tokelau</option><option value="Tonga" >Tonga</option><option value="Trinidad and Tobago" >Trinidad and Tobago</option><option value="Tunisia" >Tunisia</option><option value="Turkey" >Turkey</option><option value="Turkmenistan" >Turkmenistan</option><option value="Turks and Caicos Islands" >Turks and Caicos Islands</option><option value="Tuvalu" >Tuvalu</option><option value="Uganda" >Uganda</option><option value="Ukraine" >Ukraine</option><option value="United Arab Emirates" >United Arab Emirates</option><option value="United Kingdom" >United Kingdom</option><option value="United States" >United States</option><option value="United States minor outlying islands" >United States minor outlying islands</option><option value="Uruguay" >Uruguay</option><option value="Uzbekistan" >Uzbekistan</option><option value="Vanuatu" >Vanuatu</option><option value="Vatican City State" >Vatican City State</option><option value="Venezuela" >Venezuela</option><option value="Vietnam" >Vietnam</option><option value="Virgin Islands (British)" >Virgin Islands (British)</option><option value="Virgin Islands (U.S.)" >Virgin Islands (U.S.)</option><option value="Wallis and Futuna Islands" >Wallis and Futuna Islands</option><option value="Western Sahara" >Western Sahara</option><option value="Yemen" >Yemen</option><option value="Zambia" >Zambia</option><option value="Zimbabwe" >Zimbabwe</option></select>';
}

function rest_cart_session()
{
    session()->forget([
        'shipping_charge',
        'cart_item',
        'coupon_discount',
    ]);
}


function ratings_markup($ratings, $type = '')
{
    $markup = '';
    $markup_frontend = '';
    switch ($ratings) {
        case('1'):
            $markup = '<i class="fas fa-star"></i>';
            $markup_frontend = '<li><i class="fas fa-star"></i></li>';
            break;
        case('2'):
            $markup = '<i class="fas fa-star"></i><i class="fas fa-star"></i>';
            $markup_frontend = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li>';
            break;
        case('3'):
            $markup = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            $markup_frontend = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li>';
            break;
        case('4'):
            $markup = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            $markup_frontend = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li>';
            break;
        case('5'):
            $markup = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            $markup_frontend = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li>';
            break;
        default:
            break;
    }
    return $type == 'li' ? $markup_frontend : $markup;
}


function get_mega_menu_cat_name_by_id($type, $cat_id)
{
    $return_val = '';

    switch ($type) {
        case('service_mega_menu'):
            $cat_details = \App\ServiceCategory::find($cat_id);
            $return_val = !empty($cat_details) ? $cat_details->name : '';
            break;
        case('work_mega_menu'):
            $cat_details = WorksCategory::find($cat_id);
            $return_val = !empty($cat_details) ? $cat_details->name : '';
            break;
        case('event_mega_menu'):
            $cat_details = \App\EventsCategory::find($cat_id);
            $return_val = !empty($cat_details) ? $cat_details->title : '';
            break;
        case('product_mega_menu'):
            $cat_details = \App\ProductCategory::find($cat_id);
            $return_val = !empty($cat_details) ? $cat_details->title : '';
            break;
        case('donation_mega_menu'):
            $return_val = '';
            break;
        case('blog_mega_menu'):
            $cat_details = \App\BlogCategory::find($cat_id);
            $return_val = !empty($cat_details) ? $cat_details->name : '';
            break;
        case('job_mega_menu'):
            $cat_details = \App\JobsCategory::find($cat_id);
            $return_val = !empty($cat_details) ? $cat_details->title : '';
            break;
        default:
            break;
    }

    return $return_val;
}

function get_mege_menu_item_url($type, $slug)
{
    $return_val = '';

    switch ($type) {
        case('service_mega_menu'):
            $return_val = route('frontend.services.single',$slug);
            break;
        case('work_mega_menu'):
            $return_val = route('frontend.work.single',$slug);
            break;
        case('event_mega_menu'):
            $return_val =  route('frontend.events.single',$slug);
            break;
        case('product_mega_menu'):
            $return_val =  route('frontend.products.single',$slug);
            break;
        case('donation_mega_menu'):
            $return_val = route('frontend.donations.single',$slug);
            break;
        case('blog_mega_menu'):
            $return_val =  route('frontend.blog.single',$slug);
            break;
        case('job_mega_menu'):
            $return_val =  route('frontend.jobs.single',$slug);
            break;
        default:
            break;
    }

    return $return_val;
}


function getVisIpAddr() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function get_visitor_country(){
    $return_val = 'NG';
    $ip = getVisIpAddr();
    $ipdat = @json_decode(file_get_contents(
        "http://www.geoplugin.net/json.gp?ip=" . $ip));
       
    $ipdat = (array) $ipdat;
    $return_val = isset($ipdat['geoplugin_countryCode']) ? $ipdat['geoplugin_countryCode'] : $return_val;

    return $return_val;
}

function get_blog_category_by_id($id,$type = ''){
    $return_val = __('uncategorized');
    $blog_cat = \App\BlogCategory::find($id);
    if (!empty($blog_cat)){
        $return_val = $blog_cat->name;
        if ($type == 'link' ){
            $return_val = '<a href="'.route('frontend.blog.category',['id' => $blog_cat->id,'any' => Str::slug($blog_cat->name) ]).'">'.$blog_cat->name.'</a>';
        }
    }

    return $return_val;
}
function get_jobs_category_by_id($id,$type = ''){
    $return_val = __('uncategorized');
    $blog_cat = \App\JobsCategory::find($id);
    if (!empty($blog_cat)){
        $return_val = $blog_cat->title;
        if ($type == 'link' ){
            $return_val = '<a href="'.route('frontend.jobs.category',['id' => $blog_cat->id,'any' => Str::slug($blog_cat->title) ]).'">'.$blog_cat->title.'</a>';
        }
    }

    return $return_val;
}

function get_events_category_by_id($id,$type = ''){
    $return_val = __('uncategorized');
    $blog_cat = \App\EventsCategory::find($id);
    if (!empty($blog_cat)){
        $return_val = $blog_cat->title;
        if ($type == 'link' ){
            $return_val = '<a href="'.route('frontend.events.category',['id' => $blog_cat->id,'any' => Str::slug($blog_cat->title) ]).'">'.$blog_cat->title.'</a>';
        }
    }

    return $return_val;
}
function get_product_category_by_id($id,$type = ''){
    $return_val = __('uncategorized');
    $blog_cat = \App\ProductCategory::find($id);
    if (!empty($blog_cat)){
        $return_val = $blog_cat->title;
        if ($type == 'link' ){
            $return_val = '<a href="'.route('frontend.products.category',['id' => $blog_cat->id,'any' => Str::slug($blog_cat->title) ]).'">'.$blog_cat->title.'</a>';
        }
    }

    return $return_val;
}

function get_service_category_by_id($id,$type = ''){
    $return_val = __('uncategorized');
    $blog_cat = \App\ServiceCategory::find($id);
    if (!empty($blog_cat)){
        $return_val = $blog_cat->name;
        if ($type == 'link' ){
            $return_val = '<a href="'.route('frontend.services.category',['id' => $blog_cat->id,'any' => Str::slug($blog_cat->name) ]).'">'.$blog_cat->name.'</a>';
        }
    }

    return $return_val;
}

function get_price_plan_category_by_id($id,$type = ''){

    $return_val = __('uncategorized');
    $blog_cat = \App\PricePlanCategory::find($id);

    if (!empty($blog_cat)){
        $return_val = $blog_cat->name;
    }

    return $return_val;
}
function amount_with_currency_symbol($amount, $text = false)
{
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = $symbol . $amount;
    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }
    return $return_val;
}

function site_currency_symbol($text = false)
{
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];

    $symbol = '$';
    $global_currency = get_static_option('site_global_currency');
    foreach ($all_currency as $currency => $sym) {
        if ($global_currency == $currency) {
            $symbol = $text ? $currency : $sym;
            break;
        }
    }
    return $symbol;
}

function render_payment_gateway_for_form($cash_on_delivery = false)
{
    $output = '<div class="payment-gateway-wrapper">';
    if (empty(get_static_option('site_payment_gateway'))) {
        return;
    }

    $output .= '<input type="hidden" name="selected_payment_gateway" value="' . get_static_option('site_default_payment_gateway') . '">';
    $all_gateway = [
        'paypal', 'manual_payment', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack'
    ];
    $output .= '<ul>';
    if ($cash_on_delivery) {
        $output .= '<li data-gateway="cash_on_delivery" ><div class="img-select">';
        $output .= render_image_markup_by_attachment_id(get_static_option('cash_on_delivery_preview_logo'));
        $output .= '</div></li>';
    }

    foreach ($all_gateway as $gateway) {
        if (!empty(get_static_option($gateway . '_gateway'))):
            $class = (get_static_option('site_default_payment_gateway') == $gateway) ? 'class="selected"' : '';

            $output .= '<li data-gateway="' . $gateway . '" ' . $class . '><div class="img-select">';
            $output .= render_image_markup_by_attachment_id(get_static_option($gateway . '_preview_logo'));
            $output .= '</div></li>';
        endif;
    }
    $output .= '</ul>';

    $output .= '</div>';
    return $output;
}

function get_manual_payment_description()
{
    $manual_payment_description = get_static_option('site_manual_payment_description');
    $manual_payment_description = str_replace('https://{url}', url('/'), $manual_payment_description);
    $manual_payment_description = str_replace('http://{url}', url('/'), $manual_payment_description);
    return $manual_payment_description;
}

function is_paypal_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD'];
    return (in_array($global_currency, $supported_currency)) ? true : false;
}

function is_paytm_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['INR'];
    return (in_array($global_currency, $supported_currency)) ? true : false;
}

function is_razorpay_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['INR'];
    return (in_array($global_currency, $supported_currency)) ? true : false;
}

function is_mollie_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR'];
    return (in_array($global_currency, $supported_currency)) ? true : false;
}

function is_flutterwave_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'];
    return (in_array($global_currency, $supported_currency)) ? true : false;
}

function is_paystack_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['NGN', 'GHS'];
    return (in_array($global_currency, $supported_currency)) ? true : false;
}

function get_amount_in_usd($amount, $currency)
{
    $output = 0;
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'USD') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_usd_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}

function get_amount_in_inr($amount, $currency)
{
    $output = 0;
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'INR') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_inr_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}

function get_amount_in_ngn($amount, $currency)
{
    $output = 0;
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'NGN') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_ngn_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}
function check_currency_support_by_payment_gateway($gateway)
{
    $output = false;
    if ($gateway == 'paypal') {
        $output = is_paypal_supported_currency();
    } elseif ($gateway == 'paytm') {
        $output = is_paytm_supported_currency();
    } elseif ($gateway == 'mollie') {
        $output = is_mollie_supported_currency();
    } elseif ($gateway == 'stripe') {
        $output = true;
    } elseif ($gateway == 'razorpay') {
        $output = is_razorpay_supported_currency();
    } elseif ($gateway == 'flutterwave') {
        $output = is_flutterwave_supported_currency();
    } elseif ($gateway == 'paystack') {
        $output = is_paystack_supported_currency();
    } else {
        $output = true;
    }

    return $output;
}

function get_charge_currency($gateway)
{
    $output = 'USD';
    if ($gateway == 'paypal') {
        $output = 'USD';
    } elseif ($gateway == 'paytm') {
        $output = 'INR';
    } elseif ($gateway == 'mollie') {
        $output = 'USD';
    } elseif ($gateway == 'razorpay') {
        $output = 'INR';
    } elseif ($gateway == 'flutterwave') {
        $output = 'USD';
    } elseif ($gateway == 'paystack') {
        $output = 'NGN';
    }

    return $output;
}

function get_charge_amount($amount, $gateway)
{
    $output = 0;
    if ($gateway == 'paypal') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'paytm') {
        $output = get_amount_in_inr($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'mollie') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'razorpay') {
        $output = get_amount_in_inr($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'flutterwave') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'paystack') {
        $output = get_amount_in_ngn($amount, get_static_option('site_global_currency'));
    }

    return $output;
}


function get_paypal_form_url()
{
    $output = 'https://secure.paypal.com/cgi-bin/webscr';
    $sandbox_enable = get_static_option('paypal_test_mode');
    if (!empty($sandbox_enable)) {
        $output = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }
    return $output;
}

function get_paytm_environment()
{
    $output = 'PROD';
    $sandbox_enable = get_static_option('paytm_test_mode');
    if (!empty($sandbox_enable)) {
        $output = 'TEST';
    }
    return $output;
}

function redirect_404_page()
{
    return view('frontend.pages.404');
}
function get_future_date($current_days, $days)
{
    $date_plus_60_days = new DateTime($current_days);
    $date_plus_60_days->modify("+$days days");
    return $date_plus_60_days->format("d-M-Y  H:i:s");
}

function get_language_name_by_slug($slug)
{
    $data = Language::where('slug', $slug)->first();
    return $data->name;
}

function get_default_language_direction(){
    $default_lang = Language::where('default',1)->first();
    return !empty($default_lang) ? $default_lang->direction : 'ltr';
}

function custom_number_format ($amount){
   return number_format((float)$amount, 2, '.', '');
}

function get_footer_copyright_text(){
    $footer_copyright_text = get_static_option('site_'.get_user_lang().'_footer_copyright');
    $footer_copyright_text = str_replace('{copy}','&copy;',$footer_copyright_text);
    $footer_copyright_text = str_replace('{year}',date('Y'),$footer_copyright_text);
    return $footer_copyright_text;
}

function cart_tax_for_mail_template($cart_items = [])
{
    $tax_percentage = get_static_option('product_tax_percentage') ? get_static_option('product_tax_percentage') : 0;
    $cart_sub_total = get_cart_subtotal(false);
    $get_coupon_discount = session()->get('coupon_discount');

    $return_val = $cart_sub_total;

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $discount_bal = ($cart_sub_total / 100) * intval($coupon_details->discount);
            $return_val = $cart_sub_total - $discount_bal;
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $cart_sub_total - intval($coupon_details->discount);
        }
    }

    $tax_amount = ($return_val / 100) * intval($tax_percentage);

    if (get_static_option('product_tax_type') == 'individual') {
        //write code for all individual tax amount and sum all of them
        $all_cart_items = $cart_items;
        $all_individual_tax = [];
        foreach ($all_cart_items as $item) {
            $product_details = \App\Products::find($item['id']);
            if (empty($product_details)) {
                continue;
            }
            $price = $product_details->sale_price * $item['quantity'];
            $tax_percentage = ($price / 100) * $product_details->tax_percentage;
            array_push($all_individual_tax, $tax_percentage);
        }
        $tax_amount = array_sum($all_individual_tax);

    }

    return $tax_amount;
}

function get_shipping_name_by_id($id)
{
    $shipping_details = \App\ProductShipping::find($id);
    return !empty($shipping_details) ? $shipping_details->title : "Undefined";
}
function get_image_category_name_by_id($id){
    $return_val = __('uncategorized');

    $category_details = \App\ImageGalleryCategory::find($id);
    if (!empty($category_details)){
            $return_val = $category_details->title;
    }

    return $return_val;
}