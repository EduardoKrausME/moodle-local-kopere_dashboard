(function() {

	tinymce.create('tinymce.plugins.ResponsiveFileManager', 
	{

		init : function(editor, url)
		{

			// File manager callback
			function openmanager() 
			{
				editor.focus(true);
				var title="RESPONSIVE FileManager";
				if (typeof editor.settings.filemanager_title !== "undefined" && editor.settings.filemanager_title) {
					title=editor.settings.filemanager_title;
				}
				var akey="key";
				if (typeof editor.settings.filemanager_access_key !== "undefined" && editor.settings.filemanager_access_key) {
					akey=editor.settings.filemanager_access_key;
				}
				var sort_by="";
				if (typeof editor.settings.filemanager_sort_by !== "undefined" && editor.settings.filemanager_sort_by) {
					sort_by="&sort_by="+editor.settings.filemanager_sort_by;
				}
				var descending="false";
				if (typeof editor.settings.filemanager_descending !== "undefined" && editor.settings.filemanager_descending) {
					descending=editor.settings.filemanager_descending;
				}
				var fldr="";
				if (typeof editor.settings.filemanager_subfolder !== "undefined" && editor.settings.filemanager_subfolder) {
					fldr="&fldr="+editor.settings.filemanager_subfolder;
				}
				// Disabled because of bug
				var type=2;
				if (typeof editor.settings.filemanager_type !== "undefined" && editor.settings.filemanager_type) {
					if ($.isNumeric(editor.settings.filemanager_type) === true && editor.settings.filemanager_type > 0 && editor.settings.filemanager_type <= 3) {
						type=editor.settings.filemanager_type;
					}
					else if (editor.settings.filemanager_type == 'image'){
						type = 1;
					}
					else if (editor.settings.filemanager_type == 'media'){
						type = 3;
					}
					else {
						type = 2;
					}
				}

				win = editor.windowManager.open({
					title: title,
					file: editor.settings.external_filemanager_path+'dialog.php?type=4&descending='+descending+sort_by+fldr+'&lang='+editor.settings.language+'&akey='+akey,
					width: 860,
					height: 570,
					inline: 1,
					resizable: true,
					maximizable: true
				});
			}

			editor.settings.file_browser_callback = filemanager;
			
			function filemanager (id, value, type, win) {
				// DEFAULT AS FILE
				urltype=2;
				if (type=='image') { urltype=1; }
				if (type=='media') { urltype=3; }
				var title="RESPONSIVE FileManager";
				if (typeof editor.settings.filemanager_title !== "undefined" && editor.settings.filemanager_title) {
					title=editor.settings.filemanager_title;
				}
				var akey="key";
				if (typeof editor.settings.filemanager_access_key !== "undefined" && editor.settings.filemanager_access_key) {
					akey=editor.settings.filemanager_access_key;
				}
				var sort_by="";
				if (typeof editor.settings.filemanager_sort_by !== "undefined" && editor.settings.filemanager_sort_by) {
					sort_by="&sort_by="+editor.settings.filemanager_sort_by;
				}
				var descending="false";
				if (typeof editor.settings.filemanager_descending !== "undefined" && editor.settings.filemanager_descending) {
					descending=editor.settings.filemanager_descending;
				}
				var fldr="";
				if (typeof editor.settings.filemanager_subfolder !== "undefined" && editor.settings.filemanager_subfolder) {
					fldr="&fldr="+editor.settings.filemanager_subfolder;
				}

				tinymce.activeEditor.windowManager.open({
					title: title,
					file: editor.settings.external_filemanager_path+'dialog.php?type='+urltype+'&descending='+descending+sort_by+fldr+'&lang='+editor.settings.language+'&akey='+akey,
					width: 860,  
					height: 570,
					resizable: true,
					maximizable: true,
					inline: 1
					}, {
					setUrl: function (url) {
						var fieldElm = win.document.getElementById(id);
						fieldElm.value = editor.convertURL(url);
						if ("fireEvent" in fieldElm) {
							fieldElm.fireEvent("onchange")
						} else {
							var evt = document.createEvent("HTMLEvents");
							evt.initEvent("change", false, true);
							fieldElm.dispatchEvent(evt);
						}
					}
				});
			};

			// Register buttons
			editor.addButton('responsivefilemanager', 
			{
				title : 'Browse files',
				image : url + '/img/insertfile.gif',
				shortcut: 'Ctrl+E',
                onclick: openmanager
			});
		}
		
	});

	// Register plugin
	tinymce.PluginManager.add('responsivefilemanager', tinymce.plugins.ResponsiveFileManager);
})();
