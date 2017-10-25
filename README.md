# Laravel Command Package for .env file synchronization

這個套件編寫了一個 .env 同步的 Artisan 命令，主要用來協助系統管理與設定。

### 安裝方式

`composer require nox0121/laravel-env-sync-command`

### 設定 app.confg

	'providers' => [
	    ...
	    Nox0121\LaravelEnvSyncCommand\LaravelEnvSyncCommandServiceProvider::class,
	    ...
	];

### 支援指令如下：

1. `php artisan env:sync {source} {destination}` - 同步 .env 設定檔。

### 參考來源

* [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
