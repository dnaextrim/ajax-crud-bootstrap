/*
 * ID: Saya akan sangat berterimakasih jika anda memberikan donasi untuk ini, anda dapat memberikan donasi berapapun
 *     yang anda mau, saya juga akan mencantumkan nama anda sebagai donatur kedalam file khusus yang akan selalu
 *     disertakan dalam source code ini, saya tidak akan mencantum jumlah donasi, saya hanya mencantumkan nama saja. Tolong
 *     cantumkan keterangan dengan isi "Donasi Ajax Crud Bootstrap".
 * EN: I would be very grateful if you make a donation to this, you can donate whatever you want, I will put your name
 *     as a donor into a special file will always be included in the source code, I would not fasten the number of
 *     donations, I just included name only. Please include a description with the contents of the "Donate Ajax Crud Bootstrap".
 *
 * PayPal: dony_cavalera_md@yahoo.com
 * Rekening Mandiri: 113-000-6944-858, Atas Nama: Dony Wahyu Isprananda
 **/

if( !('kecik' in window) ) window['kecik'] = {}
if( !('setting' in window['kecik']) ) {
  window['kecik'].setting = {
    'base_url'      : '',
    'insert_url'    : '',
    'update_url'    : '',
    'delete_url'    : '',
    'find_url'      : '',
    'get_url'       : '',

    'field_class'   : 'form-data',
    'view_class'    : 'form-view',
    'filter_class'  : 'filter',

    'form'          : '#form_data',
    'form_bootstrap': true,
    'form_box'      : '#form_box',
    'form_view'     : '#form_view',
    'form_fieldset' : '#form_fieldset',
    'table'         : '#list',
    'table_column'  : [],
    'table_order'   : [],

    'search_prefix' : 'search_',
    'loading'       : '#loading',

    'message_delete'    : 'Are you sure?',
    'message_ajax_error': 'Error: Lost Connection',

    'btn_add'       : '#btn_add',
    'btn_cancel'    : '#btn_cancel',
    'btn_save'      : '#btn_save',

    'bootstrap_convert' : true,
    'table_convert'     : true,

    'before_submit_func'    : true,
    'after_submit_func'     : true,
    'insert_func'           : null,
    'update_func'           : null,
    'get_func'              : null,
    'delete_func'           : null,
    'clear_func'            : null
  }
}

kecik.set = function(varname, value) {
    kecik.setting[varname] = value;
};


