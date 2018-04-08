<div class="input-group" id="{{dom_id}}_object_select">
    <div class="input-icon right">
        <i class="fa fa-times-circle text-success {%if textvalue is not defined%} hide{%endif%}"></i>
        {%if textvalue is defined%}
        <input class="os-label form-control" value="{{textvalue}}" type="text" data-textcol="{{textcol}}">
        {%else%}
        <input class="os-label form-control" placeholder="{{placeholder|default('请选择...')}}" type="text" data-textcol="{{textcol}}">
        {%endif%}
    </div>
    {%if multiple == false and name%}
    <input class="os-input" type="hidden" name="{{name}}" value="{{value}}">
    {%endif%}
    <span class="input-group-btn">
        <button type="button" class="btn btn-default os-handle">
            <i class="fa  fa-list-alt"></i>
        </button>
    </span>

</div>
<div class="modal fade" id="{{dom_id}}_object_select_modal" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">关闭</button>
                <h3 class="modal-title">请选择</h3>
            </div>
            <div class="modal-body">
                loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary btn-submit">确认</button>
            </div>
        </div>
    </div>
</div>
<script charset="utf-8">

 $(function(){
     var os_btn = $('#{{dom_id}}_object_select'),
         os_modal = $('#{{dom_id}}_object_select_modal'),
         os_label = os_btn.find('.os-label'),
         os_modal_body = os_modal.find('.modal-body');

     os_modal_body.on('click', '.finder-body tbody tr', function (e) {
         e.preventDefault();
         e.stopPropagation();
         var box =$(this).find(':checkbox,:radio');
         box.iCheck('toggle');
     });

     os_btn.find('.fa-times-circle').on('click', function (e) {
         if (e)e.stopPropagation();
         os_label.val('');
         os_btn.find('input[type=hidden]').val('');
         $(this).addClass('hide');
     });

     os_modal.on('show.bs.modal', function () {
         os_modal.find('.modal-title').text(os_label.attr('placeholder') || '请选择');
         os_modal.appendTo('body');
     });
     os_modal.on('hidden.bs.modal', function () {
         os_modal.find('.modal-body').empty();
         os_modal.insertAfter(os_btn);
     });

     var remote_url = '{{link_url("backstage/finder/?in_modal=1&"~params)}}';

     os_btn.on('click', function (e) {
         if (e)e.stopPropagation();
         if (os_modal.find('.modal-body .finder-pager').length)
             return os_modal.modal('show');
         os_label.data('last_text', os_label.val()).val('加载中...');
         os_modal.find('.modal-body').load(remote_url, function () {
             $('input[type=checkbox], input[type=radio]').iCheck({
                 checkboxClass: 'icheckbox_minimal-red',
                 radioClass: 'iradio_minimal-red'
             });
             os_label.val(os_label.data('last_text'));
             os_modal.modal('show');
         });

     });


     os_modal.find('.btn-submit').on('click', function (e) {
         var checked = os_modal_body.find('tbody input:checked');
         if (!checked.length) {
             toastr.warning('未选择任何数据项', '提示');
         }

         var callback_func = '{{callback_func}}';
         if (callback_func && callback_func in window) {

             window[callback_func](checked.serializeArray(), os_btn);
         } else {

             if (os_btn.find('input[type=hidden]').length) {
                 var textcol = checked.closest('tr').find('td[data-key="' + os_label.attr('data-textcol') + '"]');
                 if (textcol.length) {
                     os_label.val($.trim(textcol.text()));
                 } else {
                     os_label.val(checked.val());
                 }
                 os_btn.find('input[type=hidden]').val(checked.val());
                 os_btn.find('.fa-times-circle').removeClass('hide');
             }
         }

         var callback_extend ='{{callback_extend}}';
         if (callback_extend && callback_extend in window) {
             window[callback_extend](checked.serializeArray(), os_btn);
         }

         os_modal.modal('hide');
     });

 });
</script>
