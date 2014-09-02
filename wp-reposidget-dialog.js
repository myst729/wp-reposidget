var wpReposidgetDialog = {
  initialized: false,

  init: function(editorId) {
    this.backdrop     = document.getElementById("wp-reposidget-backdrop");
    this.wrapper      = document.getElementById("wp-reposidget-wrapper");
    this.closeButton  = document.getElementById("wp-reposidget-close");
    this.ownerInput   = document.getElementById("wp-reposidget-owner");
    this.nameInput    = document.getElementById("wp-reposidget-name");
    this.addButton    = document.getElementById("wp-reposidget-add");
    this.cancelButton = document.getElementById("wp-reposidget-cancel");
    this.editor       = document.getElementById(editorId);

    this.probe();
    this.initialized = true;
  },

  probe: function() {
    document.addEventListener("keydown", this.closeDialog, false);
    this.backdrop.addEventListener("click", this.closeDialog, false);
    this.closeButton.addEventListener("click", this.closeDialog, false);
    this.cancelButton.addEventListener("click", this.closeDialog, false);
    this.addButton.addEventListener("click", this.insertShortcode, false);
  },

  generateShortcode: function() {
    return '[repo owner="' + this.ownerInput.value + '" name="' + this.nameInput.value + '"]';
  },

  htmlInsert: function() {
    var editor = this.editor;
    var range = this.range;
    var shortcode = this.generateShortcode();

    // insert shortcode into editor
    if(document.selection && range) {
      // IE
      editor.focus();
      range.text = range.text + shortcode;
      range.moveToBookmark(range.getBookmark());
      range.select();
      range = null;
    } else if(typeof editor.selectionEnd !== "undefined") {
      // W3C
      var end = editor.selectionEnd;
      var cursor = end + shortcode.length;
      editor.value = editor.value.substring(0, end) + shortcode + editor.value.substring(end, editor.value.length);
      editor.selectionStart = editor.selectionEnd = cursor;
    }

    this.close();
  },

  mceInsert: function() {
    var shortcode = this.generateShortcode();
    var mceEditor = this.mceEditor;
    var selected = mceEditor.selection.getContent();

    mceEditor.focus();
    mceEditor.insertContent(selected + shortcode);
    mceEditor.selection.collapse();

    this.close();
  },

  insertShortcode: function() {
    if(wpReposidgetDialog.isMCE) {
      wpReposidgetDialog.mceInsert();
    } else {
      wpReposidgetDialog.htmlInsert();
    }
  },

  reset: function() {
    this.ownerInput.value = "";
    this.nameInput.value = "";
    this.ownerInput.focus();
  },

  open: function(editorId, isMCE) {
    if(!this.initialized) {
      this.init(editorId);
    }

    this.isMCE = isMCE;
    if(isMCE && typeof tinymce !== "undefined") {
      this.mceEditor = this.mceEditor || tinymce.get(editorId);
    }

    if(!isMCE && document.selection && document.selection.createRange()) {
      // html editor in IE
      this.editor.focus();
      this.range = document.selection.createRange().duplicate();
    }

    this.reset();
    this.backdrop.style.display = "block";
    this.wrapper.style.display = "block";
    this.ownerInput.focus();
  },

  close: function() {
    if(this.isMCE) {
      this.mceEditor.focus();
    } else {
      this.editor.focus();
      var range = this.range;
      if(range) {
        // html editor in IE
        range.moveToBookmark(range.getBookmark());
        range.select();
        range = null;
      }
    }

    this.backdrop.style.display = "none";
    this.wrapper.style.display = "none";
  },

  closeDialog: function(e) {
    if(e.type === "keydown" && e.keyCode !== 27) {
      // pressed key is not "Esc"
      return;
    }
    wpReposidgetDialog.close();
  }
};