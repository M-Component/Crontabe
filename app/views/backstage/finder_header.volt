{%if allow_action%}
{%if object_select_model is defined%}
<div class="box-header col-md-2">
    {{input_object(['model':object_select_model,'multiple':true,'base_filter':object_select_filter,'callback_func':'select_'~object_select_model])}}
</div>
{%endif%}
<div class="box-header col-xs-12  {%if object_select_model %}col-md-6{%else%}col-md-8{%endif%}">
    <div class="finder-actions">
        {% if list_action['use_add']%}
        {% if list_action['use_add'] !==true%}
        {{link_to(list_action['use_add'],'新增','class':'btn btn-primary btn-flat')}}
        {% else%}
        {{link_to(controller~'/add','新增','class':'btn btn-primary btn-flat')}}
        {%endif%}
        {%endif%}
        {% if list_action['use_delete']%}
        {% if list_action['use_delete'] !==true%}
        {{link_to(list_action['use_delete'],'删除','class':'btn btn-danger btn-flat','data-finder-batch':'true' ,'data-confirm':'您确定要删除这些数据？')}}
        {% else%}
        {{link_to(controller~'/delete','删除','class':'btn btn-danger btn-flat','data-finder-batch':'true' ,'data-confirm':'您确定要删除这些数据？')}}
        {%endif%}
        {%endif%}

        {% if group_action%}
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="search-label">{{group_action['title']}}</span>
                <span class="fa fa-caret-down"></span>
            </button>
            <ul class="dropdown-menu">
                {% for k,v in group_action['actions']%}
                <li><a href="{{v['href']}}" data-event="batch-submit">{{v['title']}}</a></li>
                {%endfor%}
            </ul>
        </div>
        {%endif%}


        {% if list_action['custom_actions']%}
        {% for action in list_action['custom_actions'] %}
        {{link_to(action['href'],action['title'],'class':'btn btn-primary btn-flat','data-finder-batch':'true')}}
        {% endfor %}
        {%endif%}


        {% if filter_columns is not empty %}
        <button class="btn btn-primary btn-flat filter-btn">筛选</button>
        {% endif%}
    </div>
</div>
{%endif%}

<div class="box-header {%if allow_action%}col-xs-12 col-md-4 pull-right{%else%}col-xs-12{%endif%}">
    {%if search is not empty%}
    <div class="finder-search">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="search-label">{{current_search['label']}}</span>
                    <span class="fa fa-caret-down"></span>
                </button>
                <ul class="dropdown-menu">
                    {% for k,v in search%}
                    <li><a href="javascript:;" data-key="{{v['name']}}">{{v['label']}}</a></li>
                    {%endfor%}
                </ul>
            </div>
            <!-- /btn-group -->
            <input type="text" class="form-control finder-search-content" name="{{current_search['name']}}" value="{{current_search['value']}}">
            <Span class="input-group-btn finder-btn-search">
                <button type="button" class="btn btn-flat btn-default">
                    <span class="fa fa-search"></span>
                </button>
            </span>
        </div>
    </div>
    {%endif%}
</div>
