$(document).ready(function () {
    // check all
    $(".checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    // DataTable
    if ($('#dataTables').length > 0) {
        var $dataTable = $('#dataTables'),
            orderdisable = $dataTable.attr('data-orderdisable');
        if(orderdisable == null) {
            orderdisable = '[1,2]';
        }

        $dataTable.DataTable({
            "columnDefs": [{
                "targets": JSON.parse(orderdisable),
                "orderable": false
            }],
            "iDisplayLength": 50,
            "order": [[0, "asc"]]
        });
    }

    if ($('#dataTablesNoSorting').length > 0) {
        var $dataTable = $('#dataTablesNoSorting');
        $dataTable.DataTable({
            "columnDefs": [{
                "targets": [1,2],
                "orderable": false
            }],
            "iDisplayLength": 50,
            "aaSorting": []
        });
    }


    // multiselect
    if($('.multiselect').length>0){
        $('.multiselect').multiselect({
            includeSelectAllOption: true,
            buttonWidth: '100%',
            maxHeight: 300,
            enableCaseInsensitiveFiltering: true
        });
    }

    // slug title
    function onChange($this) {
        var input = $this,
            lang = input.closest('.tab-pane').attr('data-lang'),
            dInput = input.val();
        $.ajax({
            type: "POST",
            url: '/cms/pages/slug/',
            dataType: 'json',
            data: {slug:dInput, lang:lang},
            success: function(data) {
                input.closest('.tab-pane').find('.slug').val(data.text);
            }
        });
    }
    $('.title')
        .change(function(){
            onChange($(this));
        })
        .keyup(function(){
            onChange($(this));
        });

    // tabs
    $('.nav-tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // langs
    $('.lang a').on('click', function(e){
        e.preventDefault();
        var link = $(this).attr('href');
        $.get( link, function( data ) {
            location.reload();
        });
    });

    // upload img
    if($('#file').length>0) {

        $('#file').change(function(){
            $('.bar').show();

            var f = document.getElementById('file'),
                pb = document.getElementById('pb'),
                pt = document.getElementById('pt'),
                menu =$('#file').attr('data-menu');

            app.uploader({
                files:f,
                progressBar: pb,
                progressText: pt,
                processor: '/cms/'+menu+'/multiupload',
                finished: function(data){
                    var uploads = document.getElementById('uploads'),
                        succeeded = document.getElementById('succeeded'),
                        failed = document.getElementById('failed'),
                        linkdelete,
                        anchor,
                        span,
                        img,
                        x;

                    if(data.failed.length){
                        failed.innerHTML = '<p><strong>Şəkil aşağıdakı səbəblərə görə yüklənmədi:</strong></p>';
                    }
                    uploads.textContent = '';
                    for(x=0; x < data.succeeded.length; x = x + 1){

                        img = document.createElement('img');
                        img.src = '/upload/Image/'+menu+'/' + data.succeeded[x].file;
                        img.height = '100';

                        linkdelete = document.createElement('a');
                        linkdelete.href = '/cms/'+menu+'/deletephoto/' + data.succeeded[x].id +'/'+ $("#sid").val();
                        linkdelete.className = 'delete';

                        anchor = document.createElement('div');
                        anchor.className= "img ui-sortable-handle";
                        anchor.setAttribute("id", "recordsArray_" + data.succeeded[x].id);
                        anchor.appendChild(img);
                        anchor.appendChild(linkdelete);

                        succeeded.appendChild(anchor);
                    }

                    for(x=0; x < data.failed.length; x = x + 1){
                        span = document.createElement('span');
                        span.textContent = data.failed[x].name;
                        failed.appendChild(span);
                    }

                    uploads.appendChild(succeeded);
                    uploads.appendChild(failed);

                    setTimeout(function(){
                        $('.bar').hide().find('.bar-fill').css('width', '0');
                    }, 2000);
                },
                error: function(){
                    //console.log('Not wording');
                }
            });

        });

        $(document).on('click', '.delete', function(event){
            event.preventDefault();
            var thiss = $(this);
            var url = thiss.attr('href');
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(data) {
                    if(data.success == '1'){
                        thiss.closest('.img').remove();
                    } else {
                        alert(data.error);
                    }
                }
            });
        });

    }

    // sortable
    if ($('#succeeded div').length > 0) {
        $("#succeeded").sortable({
            opacity: 0.6,
            cursor: 'move',
            update: function () {
                var order = $(this).sortable("serialize");
                var menu = $("#succeeded").attr('data-menu');
                $.post('/cms/'+menu+'/orderphoto', order, function (theResponse) {
                    $("#failed").text(theResponse);
                });
            }
        });
    }

    // datetimepicker
    if($('.datetimepicker').length>0) {
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:00'
        });
    }

    $("#menu").metisMenu();
});


