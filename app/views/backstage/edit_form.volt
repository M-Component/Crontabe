
<input type="hidden" name="_redirect" value="{{_redirect}}"/>
{%if model_data is defined%}
<input type="hidden" name="id" value="{{model_data.readAttribute('id')}}"/>
{%endif%}
{% for index, column in columns%}
{% if column['edit'] === false %}
{% continue %}
{% endif %}
{% if add_filter[index] is defined %}
<input type="hidden" name="{{index}}" value="{{add_filter[index]}}">
{% continue %}
{% endif %}
{% if 'belongsTo' in column['type']%}
{% for relation in belongsTo%}
{%if relation.getFields() == index%}
<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{columns[relation.getFields()]['name']}}</label>
    <div class="col-sm-5">
        {%if model_data is defined%}
        {{input_object(['model':relation.getReferencedModel(),'multiple':false,'name':index ,'value':model_data.readAttribute(index) ,'callback_func':'callback_func_'~index ,'callback_extend':'callback_extend_'~index])}}
        {%else%}
        {{input_object(['model':relation.getReferencedModel(),'multiple':false,'name':index,'callback_func':'callback_func_'~index ,'callback_extend':'callback_extend_'~index])}}
        {%endif%}
    </div>

</div>
{% endif %}
{% endfor%}
{% else %}
<div class="form-group">
    <label for="{{index}}" class="col-sm-2 control-label">{{column['name']}}</label>
    <div class="col-sm-{%if column['type']=='code'%}8{%else%}5{%endif%}">
        {%if column['type'] is not scalar %}
            {%for type_index,type in column['type']%}
                <label>
                    <input type="radio"  value="{{type_index}}" name="{{index}}" {%if model_data is defined%}{%if model_data.readAttribute(index) == type_index%}checked{%endif%}{%endif%}/>
                    {{type}}
                </label>
            &nbsp;&nbsp;
            {%endfor%}
        {%elseif column['type'] == 'image'%}
        {%if model_data is defined%}
            {{input_image(['image_id':model_data.readAttribute(index) ,'name':index ,'callback_func':'input_image_'~index])}}
        {% else%}
            {{input_image(['name':index, 'callback_func':'input_image_'~index])}}
        {% endif%}
        {%elseif column['type'] == 'textarea'%}
        <textarea class="form-control" name="{{index}}" id="{{index}}" rows="2" placeholder="输入{{column['name']}}">{%if model_data is defined%}{{model_data.readAttribute(index)}}{%endif%}</textarea>
        {%elseif column['type'] == 'code'%}
        <textarea class="form-control" name="{{index}}" id="{{index}}">{%if model_data is defined%}{{model_data.readAttribute(index)}}{%endif%}</textarea>
        <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("{{index}}"), {
            lineNumbers: true,     // 显示行数
            indentUnit: 4,         // 缩进单位为4
            matchBrackets: true,   // 括号匹配
            lineWrapping: true,    // 自动换行
            styleActiveLine: true,
            mode: "application/ld+json",
        });
        editor.setOption("extraKeys", {
            // Tab键换成4个空格
            Tab: function(cm) {
                var spaces = Array(cm.getOption("indentUnit") + 1).join(" ");
                cm.replaceSelection(spaces);
            }
        });
        editor.setSize('100%',500);
        </script>
        {%elseif column['type'] == 'datetime'%}
            <div class="input-group datetime">
                <input type="text" name="{{index}}" id="{{index}}" value="{%if model_data is defined%}{{date('Y-m-d H:i:s',model_data.readAttribute(index))}}{%endif%}"  class="form-control input-datetimepicker">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        {%elseif column['type'] == 'date'%}
            <div class="input-group date">
                <input type="text" name="{{index}}" id="{{index}}" value="{%if model_data is defined%}{{date('Y-m-d',model_data.readAttribute(index))}}{%endif%}" class="form-control input-datepicker">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
            </div>
        {%elseif column['type'] == 'time'%}
            <!-- time Picker -->
            <div class="bootstrap-timepicker">
                <div class="input-group">
                    <input type="text" name="{{index}}" id="{{index}}" value="{%if model_data is defined%}{{date('H:i;s',model_data.readAttribute(index))}}{%endif%}" class="form-control input-timepicker">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                </div>
                <!-- /.input group -->
            </div>
        {%elseif column['type'] == 'password'%}
            <input type="password" class="form-control" name="{{index}}" id="{{index}}" >
        {%else%}
        <!--取消了 (model_data.readAttribute(index) 字段如果有值，还是可以编辑) 的功能-->
       {# <input type="{{column['type']}}" {%if model_data is defined AND model_data.readAttribute(index) AND column['update']===false%}readonly{%endif%} class="form-control" name="{{index}}" id="{{index}}" placeholder="输入{{column['name']}}" value="{%if model_data is defined%}{{model_data.readAttribute(index)}}{%endif%}">#}
        <input type="{{column['type']}}" {%if model_data is defined AND column['update']===false%}readonly{%endif%} class="form-control" name="{{index}}" id="{{index}}" placeholder="输入{{column['name']}}" value="{%if model_data is defined%}{{model_data.readAttribute(index)}}{%endif%}">
        {%endif%}
        <span class="help-inline">{{column['desc']}}</span>
    </div>
</div>
{% endif%}
{% endfor%}

<script>
 //Timepicker
 $(".input-timepicker").timepicker({
     showInputs: false,
     showSeconds:true,
     minuteStep:1,
     showMeridian:false
 });
 //Datetime picker
 $('.input-datetimepicker').datetimepicker({
     autoclose:true,
     format: 'yyyy-mm-dd hh:ii',
     language:'zh-CN',
     todayHighlight:true,
     startView: 2,
     forceParse: 0,
     weekStart: 1,
     todayBtn:  1
 });

 //Date picker
 $('.input-datepicker').datetimepicker({
     autoclose:true,
     format: 'yyyy-mm-dd',
     language:'zh-CN',
     todayHighlight:true,
     startView: 2,
     minView: 2,
     forceParse: 0,
     weekStart: 1,
     todayBtn:  1
 });

</script>
