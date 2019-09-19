<div class="weather_widget @if($config['ajaxTimeout']) widget_weather_ajax @endif panel panel-default" data-config="{{json_encode($config)}}">
        <div class="panel-heading">
    <h3 class="panel-title">{{$config['name']}}, &nbsp;&#8451;</h3>
    </div>
    <div class="panel-body text-center">
        <h1>
        @if($config['ajaxTimeout'] > 0)
            <span class="content">
                <i class="glyphicon glyphicon-repeat slow-right-spinner"> </i>
            </span> 
        @else
            {{Weather::get($config['point'])->toString()}}
        @endif
        </h1>
    </div>
</div>