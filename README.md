# 爱江山更爱美人 - 小程序资源解析

H5 小游戏资源解包工具。

## 原理

游戏资源托管在 CDN，通过一个 JSON 清单文件索引所有资源。

- **清单地址**: `https://yxgl-cdn.guangka.com/yxgl/h5/{version}/{version}.json`
  - 例: `https://yxgl-cdn.guangka.com/yxgl/h5/19014/19014.json`
- **资源地址**: `https://yxgl-cdn.guangka.com/yxgl/h5/{version}/{resource_path}`
  - 例: `https://yxgl-cdn.guangka.com/yxgl/h5/18701/resource/db/wmz/100/10009/100092743.cfg`

清单格式为 JSON，key 是资源相对路径，value 是该资源的版本号（用于构造下载 URL）。

## 注意事项

- `.cfg` 文件实际是 zip 压缩包，下载后需改后缀为 `.zip` 再解压
- 解压目标为同级目录（覆盖原目录结构）

## 使用方法

### 1. 下载资源

```bash
php download.php
```

从远程加载清单，生成 `download_list.txt`，使用 aria2 批量下载到 `./resources/` 目录。

### 2. 解压 zip

```bash
php unzip.php
```

递归扫描 `./resources/` 下所有 `.zip` 文件，解压到同级目录。

## 目录结构

```
yxgl/
├── download.php        # 下载脚本
├── unzip.php           # 解压脚本
├── download_list.txt   # aria2 下载列表（自动生成）
├── resources/          # 下载的资源文件
└── README.md
```
