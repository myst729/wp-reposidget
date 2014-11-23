<?php

/*
Plugin Name: WP Reposidget (GitHub 项目挂件)
Plugin URI: https://github.com/myst729/wp-reposidget
Description: Insert GitHub repository widget into you posts/pages. 在 WordPress 文章/页面中插入 GitHub 项目挂件。
Version: 2.1.0
Author: Leo Deng (@米粽粽)
Author URI: http://myst729.github.io/
License: GPLv2 or later
*/


define(WP_REPOSIDGET_HOMEPAGE,  "https://github.com/myst729/wp-reposidget");
define(WP_REPOSIDGET_USERAGENT, "WP Reposidget/2.1.0 (WordPress 3.9.0+) Leo Deng/729");

function wp_reposidget_i18n() {
  load_plugin_textdomain("repo", false, plugin_basename(__DIR__) . "/langs/");
}

function wp_reposidget_style() {
  wp_enqueue_style("reposidget_style", plugins_url("wp-reposidget.css", __FILE__));
}

function wp_reposidget_fetch($url) {
  $token = get_option('wp_reposidget_github_token');
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, WP_REPOSIDGET_USERAGENT);
  if($token) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: token ' . $token));
  }

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

function wp_reposidget_options_link($links) {
  $url = add_query_arg(array('page' => 'wp-reposidget-options'), admin_url('options-general.php'));
  $settings_link = '<a href="' . esc_url($url) . '">' . __('Settings', 'repo').'</a>';
  array_unshift($links, $settings_link); 
  return $links; 
}

function wp_reposidget_options_menu() {
  add_options_page(__('WP Reposidget', 'repo'), __('WP Reposidget', 'repo'), 'manage_options', 'wp-reposidget-options', 'wp_reposidget_options_page');
}

function wp_reposidget_register_settings() {
  add_option('wp_reposidget_github_token', '');
  register_setting('wp_reposidget_options_group', 'wp_reposidget_github_token');
}

function wp_reposidget_options_page() {
?>
  <div class="wrap">
    <h2><?php _e('WP Reposidget options', 'repo') ?></h2>
    <form method="post" action="options.php">
      <?php settings_fields('wp_reposidget_options_group'); ?>
      <div id="wp_reposidget_options_github_auth">
        <h3><?php _e('Get authenticated to GitHub API (HIGHLY RECOMMENDED!)', 'repo') ?></h3>
        <p class="description"><?php _e("According to GitHub API's policy, unauthenticated requests have a rate limit of <b>60</b> times per hour. For authenticated requests, the rate limit is <b>5,000</b> times per hour. If you find your reposidgets not working, it's possible that unauthenticated request quota is used up due to your site's page views.", 'repo') ?></p>
        <table class="form-table">
          <tr>
            <th scope="row">
              <label for="wp_reposidget_github_token"><?php _e('Personal Access Token', 'repo') ?></label>
            </th>
            <td>
              <input type="password" name="wp_reposidget_github_token" id="wp_reposidget_github_token" class="regular-text" value="<?php echo get_option('wp_reposidget_github_token'); ?>">
              <button type="button" id="wp_reposidget_github_token_toggler" class="button button-secondary hidden"><?php _e('Show Token', 'repo') ?></button>
            </td>
          </tr>
        </table>
        <script>
          void function() {
            var token = document.getElementById('wp_reposidget_github_token');
            var button = document.getElementById('wp_reposidget_github_token_toggler');
            var isHidden = true;
            button.addEventListener('click', function(e) {
              isHidden = !isHidden;
              token.type = isHidden ? 'password' : 'text';
              button.innerHTML = isHidden ? "<?php _e('Show Token', 'repo') ?>" : "<?php _e('Hide Token', 'repo') ?>";
            }, false);
            button.classList.remove('hidden');
          }();
        </script>
      </div>
      <?php submit_button(); ?>
    </form>
    <h3><?php _e('How do I get the personal access token?', 'repo') ?></h3>
    <p><?php _e('Visit <strong><a href="https://github.com/settings/tokens/new" target="_blank">https://github.com/settings/tokens/new</a></strong>, make sure <strong>public_repo</strong> is checked (it is the only scope requested by WP Reposidget, you may uncheck others) and generate a token.', 'repo') ?></p>
    <p><img src="<?php echo plugins_url("screenshot-3.png", __FILE__); ?>" alt="GitHub Personal Access Token" style="box-shadow:0 0 15px lightgray"></p>
  </div>
<?php
}

add_filter("plugins_loaded", "wp_reposidget_i18n");
add_filter("wp_enqueue_scripts", "wp_reposidget_style");
add_filter("admin_init", "wp_reposidget_editor_init");
add_action('admin_init', 'wp_reposidget_register_settings');
add_filter('admin_menu', 'wp_reposidget_options_menu');
add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'wp-reposidget.php'), 'wp_reposidget_options_link');
add_shortcode("repo", "wp_reposidget");

?>