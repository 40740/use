(function() {
     /* Register the buttons */
     tinymce.create('tinymce.plugins.MyButtons', {
          init : function(ed, url) {
               /**
               * Inserts shortcode content
               */
               ed.addButton( 'button_ckplayer', {
                    text : '',
                    icon: 'wp-media-library',
                    title : '插入ckplayer视频',
                    onclick : function() {
                         ed.windowManager.open( {
                              title: 'ckplayer视频地址',
                              body: [{
                                   type: 'textbox',
                                   name: 'videoSrc',
                                   label: false,
                                   value: '',
                                   multiline: true,
                                   minWidth: 300,
                                   minHeight: 100
                              }],
                              onsubmit: function( e ) {
                                   ed.selection.setContent('[ckplayer]'+e.data.videoSrc+'[/ckplayer]');
                              }
                         });
                    }
               });

               ed.addButton( 'button_erphpdown', {
                    text : '',
                    icon: 'wp-media-lock dashicons-before dashicons-admin-network',
                    title : '插入收费内容',
                    onclick : function() {
                         ed.windowManager.open( {
                              title: '收费查看隐藏内容',
                              body: [{
                                   type: 'textbox',
                                   name: 'erphpdownCon',
                                   label: false,
                                   value: '',
                                   multiline: true,
                                   minWidth: 300,
                                   minHeight: 100
                              }],
                              onsubmit: function( e ) {
                                   ed.selection.setContent('[erphpdown]'+e.data.erphpdownCon+'[/erphpdown]');
                              }
                         });
                    }
               });

               ed.addButton( 'button_reply', {
                    text : '',
                    icon: 'wp-media-comment dashicons-before dashicons-admin-comments',
                    title : '插入回复内容',
                    onclick : function() {
                         ed.windowManager.open( {
                              title: '回复查看隐藏内容',
                              body: [{
                                   type: 'textbox',
                                   name: 'replyCon',
                                   label: false,
                                   value: '',
                                   multiline: true,
                                   minWidth: 300,
                                   minHeight: 100
                              }],
                              onsubmit: function( e ) {
                                   ed.selection.setContent('[reply]'+e.data.replyCon+'[/reply]');
                              }
                         });
                    }
               });

               ed.addButton( 'button_login', {
                    text : '',
                    icon: 'wp-media-preview dashicons-before dashicons-admin-users',
                    title : '插入登录内容',
                    onclick : function() {
                         ed.windowManager.open( {
                              title: '登录查看隐藏内容',
                              body: [{
                                   type: 'textbox',
                                   name: 'loginCon',
                                   label: false,
                                   value: '',
                                   multiline: true,
                                   minWidth: 300,
                                   minHeight: 100
                              }],
                              onsubmit: function( e ) {
                                   ed.selection.setContent('[login]'+e.data.loginCon+'[/login]');
                              }
                         });
                    }
               });
          },
          createControl : function(n, cm) {
               return null;
          },
     });
     /* Start the buttons */
     tinymce.PluginManager.add( 'mobantu_button_script', tinymce.plugins.MyButtons );
})();