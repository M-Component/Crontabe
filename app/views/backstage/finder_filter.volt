<section class="filter-content">
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs control-sidebar-tabs">
        <li class="active"><a href="javascript:;" class="control-sidebar-heading pull-left">请设置筛选条件</a></li>
        <li class="active pull-right close-sidebar"><a href="javascript:;" style="cursor: pointer;"><i class="fa fa-close"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div>
            <form method="post" action="{{page.first_url}}">
                {% set datetime_format=['datetime','create_time'] %}
                {%for k,v in filter_columns%}
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        {{v['label']}}
                    </label>
                    {%if v['type'] is not scalar %}
                    <select class="form-control" name="{{v['name']}}">
                        <option></option>
                        {%for type_k ,type_v in v['type']%}
                        <option value="{{type_k}}" {%if filter[v['name']] is defined AND type_k==filter[v['name']]%}selected{%endif%} >{{type_v}}</option>
                        {%endfor%}
                    </select>

                    {%elseif v['type'] in datetime_format%}
                    <div class="input-group datetime">
                        <input type="text" name="{{v['name']~'|bthan'}}" class="form-control input-datetimepicker" value="{{filter[v['name']~'|bthan']}}">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    <div class="input-group datetime">
                        <input type="text" name="{{v['name']~'|lthan'}}" class="form-control input-datetimepicker" value="{{filter[v['name']~'|lthan']}}">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>

                    {%elseif v['type'] =='date'%}
                    <div class="input-group date">
                        <input type="text" name="{{v['name']~'|bthan'}}" class="form-control input-datetimepicker" value="{{filter[v['name']~'|bthan']}}">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                    <div class="input-group date">
                        <input type="text" name="{{v['name']~'|lthan'}}" class="form-control input-datetimepicker" value="{{filter[v['name']~'|lthan']}}">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>

                    {%elseif v['type'] =='time'%}
                    <div class="input-group time">
                        <input type="text" name="{{v['name']~'|bthan'}}" class="form-control input-datetimepicker" value="{{filter[v['name']~'|bthan']}}">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    <div class="input-group time">
                        <input type="text" name="{{v['name']~'|lthan'}}" class="form-control input-datetimepicker" value="{{filter[v['name']~'|bthan']}}">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    {%else%}

                    <input type="text" class="form-control" name="{{v['name']}}" value="{{filter[v['name']]}}">
                    {%endif%}
                </div>
                <!-- /.form-group -->
                {%endfor%}

                <div class="form-group">
                    <button class="btn btn-primary btn-flat col-xs-12">提交</button>
                </div>

            </form>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</section>
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


<script>
    $(function(){
        //筛选
        var filter_content = $('.filter-content');
        var filter_sidebar = filter_content.find('.control-sidebar');
        var filter_btn = $(".filter-btn");
        filter_btn.on('click' , function(e){
            filter_sidebar.addClass('control-sidebar-open');
            $(document).one("click", function(){
                filter_sidebar.removeClass('control-sidebar-open');
            });
            e.stopPropagation();
        });
        filter_sidebar.on("click", function(e){
            e.stopPropagation();
        });

        $('.close-sidebar').on("click", function(){
            filter_sidebar.removeClass('control-sidebar-open');
        });

        var bg = filter_content.find(".control-sidebar-bg");
        var filter_form = filter_content.find('form');
        filter_form.on('submit' ,function(e){
            e.preventDefault();
            e.stopPropagation();
            var finder_filter_data ={};
            var finder_filter_form = $(this).serializeArray();
            $.each(finder_filter_form, function() {
                if(this.value ==='' || this.value===null){
                    return;
                }
                if (finder_filter_data[this.name] !== undefined) {
                    if (!finder_filter_data[this.name].push) {
                        finder_filter_data[this.name] = [finder_filter_data[this.name]];
                    }
                    finder_filter_data[this.name].push(this.value || '');
                } else {
                    finder_filter_data[this.name] = this.value || '';
                }
            });
            if($.isEmptyObject(finder_filter_data)){
                finder_filter_data ='{}';
            }else{
                finder_filter_data = JSON.stringify(finder_filter_data);
            };
            finder_filter_data = encodeURIComponent(finder_filter_data);
            loadPage(newUrl($(this).attr('action'), 'filter',finder_filter_data));
        });

    })
</script>
