<?php
function getRedGift($total, $num = 10)
{
    $min = 0.01;
    $wamp = array();
    $returnData = array();
    for ($i = 1; $i < $num; ++$i) {
        $safe_total = ($total - ($num - $i) * $min) / ($num - $i); //红包金额的最大值
        if ($safe_total < 0) break;
        $money = @mt_rand($min * 100, $safe_total * 100) / 100;//随机产生一个红包金额
        $total = $total - $money;//剩余红包总额
        $wamp[$i] = round($money, 2);//保留两位有效数字
    }
    $wamp[$i] = round($total, 2);
    $returnData['MoneySum'] = $wamp;
    $returnData['newTotal'] = array_sum($wamp);
    return $returnData;
}


echo '<pre>';
//测试
$data = getRedGift(100, 10);
print_r($data);