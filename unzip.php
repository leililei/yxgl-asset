<?php

$DOWNLOAD_DIR = './resources';

// 递归查找所有zip文件
function findZipFiles($dir) {
    $files = [];
    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . '/' . $item;

        if (is_dir($path)) {
            $files = array_merge($files, findZipFiles($path));
        } elseif (substr($item, -4) === '.cfg') {
            $files[] = $path;
        }
    }

    return $files;
}

echo "扫描zip文件...\n";
$zipFiles = findZipFiles($DOWNLOAD_DIR);
echo "找到 " . count($zipFiles) . " 个zip文件\n\n";

$success = 0;
$fail = 0;

foreach ($zipFiles as $zipFile) {
    $extractDir = dirname($zipFile);

    echo "解压: $zipFile\n";

    $zip = new ZipArchive();
    if ($zip->open($zipFile) === true) {
        $zip->extractTo($extractDir);
        $zip->close();
        echo "  -> 成功\n";
        $success++;
    } else {
        echo "  -> 失败\n";
        $fail++;
    }
}

echo "\n完成! 成功: $success, 失败: $fail\n";
