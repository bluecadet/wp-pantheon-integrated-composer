(function() {

  /**
   * Add a character count to the default WP WYSIWYG
   *
   * @link https://amystechnotes.com/2015/05/06/tinymce-add-character-count/comment-page-1/
   *
   */
  tinymce.PluginManager.add('charactercount', function (editor) {
    var self = this;

    function update() {
      editor.theme.panel.find('#charactercount').text(['Characters: {0}', self.getCount()]);
    }

    editor.on('init', function () {
      var statusbar = editor.theme.panel && editor.theme.panel.find('#statusbar')[0];

      if (statusbar) {
        window.setTimeout(function () {
          statusbar.insert({
            type: 'label',
            name: 'charactercount',
            text: ['Characters: {0}', self.getCount()],
            classes: 'charactercount',
            disabled: editor.settings.readonly
          }, 0);

          editor.on('setcontent beforeaddundo', update);

          editor.on('keyup', function (e) {
              update();
          });
        }, 0);
      }
    });

    self.getCount = function () {
      var tx = editor.getContent({ format: 'raw' });
      var decoded = decodeHtml(tx);
      var decodedStripped = decoded.replace(/(<([^>]+)>)/ig, "").trim();
      var tc = decodedStripped.length;

      var editorParent = editor.iframeElement.closest('.acf-field-wysiwyg');
      if (editorParent) {
        var charWarnEl = editorParent.querySelector('.acf-char-count-warn');
        var charCounter = editorParent.querySelector('.mce-charactercount');
        if (charWarnEl) {
          var charWarnAt = charWarnEl.getAttribute('data-charactercount');
          if (charWarnAt) {
            charWarnAt = parseInt(charWarnAt);
            if (charWarnAt <= tc) {
              charWarnEl.classList.add('show-warn');
              if ( charCounter ) {
                charCounter.classList.add('show-warn');
              }
            } else {
              charWarnEl.classList.remove('show-warn');
              if ( charCounter ) {
                charCounter.classList.remove('show-warn');
              }
            }
          }
        }
      }


      return tc;
    };

    function decodeHtml(html) {
      var txt = document.createElement("textarea");
      txt.innerHTML = html;
      return txt.value;
    }
  });
})();