kecik.init = function(config) {
    $.each(config, function(key, val) {
        kecik.setting[key] = val;
    }) ;

    if (kecik.setting.bootstrap_convert === true) {
        //** Replace Input Submit to Button Tag
        $("input[type=submit]").replaceWith(function() {
            var replacement = $('<button>').html($(this).val());
            for (var i = 0; i < this.attributes.length; i++) {
                if (this.attributes[i].name != 'value')
                    replacement.attr(this.attributes[i].name, this.attributes[i].value);
            }
            return replacement;
        });

        //** Replace Input Reset to Button Tag
        $("input[type=reset]").replaceWith(function() {
            var replacement = $('<button>').html($(this).val());
            for (var i = 0; i < this.attributes.length; i++) {
                if (this.attributes[i].name != 'value')
                    replacement.attr(this.attributes[i].name, this.attributes[i].value);
            }
            return replacement;
        });

        //** Replace Input Reset to Button Tag
        $("input[type=button]").replaceWith(function() {
            var replacement = $('<button>').html($(this).val());
            for (var i = 0; i < this.attributes.length; i++) {
                if (this.attributes[i].name != 'value')
                    replacement.attr(this.attributes[i].name, this.attributes[i].value);
            }
            return replacement;
        });

        //$(kecik.setting.form+" input").not('input[type=button]').not('input[type=submit]').not('input[type=reset]').addClass('form-control');
        $(kecik.setting.form+" input").not('input[type=file]').addClass('form-control');
        $(kecik.setting.form+" textarea").addClass('form-control');

        if (!$(kecik.setting.btn_add).hasClass('btn')) {
            $(kecik.setting.btn_add).addClass('btn btn-success');
            $(kecik.setting.btn_add).prepend('<i class="fa fa-plus"></i> ');
        }

        if (!$(kecik.setting.btn_save).hasClass('btn')) {
            $(kecik.setting.btn_save).addClass('btn btn-primary');
            $(kecik.setting.btn_save).prepend('<i class="fa fa-floppy-o"></i> ');
        }

        if (!$(kecik.setting.btn_cancel).hasClass('btn')) {
            $(kecik.setting.btn_cancel).addClass('btn btn-danger');
            $(kecik.setting.btn_cancel).prepend('<i class="fa fa-close"></i> ');
        }

        /*if (!$(kecik.setting.form).hasClass('form-horizontal')) {
            $(kecik.setting.form).addClass('well form-horizontal');
            $(kecik.setting.form).attr('enctype',"multipart/form-data");
            if (typeof $.fn.dropzone !== 'undefined' && $('input[type=file]').length >0 ) {
                $(kecik.setting.form).addClass('dropzone');
            }
        }*/

        $(kecik.setting.form).attr('method', 'POST');
        $(kecik.setting.form).attr('action', kecik.setting.insert_url);

        if ($(kecik.setting.form_view).length <= 0) {
            form_view = '<div class="modal-dialog"> \
                        <div class="modal-content"> \
                            <div class="modal-header"> \
                                <h3 id="myModalLabel">View</h3> \
                            </div> \
                            \
                            <div class="modal-body"><div class="well form-horizontal"></div></div> \
                            <div class="modal-footer"> \
                                <button aria-hidden="true" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Close</button> \
                            </div> \
                        </div> \
                    </div>';

            $(kecik.setting.form).parent().append('<div id="'+kecik.setting.form_view.replace('#', '').replace('.', '')+'" class="modal fade" aria-hidden="true" role="dialog"></div>');
            $(kecik.setting.form_view).html(form_view);
        }

        if ($(kecik.setting.loading).length <= 0) {
            loading = '<div class="modal-dialog"> \
                        <div class="modal-content"> \
                            <div class="modal-header"> \
                                <h3 id="myModalLabel">In Progress.....</h3> \
                            </div> \
                            \
                            <div class="modal-body"> \
                                <!--<img src="http://localhost/kukang2/public/assets/sb-admin/images/gear_loader.gif">--> \
                                <i class="fa fa-gear fa-spin fa-5x"></i> \
                                <b>Please Wait.....</b> \
                            </div> \
                        </div> \
                    </div>';

            $(kecik.setting.form_box).parent().append('<div id="'+kecik.setting.loading.replace('#', '').replace('.', '')+'" class="modal fade" aria-hidden="true" role="dialog"></div>');
            $(kecik.setting.loading).html(loading);
        }

        if (!$(kecik.setting.form).hasClass('form-horizontal')) {
            $(kecik.setting.form).addClass('well form-horizontal');
            $(kecik.setting.form).attr('enctype',"multipart/form-data");
            if (typeof $.fn.dropzone !== 'undefined' && $('input[type=file]').length >0 ) {
                $(kecik.setting.form).addClass('dropzone');
            }

            $(kecik.setting.form).prepend($('<fieldset id="'+kecik.setting.form_fieldset.replace('#', '').replace('.', '')+'"></fieldset>'));

            $(kecik.setting.form).children().not('fieldset').each(function() {

                if ($(this).prop('tagName').toLowerCase() != 'fieldset')

                if ($(this).prop('tagName').toLowerCase() == 'label')
                    $(this).addClass('col-sm-3 control-label no-padding-right');

                if ( $(this).prop('tagName').toLowerCase() != 'label' &&
                     $(this).prop('tagName').toLowerCase() != 'button' &&
                     $(this).attr('type') != 'hidden' ) {
                    view = $(this).prev().prop('outerHTML');
                    view += '<div class="col-sm-3"><p id="'+$(this).attr('id')+'" class="form-control-static '+kecik.setting.view_class+'"></p></div>';
                    $(kecik.setting.form_view).find('.well.form-horizontal').append($('<div class="form-group"></div>').html(view));

                    input = $(this).prev().wrap('<div class="form-group"></div>').parent().append($('<div class="col-sm-3"></div>').html($(this).addClass(kecik.setting.field_class)));
                    $(kecik.setting.form+' fieldset').append(input);
                }

            });

            $(kecik.setting.form+' fieldset').append($(kecik.setting.form+' input[type=hidden]'));
            $(kecik.setting.form+' fieldset').append($(kecik.setting.form+' button'));
            $(kecik.setting.form+' button').wrapAll('<div class="clearfix form-actions"></div>').wrapAll('<div class="col-md-offset-3 col-md-9"></div>');

            $(kecik.setting.form).wrapAll('<div id="'+kecik.setting.form_box.replace('#', '').replace('.', '')+'"></div>');
        }

    }

    $('.hidden_input').hide();
    $(kecik.setting.form_box).hide();
    $(kecik.setting.form_view).hide();


    if (typeof $.fn.select2 !== 'undefined') {
        $(kecik.setting.form+" select").addClass('select2');
        $(kecik.setting.form+" select").css('width', '100%');
    }

    if (!$(kecik.setting.form+" select").hasClass('select2'))
        $(kecik.setting.form+" select").addClass('form-control');

    if (typeof $.fn.select2 !== 'undefined')
        $(".select2").select2({'allowClear':true});

    if (typeof $.fn.chosen !== 'undefined')
        $(".chosen").chosen();

    if (typeof autosize !== 'undefined')
        autosize($('textarea.autosize'));


    if (typeof $.fn.datepicker !== 'undefined') {
        $('.datepicker').datepicker({
           autoclose: true,
           todayHighlight: true
        });

        $('.datepicker').each(function() {
            $(this).wrap('<div class="input-group date"></div>').parent().append('<span class="input-group-addon"><i class="fa fa-calendar"></i></span>');
        });
    }

    if (typeof $.fn.timepicker !== 'undefined') {
        $('.timepicker').timepicker();

        $('.timepicker').each(function() {
            $(this).wrap('<div class="input-group date"></div>').parent().append('<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>');
        });
    }

    if (typeof $.fn.datetimepicker !== 'undefined') {
        $('.datetimepicker').datetimepicker();

        $('.datetimepicker').each(function() {
            $(this).wrap('<div class="input-group date"></div>').parent().append('<span class="input-group-addon"><i class="fa fa-calendar"></i></span>');
        });
    }

    if (typeof $.fn.mask !== 'undefined') {
        $('input').each(function() {
            if ($(this).data('mask') != '')
                $(this).mask($(this).data('mask'));
        });
    }

    if (typeof $.fn.markdown !== 'undefined')
        $("textarea.markdown").markdown({autofocus:false,savable:false})

    if (typeof $.fn.colorpicker !== 'undefined')
        $('.colorpicker').colorpicker();

    if (typeof $.fn.dropzone !== 'undefined') {
        $('input[type=file]').each(function() {
            //$(this).wrap('<div class="fallback"></div>');
            $(this).dropzone({ url: $(this).data('post') });
        });
    }

    $(".expand_fill").collapse();



    $( document ).ajaxStop(function() {
        $(kecik.setting.loading).modal('hide');
    });

    $( document ).ajaxError(function() {
        $(kecik.setting.loading).modal('hide');
        alert(kecik.setting.message_ajax_error);
    });

    $('.'+kecik.setting.filter_class+':first').focus();

    $(kecik.setting.btn_add).bind('click', function() {
        $(kecik.setting.form_box).show();
        $('.'+kecik.setting.field_class+':first').focus();
    });

    $(kecik.setting.btn_cancel).bind('click', function() {
        $(kecik.setting.form_box).hide();
        kecik.clearform();
    });

    $(".filter").focus(function(){
        this.select();
    });



    $('form'+kecik.setting.form).on('submit', function(event) {
        var form = $(this);
        var file_input = $(this).find('input[type=file]');

        $(kecik.setting.loading).modal('show');
        event.preventDefault();

        if ($.isFunction(kecik.setting.before_submit_func)) {
            if ( kecik.setting.before_submit_func() === false) return false;
        }

        var form_serial = form.serialize();
        var submit_url = form.attr('action');

        if (file_input.length > 0 ) {
            var deferred ;
            if( "FormData" in window ) {

                //for modern browsers that support FormData and uploading files via ajax
                var fd = new FormData(form.get(0));


                upload_in_progress = true;
                deferred = $.ajax({
                    url: submit_url,
                    type: form.attr('method'),
                    processData: false,
                    contentType: false,
                    //dataType: 'json',
                    data: fd,
                    xhr: function() {
                        var req = $.ajaxSettings.xhr();
                        if (req && req.upload) {
                            req.upload.addEventListener('progress', function(e) {
                                if(e.lengthComputable) {
                                    var done = e.loaded || e.position, total = e.total || e.totalSize;
                                    var percent = parseInt((done/total)*100) + '%';
                                    //percentage of uploaded file
                                }
                            }, false);
                        }
                        return req;
                    },
                    beforeSend : function() {
                    },
                    success : function() {

                    }
                });

            } else {
                //for older browsers that don't support FormData and uploading files via ajax
                //we use an iframe to upload the form(file) without leaving the page
                upload_in_progress = true;
                deferred = new $.Deferred

                var iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
                $('form').after('<iframe id="'+iframe_id+'" name="'+iframe_id+'" frameborder="0" width="0" height="0" src="about:blank" style="position:absolute;z-index:-1;"></iframe>');
                $('form').kecikend('<input type="hidden" name="temporary-iframe-id" value="'+iframe_id+'" />');
                $('form').next().data('deferrer' , deferred);//save the deferred object to the iframe
                $('form').attr({'method' : 'POST', 'enctype' : 'multipart/form-data', 'target':iframe_id, 'action':submit_url});

                $('form').get(0).submit();

                //if we don't receive the response after 60 seconds, declare it as failed!
                setTimeout(function(){
                    var iframe = document.getElementById(iframe_id);
                    if(iframe != null) {
                        iframe.src = "about:blank";
                        $(iframe).remove();

                        deferred.reject({'status':'fail','message':'Timeout!'});
                    }
                } , 60000);
            }


            ////////////////////////////
            deferred.done(function(result){
                upload_in_progress = false;

                if(result == '') {
                    //alert(result.message + ". Lokasi Server: " + result.url)
                    var oTable = $(kecik.setting.table).dataTable();
                    $.post(kecik.setting.find_url, null, function (json) {
                        oTable.fnClearTable();
                        if (json.data.length > 0) {
                            oTable.fnAddData(json.data);
                            oTable.fnDraw();
                        }
                    }, 'json');
                } else {
                    result = $.parseJSON(result);
                    alert("File gagal terupload. " + result.message);
                }

                kecik.clearform();
                $(kecik.setting.form_box).hide();

                if ($.isFunction(kecik.setting.after_submit_func)) {
                    if ( kecik.setting.after_submit_func() === false) return false;
                }
            }).fail(function(res){
                upload_in_progress = false;
                alert("There was an error");
                kecik.clearform();
                $(kecik.setting.form_box).hide();
            });

            deferred.promise();
            return false;
        } else {
            $(kecik.setting.form_fieldset).attr('disabled', true);
            $.post( form.attr('action'), form_serial, function(data) {

                kecik.clearform();
                $(kecik.setting.form_box).hide();

                if ($.isFunction(kecik.setting.after_submit_func)) {
                    if ( kecik.setting.after_submit_func() === false) return false;
                }

                var oTable = $(kecik.setting.table).dataTable();
                $.post(kecik.setting.find_url, null, function (json) {
                    oTable.fnClearTable();
                    if (json.data.length > 0) {
                        oTable.fnAddData(json.data);
                        oTable.fnDraw();
                    }
                }, 'json');

            });

            return false;
        }
    });


    var action = [
        {
            "data": "actions",
            "sortable": false,
            "render": function (id) {
                var actions = '<div class="visible-md visible-lg hidden-sm hidden-xs">';
                var actions_mobile = '<div class="visible-xs visible-sm hidden-md hidden-lg"> \
                            <div class="dropdown"> \
                                <button class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true"> \
                                    <i class="fa fa-caret-down fa-lg"></i> \
                                </button> \
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';

                $.each(kecik.setting.table_actions, function(label, data) {
                    actions += '<a  class="'+data.class+'" href="#'+label+'" onClick="return '+data.action+'('+id+')" title="'+data.desc+'"> \
                                    <i class="'+data.icon+' fa-lg hvr-grow-shadow"></i> \
                                </a>';

                    actions_mobile += '<li> \
                                        <a href="#'+label+'" class="tooltip-info" onClick="return '+data.action+'('+id+')" data-rel="tooltip" title="'+data.desc+'"> \
                                            <span class="text-primary"> \
                                                <i class="'+data.icon+' fa-lg"></i> '+data.desc+'\
                                            </span> \
                                        </a> \
                                    </li>';
                });
                actions += '</div>';
                actions_mobile += '</ul> \
                            </div> \
                        </div>';

                return actions+actions_mobile;
            },
        },
    ];


    var column = $.merge(action, kecik.setting.table_column);

    if (kecik.setting.table_convert === true) {

        if (!$(kecik.setting.table).hasClass('table')) {
            head = $(kecik.setting.table).find('thead tr');
            head.addClass('navbar-default');
            head.prepend('<th>&nbsp;</th>');
        }

        if (!$(kecik.setting.table).hasClass('table'))
            $(kecik.setting.table).addClass('table');

        if (!$(kecik.setting.table).hasClass('table-striped'))
            $(kecik.setting.table).addClass('table-striped');

        if (!$(kecik.setting.table).hasClass('table-hover'))
            $(kecik.setting.table).addClass('table-hover');
    }

    $(kecik.setting.loading).modal('show');
    var oTable = $(kecik.setting.table).dataTable({
            "ajax": {
            "url": kecik.setting.find_url,
            "type": "POST",
        },
        "processing": true,
        //"serverSide": true,
        "order": kecik.setting.table_order,
        "columns": column,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('td', nRow).closest('tr').addClass(aData['status'] === 'false' ? 'danger' : '');
            return nRow;
        }
    });

    $('input[type=search]:first').focus();

};

