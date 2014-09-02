void function() {
  tinymce.PluginManager.add('reposidget_mce', function(editor) {
    editor.addCommand('WP_Reposidget', function() {
      window.wpReposidgetDialog.open(editor.id, true);
    });

  	editor.addButton('reposidget_mce', {
      icon: 'reposidget',
      tooltip: editor.translate('reposidget.tooltip'),
      cmd: 'WP_Reposidget'
    });
  });
}();