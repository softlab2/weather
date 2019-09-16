<?php

    if(!function_exists('weather')){
        function weather() {
            return Weather::getInstance();
        }
    }