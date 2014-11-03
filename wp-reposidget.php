<?php

/*
Plugin Name: WP Reposidget (GitHub 项目挂件)
Plugin URI: https://github.com/myst729/wp-reposidget
Description: Insert GitHub repository widget into you posts/pages. 在 WordPress 文章/页面中插入 GitHub 项目挂件。
Version: 2.0.2
Author: Leo Deng (@米粽粽)
Author URI: http://myst729.github.io/
License: GPLv2 or later
*/


define(WP_REPOSIDGET_HOMEPAGE,  "https://github.com/myst729/wp-reposidget");
define(WP_REPOSIDGET_USERAGENT, "WP Reposidget/2.0.1 (WordPress 3.9.0+) Leo Deng/1.0");

function wp_reposidget_i18n() {
  load_plugin_textdomain("repo", false, plugin_basename(__DIR__) . "/langs/");
}

function wp_reposidget_style() {
  wp_enqueue_style("reposidget_style", plugins_url("wp-reposidget.css", __FILE__));
}

function wp_reposidget_fetch($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, WP_REPOSIDGET_USERAGENT);
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);
}

function wp_reposidget_render($template, $pattern, $data) {
  $handle = fopen($template, "r");
  $string = fread($handle, filesize($template));
  fclose($handle);
  $replacer = function($matches) use ($data) { return $data[$matches[1]]; };
  return preg_replace_callback($pattern, $replacer, $string);
}

function wp_reposidget($atts) {
  if(array_key_exists("path", $atts)) {
    $atts_path = explode("/", $atts["path"]);
    $atts_owner = $atts_path[0];
    $atts_name = $atts_path[1];
  } else {
    $atts_owner = $atts["owner"];
    $atts_name = $atts["name"];
  }

  if($atts_owner == null || $atts_name == null) {
    return "";
  }

  $url = "https://api.github.com/repos/" . $atts_owner . '/' . $atts_name;
  $repo = wp_reposidget_fetch($url);

  if(array_key_exists("message", $repo) || array_key_exists("documentation_url", $repo) || $repo["private"] == true) {
    $data = array(
      "owner"              => $atts_owner,
      "owner_url"          => "https://github.com/" . $atts_owner,
      "name"               => $atts_name,
      "html_url"           => "https://github.com/" . $atts_owner . "/" . $atts_name,
      "default_branch"     => "-",
      "description"        => __("This repository is not available anymore.", "repo"),
      "toggle_description" => "",
      "homepage"           => "https://github.com/" . $atts_owner . "/" . $atts_name,
      "toggle_homepage"    => "hidden",
      "stargazers_count"   => "-",
      "forks_count"        => "-",
      "toggle_download"    => "hidden",
      "plugin_tip"         => __("GitHub Reposidget for WordPress", "repo"),
      "plugin_url"         => WP_REPOSIDGET_HOMEPAGE
    );
  } else {
    $description_empty = ($repo["description"] == "");
    $homepage_empty = ($repo["homepage"] == "" || $repo["homepage"] == null);
    $data = array(
      "owner"              => $repo["owner"]["login"],
      "owner_url"          => $repo["owner"]["html_url"],
      "name"               => $repo["name"],
      "html_url"           => $repo["html_url"],
      "default_branch"     => $repo["default_branch"],
      "description"        => ($description_empty && $homepage_empty) ? __("This repository doesn't have description or homepage.", "repo") : $repo["description"],
      "toggle_description" => ($description_empty && !$homepage_empty) ? "hidden" : "",
      "homepage"           => $homepage_empty ? $repo["html_url"] : $repo["homepage"],
      "toggle_homepage"    => $homepage_empty ? "hidden" : "",
      "stargazers_count"   => number_format($repo["stargazers_count"]),
      "forks_count"        => number_format($repo["forks_count"]),
      "toggle_download"    => "",
      "plugin_tip"         => __("GitHub Reposidget for WordPress", "repo"),
      "plugin_url"         => WP_REPOSIDGET_HOMEPAGE
    );
  }

  $template = plugin_dir_path( __FILE__ ) . "wp-reposidget.html";
  $pattern = '/{{([a-z_]+)}}/';

  return wp_reposidget_render($template, $pattern, $data);
}

function wp_reposidget_editor_style() {
  wp_enqueue_style("reposidget_html", plugins_url("wp-reposidget-editor.css", __FILE__));
}

function wp_reposidget_editor() {
  if(wp_script_is("quicktags")) {
    $template = plugin_dir_path( __FILE__ ) . "wp-reposidget-dialog.html";
    $pattern = '/{{([a-z_]+)}}/';
    $data = array(
      "title"   => __('Add GitHub Reposidget', 'repo'),
      "message" => __('Please fill the owner and name of the repo:', 'repo'),
      "owner"   => __('Repo Owner', 'repo'),
      "name"    => __('Repo Name', 'repo'),
      "add"     => __('Add Repo', 'repo'),
      "cancel"  => __('Cancel', 'repo')
    );

    echo wp_reposidget_render($template, $pattern, $data);
?>
    <script type="text/javascript" src="<?php echo plugins_url("wp-reposidget-dialog.js", __FILE__); ?>"></script>
    <script type="text/javascript">
      void function() {
        function addWpReposidget(button, editor, qtags) {
          window.wpReposidgetDialog.open(qtags.id, false);
        }
        QTags.addButton("reposidget_html", "<?php _e('GitHub Repo', 'repo'); ?>", addWpReposidget, undefined, undefined, "<?php _e('Add GitHub Reposidget', 'repo'); ?>");
      }();
    </script>
<?php
  }
}

function wp_reposidget_mce_plugin($plugin_array) {
  $plugin_array["reposidget_mce"] = plugins_url("wp-reposidget-mce.js", __FILE__);
  return $plugin_array;
}

function wp_reposidget_mce_button($buttons) {
  array_push($buttons, "reposidget_mce");
  return $buttons;
}

function wp_reposidget_editor_init() {
  if(current_user_can("edit_posts") || current_user_can("edit_pages")) {
    add_filter("admin_enqueue_scripts", "wp_reposidget_editor_style");
    add_filter("admin_print_footer_scripts", "wp_reposidget_editor");

    if(get_user_option("rich_editing") == "true") {
      add_filter("mce_external_plugins", "wp_reposidget_mce_plugin");
      add_filter("mce_buttons", "wp_reposidget_mce_button");
    }
  }
}

add_filter("plugins_loaded", "wp_reposidget_i18n");
add_filter("wp_enqueue_scripts", "wp_reposidget_style");
add_filter("admin_init", "wp_reposidget_editor_init");
add_shortcode("repo", "wp_reposidget");

?>