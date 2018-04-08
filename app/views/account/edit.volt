<section class="content-header">
    <h1>
        {{ title?title:'编辑' }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">{{ title }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                {{ form(controller~'/save','method':'post','class':'form-horizontal') }}
                <div class="box-body">
                    {{partial("backstage/edit_form")}}
                    {% if role is defined %}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属角色</label>
                        <div class="col-sm-5" style="margin-left: -18px;">

                        {% for role_key,role_value in role %}
                            <label class="radio-inline">
                              <input type="checkbox" value="{{ role_value.id }}" name="role_id[]" {%if (account_role is not empty) and (role_value.id in account_role) %} checked {%endif%}> {{ role_value.name }}
                            </label>
                        {% endfor %}

                        </div>
                    </div>
                    {% endif %}
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        保存
                    </button>
                </div>
                {{ endform() }}
            </div>
        </div>
    </div>
</section>