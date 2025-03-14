/*
Copyright 2017 Ziadin Givan

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

https://github.com/givanz/VvvebJs
*/

//doesn't support elements from iframe https://github.com/tinymce/tinymce/issues/3117
//use ckeditor instead that supports inline editing for iframe elements

var tinyMceOptions = {
    target                        : false,
    inline                        : false,  //see comment above
    // toolbar_mode                  : 'sliding',
    toolbar_persist               : true,
    plugins                       : 'autolink save directionality code visualblocks visualchars fullscreen image link media table charmap lists quickbars emoticons',
    menubar                       : false,
    toolbar                       : [
        'undo redo | bold italic underline strikethrough | table | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist',
        'insertfile image media link | forecolor backcolor removeformat | fullscreen | ltr rtl | code | charmap emoticons ',
        'cancel save'
    ],
    toolbar_sticky                : true,
    height                        : 200,
    quickbars_insert_toolbar      : 'quickmedia quicklink quicktable',
    quickbars_selection_toolbar   : 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    quickbars_image_toolbar       : 'alignleft aligncenter alignright | rotateleft rotateright | imageoptions',
    noneditable_noneditable_class : 'mceNonEditable',
    contextmenu                   : 'link image imagetools table',
    skin                          : 'oxide',
    content_css                   : '',
    content_style                 : 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
    images_upload_url             : 'postAcceptor.php',
    relative_urls                 : false,

    image_advtab          : true,
    image_caption         : true,
    paste_data_images     : true,
    image_uploadtab       : true,
    images_file_types     : 'jpeg,jpg,png,gif',
    images_upload_handler : function(blobInfo, success, failure) {
        var base64str = 'data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64();
        success(base64str);
    },
    file_picker_callback  : function(callback, value, meta) {
        if (!Vvveb.MediaModal) {
            Vvveb.MediaModal = new MediaModal(true);
            // Vvveb.MediaModal.mediaPath = mediaPath;
        }
        Vvveb.MediaModal.open(null, callback);
    },

    branding : false,
};


Vvveb.WysiwygEditor = {
    isActive : false,
    oldValue : '',
    doc      : false,
    editor   : false,

    init : function(doc) {
        this.doc = doc;
    },

    edit : function(element) {
        if(element.tagName =="BODY"){
            alert("The <body> component cannot be edited.");
            return;
        }
        this.element = element;
        this.isActive = true;
        this.oldValue = element.innerHTML;
        Vvveb.Builder.selectPadding = 0;
        Vvveb.Builder.highlightEnabled = false;
        tinyMceOptions.target = element;
        this.editor = tinymce.init(tinyMceOptions);

        setTimeout(function() {
            document.getElementById("select-box").style.display = "none";
        }, 1000);
    },

    saveandclose : function() {
        if (tinymce && tinymce.activeEditor) {
            tinymce.activeEditor.destroy();
            Vvveb.Builder.highlightEnabled = true;
            this.isActive = false;

            node = this.element;
            Vvveb.Undo.addMutation({
                type     : 'characterData',
                target   : node,
                oldValue : this.oldValue,
                newValue : node.innerHTML
            });
        }
    },

    destroy : function(element) {
        // console.trace(element);
        // if (tinymce && tinymce.activeEditor) {
        //     tinymce.activeEditor.destroy();
        //     Vvveb.Builder.highlightEnabled = true;
        //     this.isActive = false;
        //
        //     node = this.element;
        //     Vvveb.Undo.addMutation({
        //         type     : 'characterData',
        //         target   : node,
        //         oldValue : this.oldValue,
        //         newValue : node.innerHTML
        //     });
        // }
    }
};
