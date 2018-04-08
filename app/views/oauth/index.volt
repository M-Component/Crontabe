<section class="content-header">
    <h1>
        支付方式
    </h1>
</section>
<section class="content">
    <div class="row">
        {%for oauth in oauth_list%}
        <div class="modal fade" id="conf_setting_{{oauth['oauth_name']}}" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box {%if oauth['status']=='true'%}box-primary{%else%}box-danger{%endif%}">
                <div class="box-header with-border">
                    <h3 class="box-title">{{oauth['name']}}</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <p>{{oauth['description']}}</p>
                    <a href="/oauth/setting/{{oauth['oauth_name']}}" data-target="#conf_setting_{{oauth['oauth_name']}}" data-toggle="modal" class="btn btn-primary"><i class="fa fa-cog"></i> 配置</a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        {%endfor%}
    </div>
</section>






