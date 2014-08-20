<?php
/*
Plugin Name: WP Reposidget (GitHub 项目挂件)
Plugin URI: http://forcefront.com/reposidget-plugin/
Description: Insert GitHub repository widget into you posts/pages. 在 WordPress 文章/页面中插入 GitHub 项目挂件。
Version: 1.0.2
Author: Leo Deng (@米粽粽)
Author URI: http://forcefront.com/
License: GPLv2 or later
Bitbucket addition contributor: Caspar Cedro
Bitbucket addition contributor website: http://eclecticdimensions.com
*/


function multi_lingua() {
    load_plugin_textdomain('repo', false, dirname(plugin_basename(__FILE__)) . '/langs/');
}

function quicktags() {
    ?>
    <script type="text/javascript">
    void function() {
        function repoPath(e, c, ed) {
            var path = prompt('<?php _e("Path to the repo you want to insert:", "repo"); ?>');
            if (!!path) {
                this.tagStart = '[repo path="' + path + '"]';
                QTags.TagButton.prototype.callback.call(this, e, c, ed);
            }
        }
        QTags.addButton('repo', '<?php _e("GitHub Repo", "repo"); ?>', repoPath);
    }();
    </script><?php
}

function add_reposidget_stylesheet() {
    echo "\n" . '<link rel="stylesheet" href="' . plugins_url('wp-reposidget.css', __FILE__) . '" />' . "\n";
}

function get_repo_github($path) {
    $url = "https://api.github.com/repos/" . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36");
    $repo = json_decode(curl_exec($ch), true);
    curl_close ($ch);
    return $repo;
}

function get_repo_bitbucket($path) {
    $url = "https://bitbucket.org/api/2.0/repositories/" . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36");
    $repo = json_decode(curl_exec($ch), true);
    curl_close ($ch);
    return $repo;
}

function reposidget($atts) {
    $path = $atts["path"];
    $repohost = $atts["repohost"];
    $branch = $atts["branch"];

    if ($branch == "") {
        $branch = "master";
    }

    if ($repohost == "github") {
        $repo = get_repo_github($path);
        if ($repo["description"] != "" && $repo["homepage"] != null) {
            $repoContent = '<p>' . $repo["description"] . '</p><p class="homepage"><strong><a href="' . $repo["homepage"] . '">' . $repo["homepage"] . '</a></strong></p>';
        } else if ($repo["description"] != "") {
            $repoContent = '<p>' . $repo["description"] . '</p>';
        } else if ($repo["homepage"] != null) {
            $repoContent = '<p class="homepage"><strong><a href="' . $repo["homepage"] . '">' . $repo["homepage"] . '</a></strong></p>';
        } else {
            $repoContent = '<p class="none">No description or homepage.</p>';
        }
        $html = '<div class="reposidget"><div class="reposidget-header"><h2><a href="https://github.com/' . $repo["owner"]["login"] . '">' . $repo["owner"]["login"] . '</a>&nbsp;/&nbsp;<strong><a href="' . $repo["html_url"] . '">' . $repo["name"] .  '</a></strong></h2></div><div class="reposidget-content">' . $repoContent . '</div><div class="reposidget-footer"><span class="social"><span class="star">' . number_format($repo["watchers_count"]) . '</span><span class="fork">' . number_format($repo["forks_count"]) . '</span></span><a href="' . $repo["html_url"] . '/archive/' . $repo["default_branch"] . '.zip">Download as zip</a></div></div>';
        return $html;
    } else if ($repohost == "bitbucket") {
        $repo = get_repo_bitbucket($path);
        if ($repo["description"] != "") {
            $repoContent = '<p>' . $repo["description"] . '</p>';
        } else if ($repo["homepage"] != null) {
            $repoContent = '<p class="homepage"><strong><a href="' . $repo["homepage"] . '">' . $repo["homepage"] . '</a></strong></p>';
        } else {
            $repoContent = '<p class="none">No description or homepage.</p>';
        }
        $html = '<div class="reposidget"><div class="reposidget-header-bitbucket"><h2 style="background-repeat: no-repeat;border-radius: 50%;width: 30px;height: 34px;overflow: visible !important;
                background: url('.$repo["links"]["avatar"]["href"].');
                background-size: cover;"><a style="color:#FFFFFF;margin-left: 5px;" href="https://bitbucket.org/' . $repo["owner"]["username"] . '">' . $repo["owner"]["username"] . '</a>&nbsp;/&nbsp;<strong><a style="color:#FFFFFF;" href="https://bitbucket.org/' . $repo["owner"]["username"] . '/' . strtolower($repo["name"]).'">' . $repo["name"] .  '</a></strong></h2></div><div class="reposidget-content-bitbucket">' . $repoContent . '</div><div class="reposidget-footer-bitbucket"><span class="social" style="background: url(https://d3oaxc4q5k2d6q.cloudfront.net/m/cc9af2a5b5eb/img/marketing/bb_logo.png?07f1e2cdd031);background-size: contain;background-repeat: no-repeat;padding-right: 24px;"><a style="padding: 15px 100px 10px 10px !important;margin-top: 0px;background: none;
                border: none;" href="https://bitbucket.org"></a></span><a href="https://bitbucket.org/'.$repo["owner"]["username"].'/'.strtolower($repo["name"]).'/get/'.$branch.'.zip">Download as zip</a></div></div>';
        return $html;
    } else {
        return;
    }
}

function reposidget_button($buttons) {
    array_push($buttons, "|", "reposidget");
    return $buttons;
}
function reposidget_script($plugin_array) {
    $plugin_array['reposidget'] = plugins_url('wp-reposidget.js', __FILE__);
    return $plugin_array;
}

add_action('admin_print_footer_scripts', 'quicktags');
add_action('plugins_loaded', 'multi_lingua');
add_action('wp_head', 'add_reposidget_stylesheet');
add_filter('mce_buttons', 'reposidget_button');
add_filter('mce_external_plugins', 'reposidget_script');
add_shortcode('repo', 'reposidget');


?>