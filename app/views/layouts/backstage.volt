<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Seven</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  {{stylesheet_link("public/backstage/bootstrap/css/bootstrap.min.css")}}
  <!-- Font Awesome -->
  {{stylesheet_link("public/backstage/awesome/css/font-awesome.min.css")}}
  <!-- Ionicons -->
  {{stylesheet_link("public/backstage/ionicons/css/ionicons.min.css")}}
  <!-- Theme style -->
  {{stylesheet_link("public/backstage/dist/css/AdminLTE.min.css")}}
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  {{stylesheet_link("public/backstage/dist/css/skins/_all-skins.min.css")}}
  <!-- iCheck -->
  {{stylesheet_link("public/backstage/plugins/iCheck/all.css")}}
  <!-- Morris chart -->
  {{stylesheet_link("public/backstage/plugins/morris/morris.css")}}
  <!-- jvectormap -->
  {{stylesheet_link("public/backstage/plugins/jvectormap/jquery-jvectormap-1.2.2.css")}}
  
    <!-- Daterange picker -->
  {{stylesheet_link("public/backstage/plugins/daterangepicker/daterangepicker.css")}}
    <!-- Bootstrap time Picker -->
  {{stylesheet_link("public/backstage/plugins/timepicker/bootstrap-timepicker.min.css")}}
  <!-- Datetime picker-->
  {{stylesheet_link("public/backstage/plugins/datetimepicker/bootstrap-datetimepicker.min.css")}}
  <!-- bootstrap wysihtml5 - text editor -->
  {{stylesheet_link("public/backstage/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css")}}
  {{stylesheet_link("public/backstage/plugins/codemirror/codemirror.css")}}
  <!-- bootstrap toastr -->
  {{stylesheet_link("public/backstage/plugins/bootstrap-toastr/toastr.min.css")}}

  <!-- nprogress -->
  {{stylesheet_link("public/backstage/plugins/nprogress/nprogress.css")}}

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  {{javascript_include("public/backstage/html5shiv/html5shiv.min.js")}}
  {{javascript_include("public/backstage/respond/1.4.2/respond.min.js")}}
  <![endif]-->


  <style type="text/css">
      .CodeMirror {border: 1px solid black; font-size:13px}
      .pagination input.input-current {
          display: inline;
          float: left;
          width: 40px!important;
          text-align: center;
          height: auto;
          padding: 4px 0;
          border-left: 0;
          border-right: none;
          font-weight: 400;
          color: #333;
          background-color: #fff;
          border-top: 1px solid #e5e5e5;
          border-bottom: 1px solid #e5e5e5;
          box-shadow: none;
      }
      .input-icon {
          position: relative;
      }
      .input-icon>i, .radio-list>label {
          display: block;
      }
      .input-icon>i {
          color: #ccc;
          position: absolute;
          margin: 11px 2px 4px 10px;
          z-index: 3;
          width: 16px;
          height: 16px;
          font-size: 16px;
          text-align: center;
      }
      .input-icon.right>i {
          right: 8px;
          float: right;
      }
       .finder table{
               white-space: nowrap;
       }
      .form-group .icheckbox_minimal-red, .iradio_minimal-red{
          width: 20px;
          height: 20px;
      }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  {{partial('public/header')}}
  <!-- Left side column. contains the logo and sidebar -->
  {{partial('public/sidebar')}}
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="J_backstage_content">
    <!-- Content Header (Page header) -->
    {{ content() }}
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  {{partial('public/footer')}}

</div>
<div class="modal fade" id="J_modal" style="display: none;">

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
{{javascript_include("public/backstage/plugins/jQuery/jquery-2.2.3.min.js")}}
<!-- jQuery UI 1.11.4 -->
{{javascript_include("public/backstage/plugins/jQueryUI/jquery-ui.min.js")}}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
{{javascript_include("public/backstage/bootstrap/js/bootstrap.min.js")}}
<!-- nprogress -->
{{javascript_include("public/backstage/plugins/nprogress/nprogress.js")}}
<!-- Morris.js charts -->
{{javascript_include("public/backstage/plugins/raphael/raphael-min.js")}}
{{javascript_include("public/backstage/plugins/morris/morris.min.js")}}
<!-- Sparkline -->
{{javascript_include("public/backstage/plugins/sparkline/jquery.sparkline.min.js")}}
<!-- jvectormap -->
{{javascript_include("public/backstage/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js")}}
{{javascript_include("public/backstage/plugins/jvectormap/jquery-jvectormap-world-mill-en.js")}}
<!-- jQuery Knob Chart -->
{{javascript_include("public/backstage/plugins/knob/jquery.knob.js")}}
<!-- daterangepicker -->
{{javascript_include("public/backstage/plugins/moment/moment.min.js")}}
{{javascript_include("public/backstage/plugins/daterangepicker/daterangepicker.js")}}

<!-- bootstrap time picker -->
{{javascript_include("public/backstage/plugins/timepicker/bootstrap-timepicker.min.js")}}
<!-- datepicker -->

{{javascript_include("public/backstage/plugins/datetimepicker/bootstrap-datetimepicker.min.js")}}
{{javascript_include("public/backstage/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js")}}

