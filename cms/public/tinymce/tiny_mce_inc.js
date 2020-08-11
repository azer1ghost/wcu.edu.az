// TINYMCE 4.1.7 (2014-11-27)
    if ($('.myBasicEditor').length > 0) {
        tinymce.init({
            height : "300",
            selector: "textarea.myBasicEditor",
            theme: "modern",
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
            ],
            templates: [
                {title: 'Sag sekil', url: '/cms/public/tinymce/template/template1.html'},
                {title: 'Sag block metn', url: '/cms/public/tinymce/template/template5.html'},
                {title: 'Yazi block 1', url: '/cms/public/tinymce/template/template2.html'},
                {title: 'Yazi block 2', url: '/cms/public/tinymce/template/template3.html'},
                {title: 'Yazi block 3', url: '/cms/public/tinymce/template/template4.html'},
                {title: 'Video', url: '/cms/public/tinymce/template/template6.html'},
            ],
            content_css: "/cms/public/css/tinymce-content.css",
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code | fontselect | fontsizeselect",
            font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
            fontsize_formats: '8px 10px 12px 14px 18px 24px 36px',
            image_advtab: true,
            toolbar_items_size: 'normal',
            style_formats: [
                {title: 'Headers', items: [
                    {title: 'h1', block: 'h1'},
                    {title: 'h2', block: 'h2'},
                    {title: 'h3', block: 'h3'},
                    {title: 'h4', block: 'h4'},
                    {title: 'h5', block: 'h5'},
                    {title: 'h6', block: 'h6'}
                ]},

                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'}
            ],
            relative_urls: false,
            remove_script_host : false,
            entity_encoding: 'raw',
            external_filemanager_path: "/cms/public/tinymce/filemanager/",
            filemanager_title: "Filemanager",
            filemanager_access_key: "myPrivateKey",
            external_plugins: {"filemanager": "/cms/public/tinymce/filemanager/plugin.min.js"}
        });
    }