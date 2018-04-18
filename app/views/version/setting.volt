
<section class="content-header">
    <h1>
        Robot配置
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i>首页</a></li>
        <li><a href="{{link_url('setting/index')}}">配置</a></li>
        <li class="active">Version配置</li>
    </ol>
</section>
<section class="content">
    <form method="post" action="{{link_url('version/setting')}}" id="conf_form" class="form">
        <div class="row">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-8">
                        <div class="form-horizontal">
                            <div class="form-body">

                                <div class="form-group">
                                    <label class="col-md-4 control-label">App 版本</label>
                                    <div class="col-md-8">
                                        <input type="text" class="input form-control" name="setting[app_version]" value="{{setting['app_version']}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Html5 版本</label>
                                    <div class="col-md-8">
                                        <input type="text" class="input form-control" name="setting[html5_version]" value="{{setting['html5_version']}}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-md-offset-3">
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </div>
            </div>

        </div>

    </form>
</section>
