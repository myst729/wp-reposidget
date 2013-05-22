;void function() {
    tinymce.create('tinymce.plugins.reposidget', {
        init: function(ed, url) {
            ed.addButton('reposidget', {
                title: ed.getLang("reposidget.title"),
                image: url + '/icon.png',
                onclick: function() {
                    var path = prompt(ed.getLang("reposidget.tip"));
                    if(!!path) {
                        ed.execCommand('mceInsertContent', false, '[repo path="' + path + '"]');
                    }
                }
            });
        }
    });
    tinymce.PluginManager.add('reposidget', tinymce.plugins.reposidget);
}();