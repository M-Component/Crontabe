<div class="row">
    <div id="{{dom_id}}_finder"  data-first="{{page.first_url}}">
        <div class="box finder">
            <div class="finder-header">
                {{partial("backstage/finder_header")}}
            </div>

            <div class="finder-body">
                {{partial("backstage/finder_body")}}
            </div>

        </div>
    </div>
</div>
<script>
 var finder_{{dom_id}} = $('#{{dom_id}}_finder');
 var first_url_{{dom_id}} = finder_{{dom_id}}.attr('data-first');

 finder_{{dom_id}}.data('in_modal' ,"{{in_modal}}");

 finder_{{dom_id}}.on('click','.finder-btn-search',function(e){
     var search_input=finder_{{dom_id}}.find('.finder-search-content'),
         search_btn =finder_{{dom_id}}.find('.finder-btn-search');
     var finder_search_data={};
     if($.trim(search_input.val())){
         finder_search_data[search_input.attr('name')] = search_input.val();
     }
     finder_search_data = JSON.stringify(finder_search_data);
     finder_search_data =encodeURIComponent(finder_search_data);
     var url = newUrl(first_url_{{dom_id}}, 'filter',finder_search_data);
     loadContent_{{dom_id}}(url ,finder_{{dom_id}});

 }).on('click','.finder-search .dropdown-menu a',function(){
     var search_input=finder_{{dom_id}}.find('.finder-search-content'),
         search_btn =finder_{{dom_id}}.find('.finder-btn-search');
     search_input.attr('name' , $(this).attr('data-key'));
     $(this).closest('.finder-search').find('.search-label').text($(this).text());
 }).on('keydown','.finder-search-content',function(e){
     if(e.keyCode == 13){
         var search_input=finder_{{dom_id}}.find('.finder-search-content'),
             search_btn =finder_{{dom_id}}.find('.finder-btn-search');
         search_btn.trigger('click',e);
     }
 }).on('change','.finder-limit',function(){
     var limit = $(this).val();
     var url = newUrl(first_url_{{dom_id}}, 'limit',limit)
     loadContent_{{dom_id}}(url ,finder_{{dom_id}});
 }).on('click','.finder-body a:not(a[target="_blank"],a[href^="javascript:"],a[target="_command"])' ,function(e){
     e.preventDefault();
     e.stopPropagation();
     loadContent_{{dom_id}}($(this).attr('href'),finder_{{dom_id}});
 }).on('blur','.pagination .input-current',function(e){
     var ipt = $(this),page_total = ipt.attr('data-max'),page_current = ipt.attr('data-current');
     ipt.val(parseInt($.trim(ipt.val())));
     if(isNaN(ipt.val())){
         ipt.val(page_current);
     }
     if(ipt.val()-page_total>0){
         ipt.val(page_total);
     }
     if(page_current == ipt.val()){
         return true;
     }
     var url=newUrl(first_url_{{dom_id}},'page' ,ipt.val());
     loadContent_{{dom_id}}(url ,finder_{{dom_id}});
 }).on('keydown','.pagination .input-current',function(e){
     if(e.keyCode == 13){
         $(this).trigger('blur',e);
     }
 }).on('click','a[data-finder-batch]',function(e){
     e.preventDefault();
     e.stopPropagation();
     var $el = $(this),
         box = $el.closest('.finder'),
         checked = box.find('table>tbody input[type=checkbox]:checked');
     if(!checked.length && $el.data('ignore') == 'false'){
         return toastr.warning('请选择要操作的数据', '异常');
     }
     if ($el.attr('data-confirm') && !confirm($el.attr('data-confirm'))) {
         return false;
     }
     var _ids= [];
     $.each(checked,function(i,el){
         _ids.push(el.value);
     });
     $.post($el.attr('href'),{'id':_ids},function(res){
         if(res.status=='success'){
             toastr.success(res.msg,'成功');
             loadContent_{{dom_id}}(location.href ,finder_{{dom_id}});
         }else{
             toastr.error(res.msg, '异常');
         }
     },'json');
 }).on('click','a[data-event="batch-submit"]',function(e){
     e.preventDefault();
     e.stopPropagation();
     var $el = $(this),
         box = $el.closest('.finder'),
         checked = box.find('table>tbody input[type=checkbox]:checked');
     if(!checked.length){
         return toastr.warning('请选择要操作的数据', '异常');
     }

     var _ids= [];
     $.each(checked,function(i,el){
         _ids.push(el.value);
     });
     FirstModal.load($el.attr('href'),{'id':_ids},function(res){
         FirstModal.modal('show');
     });
 });

// 局部刷新页面
 var loadContent_{{dom_id}} =function(url ,finder){
     if(finder.data('in_modal')){
         url = newUrl(url ,'in_page',1);
         finder.find('.finder-body').load(url ,function(){
             first_url_{{dom_id}} = finder.find('.finder-body table').attr('data-first');
             $('input[type=checkbox], input[type=radio]').iCheck({
                 checkboxClass: 'icheckbox_minimal-red',
                 radioClass: 'iradio_minimal-red'
             });
         });
     }else{
         loadPage(url);
     }
 }


 //数据项详情
 var detail_show_log = {};
 finder_{{dom_id}}.on('click','tbody td:has([data-detail])',function(){

     var handle = $(this).find('[data-detail]'),
         tr = $(handle).closest('tr'),
         item_id = tr.attr('item-id'),
         detail_row =tr.next('tr.detail-row');

     if(!detail_row.length){
         tr.addClass('bg-grey-steel');
         detail_row = $('<tr class="detail-row"><td class="row-container" colspan='+tr.find('td').length+'><div class="row-wrap bg-grey-steel"><i class="fa fa-spinner fa-spin"></i></div></td></tr>');
         detail_row.insertAfter(tr);
         $(handle).removeClass('fa-plus-square').addClass('fa-minus-square');
         detail_row.find('.row-wrap').load($(handle).attr('data-detail')).width(finder_{{dom_id}}.width());
         detail_show_log[item_id] = true;
     }else{
         if($(handle).hasClass('fa-minus-square')){
             detail_row.addClass('hidden');
             tr.removeClass('bg-grey-steel');
             detail_show_log[item_id] = false;
             $(handle).removeClass('fa-minus-square').addClass('fa-plus-square');
         }else{
             detail_row.removeClass('hidden');
             tr.addClass('bg-grey-steel');
             detail_show_log[item_id] = true;
             $(handle).removeClass('fa-plus-square').addClass('fa-minus-square');
         }
     }
 });
</script>
