jQuery(document).ready(function ($) {
    //Подключение таймера к аяксовым виджетам погоды
    $('.widget_weather_ajax').each(function(index, widget){
        getWeather(widget);
    });

    // Функция подключения таймера
    function getWeather( widget ){
        var $widget = $(widget);
        var $content = $widget.find('.content');
        var config = $widget.data('config');
        var {point, ajaxTimeout} = config;

        if(typeof point['lat'] == "undefined" || typeof point['lng'] == "undefined"){
            console.log('Данные точки заданы неправильно: ' + point);
            return;
        }

        reloadWeather( $content, config );
        if(ajaxTimeout){
            setInterval(
                function(){ 
                    reloadWeather( $content, config );
                },
                ajaxTimeout*1000
            );
        }

    }

    // Функция запроса данных через апи сайта
    function reloadWeather( $content, config ){
        var {point} = config;

        // Отправка запроса через сайт
        // Виджет получает данные через апи  сайта, за которым скрыто обращение к произвольному сервису
        $.ajax({
            url: '/api/weather/get',
            method: 'GET',
            dataType: 'JSON',
            data: {
                lat: point['lat'],
                lng: point['lng']
            },
            success: function(data){
                if(data.status == -1){
                    //
                }else{
                    if(data.status == 1){
                        $content.html(data.data.temp);
                    }else{
                        $content.html('Err');
                    }
                }
            },
            error: function(data){

            }
        });

    }
});