kecik.clearform = function(callback) {
    $('input[type=text].'+kecik.setting.field_class).val('');
    $('input[type=file].'+kecik.setting.field_class).val('');
    $('input[type=hidden].'+kecik.setting.field_class).val('');
    $('textarea.'+kecik.setting.field_class).val('');
    $('.editor.'+kecik.setting.field_class).text('');
    $('select.'+kecik.setting.field_class+' option:first').attr('selected','selected');
    var select2 = $('select.form-data.select2');
    select2.each(function(i,item){
      $(item).select2("destroy");
    });
    select2.select2();
    $(kecik.setting.form).attr('action', kecik.setting.insert_url);
    $('.foto_box.'+kecik.setting.field_class).hide();
    $(kecik.setting.form_fieldset).attr('disabled', false);
    if ($(kecik.setting.form)[0] !== 'undefined')
        $(kecik.setting.form)[0].reset();
    $(kecik.setting.loading).modal('hide');

    if ($.isFunction(callback))
        kecik.setting.clear_func = callback;
    else if ($.isFunction(kecik.setting.clear_func))
        kecik.setting.clear_func();

    $('input[type=search]:first').focus();

};

kecik.Insert = function(callback) {
    if ($.isFunction(callback))
        kecik.setting.insert_func = callback;
    else if ($.isFunction(kecik.setting.insert_func))
        kecik.setting.insert_func();
};

