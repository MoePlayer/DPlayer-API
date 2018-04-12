## DPlayer API For PHP
这个东西。。只是我闲的无聊。。随便写写的（基于 FrameLite 。。并没有什么多大的用处。。╮(╯_╰)╭

## 使用方法
Clone 到 Server 上之后。。

把 MYSQL.sql 导入到数据库。。然后修改一下 Config/Config.php 中的数据库信息

最后。。添加一波 Rewrite 就完成啦。。 ヾ(=･ω･=)o

## 已完成的。。
* [x] 添加弹幕
* [x] 获取 BiliBili 视频链接
* [x] 获取 BiliBili 弹幕。。

## Rewrite
##### Nginx（需要 Pathinfo 的支持

    location / {
        if (!-e $request_filename) {
             rewrite ^/(.*)$ /index.php/$1 last;
        }   
    }
    
##### Apache（未经过测试。。的说

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]


