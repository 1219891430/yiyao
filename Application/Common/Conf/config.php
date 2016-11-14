<?php
return array(
	'DB_TYPE'				=> 'mysql',			// 数据库类型
	'DB_HOST'				=> 'localhost',		// 服务器地址
	'DB_NAME'				=> 'yiyao',	// 数据库名
	'DB_USER'				=> 'root',			// 用户名
	'DB_PWD'				=> '',		// 密码
	'DB_PORT'				=> '3306',			// 端口
	'DB_PREFIX'				=> 'zdb_',			// 数据库表前缀
	'DB_CHARSET'			=> 'utf8',			// 数据库编码
	'DB_FIELDTYPE_CHECK'	=> false,			// 关闭字段类型检查
	'DB_FIELDS_CACHE'		=> false,			// 关闭字段缓存
	'DB_SQL_BUILD_CACHE'	=> false,			// 关闭SQL缓存
	'DB_SQL_LOG'			=> false,			// 关闭SQL执行日志记录
	'DB_BIND_PARAM'			=> false,			// 关闭数据自动参数绑定 
	
	'DEFAULT_FILTER'		=> 'trim,strip_tags,htmlspecialchars',			// 用户录入过滤	
	
	'ACTION_SUFFIX'			=> 'Action',									// Action方法后缀 
	
	'LOAD_EXT_CONFIG'		=> array('ZDB' => 'zdb'),						// 加载自定义配置文件
	
	'MODULE_DENY_LIST'		=> array('Common', 'Runtime'),					// 禁止访问的模块列表
	'MODULE_ALLOW_LIST'		=> array('Mall', 'Admin', 'Dealer', 'Mobile', 'WapMall'),	// 允许访问的模块列表
	'DEFAULT_MODULE'		=> 'Mall',										// 默认模块
	'DEFAULT_CONTROLLER'	=> 'Index',										// 默认控制器名称
	'DEFAULT_ACTION'		=> 'index',										// 默认操作名称
	
	'URL_CASE_INSENSITIVE'	=> true,					// 不区分大小写
	'URL_MODEL'				=> 1,						// URL访问模式: 0->普通模式, 1->PATHINFO模式, 2->REWRITE模式
	'URL_PARAMS_BIND'		=> false,					// URL变量不绑定到Action方法参数
	'URL_ROUTER_ON'			=> false,					// 关闭路由	
	'URL_DENY_SUFFIX'		=> 'ico|png|gif|jpg',		// URL禁止访问的后缀设置
	
	'SHOW_PAGE_TRACE'		=> true,					// 显示页面Trace信息, 生成环境修改为 false
	
	'LOG_RECORD'			=> true,					// 开启日志记录
	'LOG_LEVEL'				=> 'EMERG,ALERT,CRIT,ERR',	// 只记录EMERG ALERT CRIT ERR 错误
	
	'TOKEN_ON'				=> true,					// 是否开启令牌验证 默认关闭
	'TOKEN_NAME'			=> '__hash__',				// 令牌验证的表单隐藏字段名称，默认为__hash__
	'TOKEN_TYPE'			=> 'md5',					// 令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'			=> true,					// 令牌验证出错后是否重置令牌 默认为true

	'DATA_CACHE_TIME'		=> 0,						// 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_TYPE'		=> 'File',					// 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
	'DATA_CACHE_SUBDIR'		=> true,					// 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
	
	'TMPL_ENGINE_TYPE'		=> 'Think',					// 默认模板引擎 以下设置仅对使用Think模板引擎有效
	'TMPL_DENY_FUNC_LIST'	=> 'echo,exit',				// 模板引擎禁用函数
	'TMPL_DENY_PHP'			=> false,					// 默认模板引擎是否禁用PHP原生代码

    'GLOBAL_CACHE'          => true

);