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

                    {% if acl is not empty %}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">权限资源</label>

                        <div class="col-sm-8">
                            {% for acl_key,acl_val in acl %}
                            <hr>
                            <h5>
                                <i class="fa caption-icon {{ acl_val.icon }}"></i> {{ acl_val.title }}
                            </h5>

                            <div class="row">
                                {% for key_,val_ in acl_val %}
                                {% if val_.title %}
                                <div class="col-md-4 col-xs-12">
                                    <label class="radio-inline">
                                        <input type="checkbox" value="{{ key_ }}" name="resources[]" {%if (model_data_resources is not empty) and (key_ in model_data_resources) %} checked {%endif%}> &nbsp;{{ val_.title }}
                                    </label>
                                </div>
                                {%endif%}
                                {%endfor%}

                            </div>
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
