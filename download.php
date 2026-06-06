<?php

// 从远程加载资源清单
$manifestUrl = 'https://yxgl-cdn.guangka.com/yxgl/h5/19014/19014.json';
$manifestJson = file_get_contents($manifestUrl);
if ($manifestJson === false) {
    echo "无法加载资源清单: $manifestUrl\n";
    exit(1);
}
$manifest = json_decode($manifestJson, true);

// CDN基础地址
$CDN_BASE = 'https://yxgl-cdn.guangka.com/yxgl/h5';

// 下载目录
$DOWNLOAD_DIR = './resources';

// 创建下载目录
if (!is_dir($DOWNLOAD_DIR)) {
    mkdir($DOWNLOAD_DIR, 0777, true);
}

// 构造下载列表
$downloadList = [];
foreach ($manifest as $filePath => $version) {
    $url = "$CDN_BASE/$version/$filePath";

    // cfg文件后缀改为zip
    $localPath = $filePath;
    // if (substr($localPath, -4) === '.cfg') {
    //     $localPath = substr($localPath, 0, -4) . '.zip';
    // }

    $localPath = $DOWNLOAD_DIR . '/' . $localPath;

    $downloadList[] = [
        'url' => $url,
        'out' => $localPath
    ];
}

// 生成aria2输入文件
$aria2Content = '';
foreach ($downloadList as $item) {
    $aria2Content .= $item['url'] . "\n";
    $aria2Content .= "  out=" . $item['out'] . "\n";
}

file_put_contents('download_list.txt', $aria2Content);

echo "共 " . count($downloadList) . " 个文件需要下载\n";
echo "已生成 download_list.txt\n";
echo "开始下载...\n";

// 使用aria2批量下载
$cmd = 'aria2c -i download_list.txt --continue';
passthru($cmd, $exitCode);

if ($exitCode === 0) {
    echo "\n下载完成!\n";
} else {
    echo "\n部分文件下载失败，请重新运行脚本重试\n";
}