kecik.Update = function(callback) {
    if ($.isFunction(callback))
        kecik.setting.update_func = callback;
    else if ($.isFunction(kecik.setting.update_func))
        kecik.setting.update_func();
};

kecik.Get = function(id, callback) {
    var $data;
    $(kecik.setting.loading).modal('show');
    $.post(kecik.setting.get_url, // request ke file load_data.php
        id,
        function(data){

            if(data.error == undefined){ // jika respon error tidak terdefinisi maka pengambilan data sukses
                $(kecik.setting.form_box).show();

                for(var x=0; x<data.length; x++) {
                    $data = data[x];
                    $.each($data, function(id) {

                        if ( !$.isPlainObject(eval('$data.'+id)) ) {

                            if ( eval('$data.'+id) != '' && !$('#'+id).is('textarea') && $('input[id=\''+eval('$data.'+id)+'\']').attr('type') == 'radio' )
                                $('input[id=\''+eval('$data.'+id)+'\]').attr('checked', true);
                            else if ( !$('#'+id).is('textarea') && $('input'+id).attr('type') == 'checkbox' ) {
                                if ( eval('$data.'+id) == 1 )
                                    $('input#'+id).attr('checked', true);
                            } else {
                                if ($('#'+id).is('textarea')) {
                                    if ( $('#'+id).text() != undefined )
                                        $('#'+id).text(eval('$data.'+id))

                                    if ( $('.editor').html() != undefined )
                                        $('.editor').html(eval('$data.'+id));
                                } else {
                                    if ( $('#'+id).val() != undefined )
                                        $('#'+id).val(eval('$data.'+id))
                                }

                                if ( $('#'+id+"_old").val() != undefined )
                                    $('#'+id+"_old").val(eval('$data.'+id))
                            }
                        }

                    });
                }

                if (typeof $.fn.select2 !== 'undefined') {
                    $(".select2."+kecik.setting.field_class).select2('destroy');
                    $(".select2."+kecik.setting.field_class).select2();
                }

                $('form').attr('action', kecik.setting.update_url);

                $('.'+kecik.setting.field_class+':first').focus();

                if ($.isFunction(callback))
                    kecik.setting.get_func = callback;
                else if ($.isFunction(kecik.setting.get_func))
                    kecik.setting.get_func();

            }else{
                alert(data.error); // jika ada respon error tampilkan alert
            }

    }, 'json');
};

