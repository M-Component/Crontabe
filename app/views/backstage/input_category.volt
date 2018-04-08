<select class="category_{{dom_md5}}">
    <option>请选择</option>
    {%for item in root%}
    <option value="{{item['id']}}" {%if item['selected']%}selected{%endif%}>{{item['name']}}</option>
    {%endfor%}
</select>
{%if tree%}
{%for items in tree %}
<select class="category_{{dom_md5}}">
    <option>请选择</option>
    {%for item in items%}
    <option value="{{item['id']}}" {%if item['selected']%}selected{%endif%}>{{item['name']}}</option>
    {%endfor%}
</select>
{%endfor%}
{%endif%}

{%if cat is defined%}
<input type="hidden" name="{{name}}" value="{{cat.parent_cid}}">
{%else%}
<input type="hidden" name="{{name}}" value="{{parent_id}}">
{%endif%}

<script>
 $(document).on('change','.category_{{dom_md5}}',function(){
     var _this = $(this);
     var current_pos = $('.category_{{dom_md5}}').index(this);
     var select_count = $('.category_{{dom_md5}}').length;

     if(_this.val()==0){
         var pre = _this.prev('.category_{{dom_md5}}');
         if(pre.length>0){
             $('input[name="{{name}}"]').val(pre.val());
         }else{
             $('input[name="{{name}}"]').val(0);
         }
     }else{
         $('input[name="{{name}}"]').val(_this.val());
         $.get('{{url("goods_category/getCategoryChildren")}}',{id:_this.val()} ,function(res){
             var children = res.data;
             if(children.length>0){
                 var _new_select ='<select class="category_{{dom_md5}}"><option value="0">请选择</option>';
                 for(var i in children){
                     _new_select +='<option value="'+children[i].id+'">'+children[i].name+'</option>';
                 }
                 _new_select+='</select>';
                 _this.after(_new_select);
             }
         },'json');
     }

     $('.category_{{dom_md5}}').each(function(index ,element){
         var _select = $(this);
         if(index-current_pos>0){
             _select.remove();
         }
     });
 });
</script>
