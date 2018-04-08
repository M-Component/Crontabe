{%if image_id is defined%}
<div id="J_result_{{name}}_box">
    <img src="{{img_url}}" width={{width}} height={{height}}>
</div>
<input type="hidden" name="{{name}}" id="J_upload_{{name}}_value" value="{{image_id}}"/>
{%else%}
<div id="J_result_{{name}}_box"></div>
<input type="hidden" name="{{name}}" id="J_upload_{{name}}_value"/>
{%endif%}
<input type="file" id="J_upload_{{name}}" data-url="{{link_url('backstage/upload_image')}}" name="{{name}}"/>
<script>
 $('#J_upload_{{name}}').fileupload({
     'dataType':'json',
     done:function(e,res){
         var data =res.result;
         var file =data.data['{{name}}'];
         var md5 = file['md5'];
         var imgUrl = file['url'];
         $('#J_upload_{{name}}_value').val(md5);
         $('#J_result_{{name}}_box').html('<img src=\"'+imgUrl+'\" width={{width}} height={{height}}>');

         var callback_func = '{{callback_func}}';
         if (callback_func && callback_func in window) {
             window[callback_func](e,res, "{{name}}");
         }
     }
 });
</script>
