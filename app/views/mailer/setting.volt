<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="modal-title">配置 {{mailer['display_name']}}<small>{{mailer['mailer_name']}}</small></h3>
</div>


<form method="post" action="{{link_url('mailer/setting/'~mailer['mailer_name'])}}" id='conf_form' class="form">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            说明
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="text-overflow">
                            <!-- 说明 -->
                            {{mailer['description']}}
                            <!-- /.说明 -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-horizontal">
                    <div class="form-body">
                        {%for key,input in setting %}
                        <!-- 把键定义为input_name -->
                        {%if input['name']%}
                        {%set input_name=input['name']%}
                        {%else%}
                        {%set input_name="setting["~key~"]"%}
                        {%endif%}

                        {%if input['type'] == 'hidden'%}
                        <input type="hidden" name="{{input_name}}" value="{{mailer['setting'][key]|default(input['default'])}}">
                        {%else%}

                        <div class="form-group">
                            <!-- 定义属性的名称 -->
                            <label class="col-md-4 control-label">{{input['title']}}</label>
                            <div class="col-md-8">
                                {%if input['type'] == 'html' or input['type'] == 'textarea'%}
                                <!-- mailer['setting'][key]是否为空,如果是则为:default(input['default']) -->
                                <textarea class="form-control" rows=2 name="{{input_name}}">{{mailer['setting'][key]|default(input['default'])}}</textarea>

                                    {%elseif input['type'] == 'radio'%}
                                <select class="form-control" name="{{input_name}}" >
                                    {%for val,option in input['options']%}
                                    <option value="{{val}}" {%if val==mailer['setting'][key]|default(input['default']) %}selected{%endif%}>{{option}}</option>
                                    {%endfor%}
                                </select>

                                {%elseif input['type'] == 'select'%}
                                <select class="form-control" name="{{input_name}}" >
                                    {%for val,option in input['options']%}
                                    <option value="{{val}}" {%if val==mailer['setting'][key]|default(input['default']) %}selected{%endif%}>{{option}}</option>
                                    {%endfor%}
                                </select>
                                {%elseif input['type'] == 'password' %}
                                    <input type="password" class="input form-control" name="{{input_name}}"  value="{{mailer['setting'][key]|default(input['default'])}}">

                                {%elseif input['edit'] == 'false' %}
                                    <input type="text" class="input form-control" name="{{input_name}}" disabled value="{{mailer['setting'][key]|default(input['default'])}}">
                                {%else%}
                                    <input type="text" class="input form-control" name="{{input_name}}" value="{{mailer['setting'][key]|default(input['default'])}}">
                                {%endif%}
                            </div>
                        </div>
                        {%endif%}
                        {%endfor%}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn default" data-dismiss="modal">关闭</button>
        <button type="submit" class="btn blue">保存</button>
    </div>

</form>


<script>
    $('form').data('ajax:success' ,function(){
        location.reload();
    });
</script>