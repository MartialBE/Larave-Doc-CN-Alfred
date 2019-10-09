<?php

require_once('workflows.php');
$w = new Workflows();
$queryArr = explode("+", trim($query));
$bookVersion = json_decode(file_get_contents("./laravelBookId.json"), true);
$url = "https://learnku.com/books/api_search/%d/?is_docs=yes&user_id=0&bookid=%d&q=%s";

$version = isset($bookVersion[$queryArr[0]]) ? $queryArr[0] : "5.8";
$bookId  = $bookVersion[$version];
$keyword = str_replace($version."+", "", $query);

if($keyword == ""){
    $w->result(0, "null", "请输入关键字", "请输入关键字", './icon.png');
    echo $w->toxml();die;
}

$url = sprintf($url, $bookId, $bookId, $keyword);

$data = json_decode(file_get_contents($url), true);

if(!$data || count($data['results']) == 0) {
    $w->result(0, "null", "暂无结果", "查询不到您要的结果", './icon.png');
    echo $w->toxml();die;
}

$i = 1;
foreach ($data['results'] as $value) {
    foreach ($value['results'] as $searchList) {
        $w->result($i, $searchList['url'], $searchList['title'], $searchList['description'], './icon.png');
        $i++;
    }
}
echo $w->toxml();
