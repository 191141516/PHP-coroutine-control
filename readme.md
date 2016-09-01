# PHP-coroutine-control

PHP 协程调用 service 框架

---

环境: PHP 5.4+

---

## 内置服务使用

`conf` 目录下是你的服务列表，一个 `.ini` 文件表示一个服务

进入 bin 目录下

运行

查看帮助：

```
	php server.php
```

启动服务：

```
	php server.php yourServerName
```

`yourServerName` 是你的服务配置名，即 `conf` 目录下的 `.ini` 文件名，不用加后缀

查看当前服务列表：

```
	php server.php list
```

终端服务

> ctrl + c

---