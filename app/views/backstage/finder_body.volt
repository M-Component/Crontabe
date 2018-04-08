<div class="box-body col-xs-12 table-responsive" >
    <table class="table table-bordered table-striped" data-first="{{page.first_url}}">
        <thead>
            <tr>
                <th>
                    {%if multiple%}
                    <input type="checkbox"/>
                    {%else%}
                    <input type="radio"/>
                    {%endif%}
                </th>
                {% if show_finder_detail%}
                <th>查看</th>
                {%endif%}
                {% if extend_columns%}
                {% for column in extend_columns %}
                <th>{{column['label']}}</th>
                {%endfor%}
                {% endif %}
                {% for column in columns%}
                {% if column['hide']==false %}
                <th>{{column['name']}}</th>
                {% endif %}
                {% endfor %}
            </tr>
        </thead>
        <tbody>
            {% set datetime_format = ['datetime' ,'create_time' ,'update_time'] %}
            {% for item in page.items %}
            <tr item-id="{{item['id']}}">
                <td>
                    {%if multiple%}
                    <input type="checkbox" name="id[]" value="{{item['id']}}"/>
                    {%else%}
                    <input type="radio" name="id" value="{{item['id']}}"/>
                    {%endif%}
                </td>

                {% if show_finder_detail%}
                <td class="text-center" style="cursor:pointer;">
                    <i class="fa text-default fa-plus-square font-grey-gallery" data-detail="{{link_url(controller~'/finder_detail/'~item['id'])}}"></i>
                </td>
                {%endif%}

                {% if extend_columns%}
                {% for column in extend_columns %}
                <td>{{column['value'][item['id']]}}</td>
                {%endfor%}
                {% endif %}
                {% for index, column in columns%}
                {% if column['hide']==false %}
                <td data-key="{{index}}">
                    {% if column['type']=='time'%}
                    {{date('H:i:s',item[index])}}
                    {% elseif column['type']=='date'%}
                    {{date('Y-m-d',item[index])}}
                    {% elseif in_array(column['type'] ,datetime_format)%}
                    {{date('Y-m-d H:i:s',item[index])}}
                    {% elseif column['type']|is_array %}
                    {{column['type'][item[index]]}}
                    {%else%}
                    {{item[index]}}
                    {%endif%}
                </td>
                {% endif %}
                {% endfor %}
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
<div class="box-footer">
    <div class="pull-left col-md-6 col-xs-12">
        共{{page.total_items}}条数据,共{{page.total_pages}}页，当前第{{page.current}}页,
        <label>每页
            <select class="finder-limit">
                {%set page_limit_opt=['1','10','20','50','100']%}

                {%for i in page_limit_opt%}
                <option value="{{i}}" {%if i==limit%}selected{%endif%}>{{i}}</option>
                {%endfor%}
            </select>
            条</label>

    </div>
    <div class="col-md-6 col-xs-12">
        <ul class="pagination pagination-sm no-margin pull-right">
            <li><a href="{{page.first_url}}">第一页</a></li>
            <li><a href="{{page.before_url}}">«</a></li>
            <li  class='disabled'><input type="number" class="input-current" data-max={{page.total_pages}} data-current={{page.current}} value="{{page.current}}" /></li>
            <li><a href="{{page.next_url}}">»</a></li>
            <li><a href="{{page.last_url}}">最后一页</a></li>
        </ul>
        <div>
        </div>
    </div>
</div>
