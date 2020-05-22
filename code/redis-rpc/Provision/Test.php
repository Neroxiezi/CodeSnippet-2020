<?php

/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 17:39
 */
class Test
{
    protected $redis;

    public function __construct()
    {
        //实例化
        $redis = new Redis();
        //连接服务器
        $redis_connect = $redis->connect("localhost", 6379);
        $this->redis = $redis_connect;
    }

    public function get_html($params)
    {
        $this->get_user($params);
        return '<div class="media text-muted pt-3">
      <svg class="bd-placeholder-img mr-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 32x32"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"></rect><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
      <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        <strong class="d-block text-gray-dark">@username</strong>
        Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.
      </p>
    </div>';
    }

    private function get_user($article)
    {

    }

    private function get_commit_form()
    {

    }
}
