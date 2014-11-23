## WP Reposidget (GitHub 仓库挂件)

Contributors: Leo Deng ([@米粽粽](http://weibo.com/myst729))  
Plugin URI: https://github.com/myst729/wp-reposidget  
Tags: github, reposidget  
Requires at least: 3.9.0  
Tested up to: 4.0.1  
Stable tag: 2.1.0  
Author URI: http://myst729.github.io/  
License: GPLv2 or later

Insert GitHub repository widget into you posts/pages.


### Description

Insert [GitHub](https://github.com/) repository widget into you posts/pages.

在 WordPress 文章/页面中嵌入 [GitHub](https://github.com/) 仓库挂件。


### Installation

1. Upload the plugin to your `/wp-content/plugins/` directory.  
   上传插件到您的 WordPress 插件目录。

2. Activate the plugin through the 'Plugins' menu in WordPress.  
   登录后台激活插件。

3. Now you could see the `GitHub Repo` button in post/page editor.  
   进入文章编辑界面，您会看到“GitHub Repo”的快捷按钮。

4. Click the button and input the owner and name of your GitHub repo.  
   点击按钮后，输入您的仓库所有者和名称即可插入短码。

5. (Optional) Fill in your GitHub personal access token in plugin options page.  
   （可选）在插件设置页面填写你的 GitHub 个人访问令牌。


### Frequently Asked Questions

1. **Q**: Does this plugin support BitBucket?  
   **问题**：这个插件支持添加 BitBucket 仓库吗？  

   **A**: No. It's not going to happen until BitBucket API system is actually usable (it's basically shit at the moment).  
   **回答**：不支持，除非 BitBucket API 系统达到实际可用的程度（目前就是一坨屎）。  

2. **Q**: After upgraded to version 2.x, I got a "Parse error: syntax error, unexpected T_FUNCTION...", what's that?  
   **问题**：升级到 2.x 以后报错，“Parse error: syntax error, unexpected T_FUNCTION...”，是什么原因？  

   **A**: Version 2.x requires PHP 5.3 and above. Please upgrade your PHP environment, or you can continue to use version 1.x.  
   **回答**：2.x 要求 PHP 版本不低于 5.3。请升级您的 PHP 环境，或继续使用 1.x 版本。  


### Screenshots

1. Use shortcode to insert reposidget into the post/page.  
   使用简码向文章/页面中嵌入 GitHub 仓库。  
   ![Insert Reposidget](https://raw.githubusercontent.com/myst729/wp-reposidget/master/screenshot-1.png)

2. The look of a reposidget.  
   嵌入文章的仓库挂件。  
   ![Visual Style](https://raw.githubusercontent.com/myst729/wp-reposidget/master/screenshot-2.png)

3. Generate a GitHub personal access token.  
   生成 GitHub 个人访问令牌。  
   ![Visual Style](https://raw.githubusercontent.com/myst729/wp-reposidget/master/screenshot-3.png)
