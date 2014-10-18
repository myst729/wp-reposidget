WP Reposidget (GitHub 项目挂件)
===============================

Contributors: Leo Deng ([@米粽粽](http://weibo.com/myst729))  
Plugin URI: http://forcefront.com/wp-reposidget-plugin/  
Tags: github, reposidget  
Requires at least: 3.9.0  
Tested up to: 3.9.2  
Stable tag: 2.0.1  
Author URI: http://forcefront.com/  
Donate link: http://forcefront.com/macbook-pro-donation/  
License: GPLv2 or later

Insert GitHub repository widget into you posts/pages.


###Description###

Insert [GitHub](https://github.com/) repository widget into you posts/pages.

在 WordPress 文章/页面中嵌入 [GitHub](https://github.com/) 项目挂件。


###Installation###

1. Upload the plugin to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Now you could see the `GitHub Repo` button in post/page editor.
4. Click the button and input the owner and name of your GitHub repo.

上传插件到您的 WordPress 插件目录，登录后台激活即可。进入文章编辑界面，您就会看到“GitHub Repo”的快捷按钮。点击按钮后，输入您的项目所有者和名称即可插入短码。


###Frequently Asked Questions###

1. Q: Does this plugin support BitBucket?  
   A: No. It's not going to happen until BitBucket API system is actually usable (right now it's only shit).  

   问题：这个插件支持添加 BitBucket 项目吗？  
   回答：不支持，除非 BitBucket API 系统达到实际可用的程度（目前就是一坨屎）。  

2. Q: After upgraded to version 2.x, I got a "Parse error: syntax error, unexpected T_FUNCTION...", what's that?  
   A: Version 2.x requires PHP 5.3 and above. Please upgrade your PHP environment, or you can continue to use version 1.x.  

   问题：升级到 2.x 以后报错，“Parse error: syntax error, unexpected T_FUNCTION...”，是什么原因？  
   回答：2.x 要求 PHP 版本不低于 5.3。请升级您的 PHP 环境，或继续使用 1.x 版本。  


###Donation###

If you like this plugin and want to thank its developer, you may consider [**donation**](http://forcefront.com/macbook-pro-donation/).

如果您觉得这个插件还不错，想对开发者表示感谢，可以考虑[**打赏开发者一点儿银子**](http://forcefront.com/macbook-pro-donation/)。  
[![I want a Macbook Pro!](http://forcefront.com/wp-content/uploads/2014/07/alipay.png)](http://forcefront.com/macbook-pro-donation/)


###Screenshots###

1. Use shortcode to insert reposidget into the post/page. 使用简码向文章/页面中嵌入 GitHub 项目。  
![Insert Reposidget](https://raw.githubusercontent.com/myst729/wp-reposidget/master/screenshot-1.png)

2. The look of a reposidget. 嵌入文章的项目挂件。  
![Visual Style](https://raw.githubusercontent.com/myst729/wp-reposidget/master/screenshot-2.png)


###Changelog###

= 2.0.1 (2014-09-03) =
* Back compatible with old shortcode syntax. Fix situation when repo info is wrong.
* 兼容旧版短码格式。处理项目信息有误的情况。

= 2.0.0 (2014-09-02) =
* Completely rewritten. Fix bugs and improve usability. Compatible with WordPress visual style.
* 完全重写了代码。修复 bug，改善使用体验。兼容 WordPress 界面风格。

= 1.0.3 (2014-05-10) =
* Be compatible with GitHub API change.
* 修复 GitHub API 变更。

= 1.0.2 (2013-05-23) =
* Format numbers.
* 格式化数字。

= 1.0.1 (2013-05-23) =
* Add support to rich editor. Add support to language translation.
* 支持可视化编辑器。支持多语言。

= 1.0.0 (2013-05-22) =
* First drop.
* 发布第一个版本。
