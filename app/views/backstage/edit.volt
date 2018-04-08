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
