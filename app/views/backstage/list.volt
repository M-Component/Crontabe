<section class="content-header">
    <h1>
        {{ title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">{{ title }}</li>
    </ol>
</section>
<section class="content data-finder">
    {{ partial("backstage/finder") }}
</section>

{% if filter_columns is not empty %}
{{ partial("backstage/finder_filter") }}
{%endif%}
