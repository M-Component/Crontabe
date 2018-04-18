<section class="content-header">
    <h1>
        Mailer 配置
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i>首页</a></li>
        <li><a href="{{link_url('setting/index')}}">配置</a></li>
        <li class="active">短信配置</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {%for mailer in mailer_list%}
        <div class="modal fade" id="conf_setting_{{mailer['driver_name']}}" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box {%if mailer['status']=='true'%}box-primary{%else%}box-danger{%endif%}">
                <div class="box-header with-border">
                    <h3 class="box-title">{{mailer['display_name']}}</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <p>{{mailer['driver_name']}}</p>
                    <a href="{{link_url('mailer/setting/'~mailer['driver_name'])}}" data-target="#conf_setting_{{mailer['driver_name']}}" data-toggle="modal" class="btn btn-primary"><i class="fa fa-cog"></i> 配置</a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        {%endfor%}
    </div>
</section>