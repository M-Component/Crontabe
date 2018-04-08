<section class="content-header">
    <h1>
        短信配置
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i>首页</a></li>
        <li><a href="{{link_url('setting/index')}}">配置</a></li>
        <li class="active">短信配置</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {%for sms in sms_list%}
        <div class="modal fade" id="conf_setting_{{sms['sms_name']}}" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box {%if sms['status']=='true'%}box-primary{%else%}box-danger{%endif%}">
                <div class="box-header with-border">
                    <h3 class="box-title">{{sms['display_name']}}</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <p>{{sms['sms_name']}}</p>
                    <a href="{{link_url('sms/setting/'~sms['sms_name'])}}" data-target="#conf_setting_{{sms['sms_name']}}" data-toggle="modal" class="btn btn-primary"><i class="fa fa-cog"></i> 配置</a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        {%endfor%}
    </div>
</section>
<add key="ZTSmsNoticeKey" value="mipuhy"/>
<add key="ZTSmsNoticeSecret" value="SUU0pg"/>
<add key="ZTSmsApiServer" value="http://www.api.zthysms.com:8088/" />