{{javascript_include("public/backstage/plugins/codemirror/codemirror.js")}}
{{javascript_include("public/backstage/plugins/codemirror/javascript.js")}}
<!-- Bootstrap WYSIHTML5 -->
{{javascript_include("public/backstage/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js")}}
<!-- Bootstrap TOASTR -->
{{javascript_include("public/backstage/plugins/bootstrap-toastr/toastr.min.js")}}
<!-- Slimscroll -->
{{javascript_include("public/backstage/plugins/slimScroll/jquery.slimscroll.min.js")}}
<!-- FastClick -->
{{javascript_include("public/backstage/plugins/fastclick/fastclick.js")}}
<!-- iCheck 1.0.1 -->
{{javascript_include("public/backstage/plugins/iCheck/icheck.min.js")}}
<!-- AdminLTE App -->
{{javascript_include("public/backstage/dist/js/app.min.js")}}
{{javascript_include("public/backstage/jquery.fileupload.js")}}
{{javascript_include("public/backstage/icheck.js")}}
<script>
(function(window){
    var content = $('#J_backstage_content'),
        lastState = '';
    window.FirstModal = $('#J_modal');
    toastr.options = {
      "positionClass": "toast-top-center",
    };
    NProgress.configure({parent:'#J_backstage_content'});
    /**
     * 处理ajax 消息
     */
    var jsonCommond = function(response) {

        re = typeof(response)=='object'?response:$.parseJSON(response);
        re = response;
        if (!re) {
            return toastr.error('操作失败!' + response, '异常');
        }
        if (re.status == 'success') {
            toastr.success(re.msg, '成功');
            if (re.redirect) {
                loadPage(re.redirect);
            }
            return;
        }
        if (re.status == 'error') {
            toastr.error(re.msg, '错误');
        }
    };
     window.loadPage = function(url ,keep_history){

         content.load(url,function(){
             $('input[type=checkbox], input[type=radio]').iCheck({
                 checkboxClass: 'icheckbox_minimal-red',
                 radioClass: 'iradio_minimal-red'
             });
         });
        if(keep_history!=false && history.pushState != 'undefined'){

            history[lastState == url ? 'replaceState' : 'pushState']({
                        go:url
                    },
                    '', url);
            lastState = url;

        }
    }
    window.onpopstate = function(event) {
        if (!event.state) return;
        var params = event.state.go;
        loadPage(params ,false);
    };
    $(document).on('click', 'a:not(a[target="_blank"],a[target="_command"],a[href^="javascript:"],a[data-toggle],a[data-target],a[data-event],a[data-finder-batch])', function(e) {
        e.preventDefault();
        if ($(this).attr('data-confirm') && !confirm($(this).attr('data-confirm'))) {
            return false;
        }
        var $el = $(this);
        var url = $el.prop('href');
        var target = $el.prop('target');

        loadPage(url);
    }).on('click','a[target="_command"]',function(e){
        e.preventDefault();
        e.stopPropagation();
        var $el = $(this);
        $.get($el.attr('href'),function(res){
            if(res.status=='success'){
                toastr.success(res.msg,'成功');
                loadPage(location.href);
            }else{
                toastr.error(res.msg, '异常');
            }
        },'json');
    }).on('submit','form:not(form[enctype="multipart/form-data"])',function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url:form.prop('action'),
            type:form.prop('method'),
            data:form.serialize(),
            beforeSend: function(xhr) {
                if (form.data('ajax:beforeSend') && typeof(form.data('ajax:beforeSend')) == 'function') {
                    form.data('ajax:beforeSend')(xhr);
                }
            },
            success: function(responseText) {
                jsonCommond(responseText);
                if (form.data('ajax:success') && typeof(form.data('ajax:success')) == 'function') {
                    form.data('ajax:success')(responseText);
                }
            },
            complete: function(xhr) {
                if (form.data('ajax:complete') && typeof(form.data('ajax:complete')) == 'function') {
                    form.data('ajax:complete')(xhr);
                }
            },
            error: function(xhr) {
                toastr.warning(xhr, '异常');
                if (form.data('ajax:error') && typeof(form.data('ajax:error')) == 'function') {
                    form.data('ajax:error')(xhr);
                }
            }
        });
    }).on('ajaxStart',function(){
        NProgress.start();
    }).on('ajaxComplete',function(){
        NProgress.done();
    });
    //全选
    $('body').on('ifChecked','thead :checkbox',function(e){
        var box = $(this).closest('.finder').find('tbody tr :checkbox');
        box.iCheck('check');
    }).on('ifUnchecked','thead :checkbox',function(e){
        var box = $(this).closest('.finder').find('tbody tr :checkbox');
        box.iCheck('uncheck');

    });

     $('body').on('shown.bs.modal','.modal',function(){
         var _this= $(this);
         _this.find('form').data('ajax:success',function(res){
             _this.modal('hide');
         });
     });


     window.newUrl = function(url ,name,value) {
        var newUrl="";
        var reg = new RegExp("([\?&])"+ name +"=([^&]*)");
        var tmp = name + "=" + value;
        if(url.match(reg) != null) {
            newUrl= url.replace(eval(reg),'$1'+tmp);
        }
        else {
            if(url.match("[\?]")) {
                newUrl= url + "&" + tmp;
            } else {
                newUrl= url + "?" + tmp;
            }
        }
        return newUrl;
    }
})(window);
</script>

</body>
</html>