kecik.Delete = function(id, callback) {
    bootbox.dialog({
        message: kecik.setting.message_delete,
        title: "Confirmation",
        buttons: {
            yes: {
                label: "Yes",
                className: "btn-success",
                callback: function() {
                    $(kecik.setting.loading).modal('show');
                    $.post(kecik.setting.delete_url, // request ke file load_data.php
                    id,
                    function(data){

                        if ($.isFunction(callback))
                            kecik.setting.delete_func = callback;
                        else if ($.isFunction(kecik.setting.delete_func))
                            kecik.setting.delete_func();

                        var oTable = $(kecik.setting.table).dataTable();
                        $.post(kecik.setting.find_url, null, function (json) {

                            oTable.fnClearTable();
                            if (json.data.length > 0) {
                                oTable.fnAddData(json.data);
                                oTable.fnDraw();
                            }
                        }, 'json');
                    });
                }
            },
            no: {
                label: "No",
                className: "btn-danger",
                callback: function() {}
            },
        }
    });
};

kecik.View = function(id) {
    $(kecik.setting.loading).modal('show');
    $.post(kecik.setting.get_url, // request ke file load_data.php
    id,
    function(data){

        if(data.error == undefined){ // jika respon error tidak terdefinisi maka pengambilan data sukses

            for(var x=0; x<data.length; x++) {
                $data = data[x];

                $.each($data, function(id) {
                    if ( !$.isPlainObject(eval('$data.'+id)+'.'+kecik.setting.view_class) ) {
                        if ( $('#'+id+'.'+kecik.setting.view_class).html() != undefined ) {
                            switch(id) {
                                /**
                                 * Add Your custom Case if you need
                                 */
                                case 'image':
                                    $('#'+id+'.'+kecik.setting.view_class).hide();
                                    if (eval('$data.'+id)) {
                                        $('#'+id+'.'+kecik.setting.view_class).attr('src', eval('$data.'+id));
                                        $('#'+id+'.'+kecik.setting.view_class).show();
                                    }
                                break;
                                default:
                                    $('#'+id+'.'+kecik.setting.view_class).html(eval('$data.'+id));
                            }
                        }
                    }

                });
            }


            $(kecik.setting.form_view).modal('show');

        }else{
            alert(data.error); // jika ada respon error tampilkan alert
        }

    }, 'json');
};


kecik.beforeSubmit = function(callback) {
    if (typeof callback !== 'undefined')
        kecik.setting.before_submit_func = callback;
    else
        kecik.setting.before_submit_func = true;
};

kecik.afterSubmit = function(callback) {
    if (typeof callback !== 'undefined')
        kecik.setting.after_submit_func = callback;
    else
        kecik.setting.after_submit_func = true;
};
