<!--添加账户邮件发送模版-->
<style>
    .dcg-name,.dcg-name-time{
        line-height: 10px;
        color: #3a3768;
    }
    .dcg-logs{

    }
    .dcg-logs-img{
        background: #3a3768;padding: 10px 20px;
    }
    .dcg-logs-left{
        float: left;
        margin-right: 30px;
    }
</style>
    <!--添加账户邮件发送模版-->
<div style="border: 1px solid #eff5fb;height: 253px;padding: 15px;">
    <div style="height:100px">尊敬的{{ params['email'] }}用户您好,你的初始密码为：<span style="border-bottom: solid 1px #000;color: red;">{{ params['password']}}</span></div>

    <div>
        <p class="dcg-name">洞察官团队</p>
        <p class="dcg-name-time">{{ params['send_time'] }}</p>
        <span style="display: block;unicode-bidi: isolate;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;overflow: hidden;border-width: 1px;border-style: dashed;border-color: #e8e8e8;"> </span>
        <div class="dcg-logs">
            <div class="dcg-logs-left">
                <img class="dcg-logs-img" style="" src="data:image/png;base64,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" alt="">
            </div>
            <div class="dcg-logs-right">
                     <p>洞察官-专业的 络零售竞品监测分析产品</p>
                     <p>官网：<a href="https://www.dongchaguan.cn">www.dongchaguan.cn</a></p>
            </div>
        </div>

    </div>

</div>
