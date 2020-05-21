<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 11:58
 */
//header('Expires: '. gmdate('D, d M Y H:i:s', time() + 3600). ' GMT');
//header("Cache-Control: max-age=3600"); //有效期3600秒
$last_modify = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
if (time() - $last_modify < 3600) {
    header('Last-Modified: '. gmdate('D, d M Y H:i:s', $last_modify).' GMT');
    header('HTTP/1.1 304'); //Not Modified
    exit;
}
header('Last-Modified: '. gmdate('D, d M Y H:i:s').' GMT');
