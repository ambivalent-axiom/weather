<?php
    $key ="f295d63c509ba3e52f1793dd772ab19c";
    $city = readline("Enter the city: ");
    $country = readline("Enter the country:");

    $url = "https://api.openweathermap.org/data/2.5/weather?q=" .
        urlencode($city) . "," .
        urlencode($country) .
        "&units=metric&appid=$key";

    function getWeather(string $url): string
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        if( ! $result = curl_exec($request))
        {
            trigger_error(curl_error($request));
        }
        curl_close($request);
        return $result;
    }
    function getWindDirection(int $degree): string
    {
        $directions = ['N', 'NE', 'E', 'SE', 'S' , 'SW', 'W', 'NW', 'N'];
        for ($i = 0, $d=22.5; $i < count($directions); $i++, $d+=45) {
            if($d - $degree > 0) {
                return $directions[$i] . "\n";
            }
        }
    }
    function printWeather($result): void
    {
        echo $result->name . " " . $result->sys->country . "\n";
        echo $result->weather[0]->main . "\n";
        echo "Current temp: " . $result->main->temp . "°C" .
            " Feels like: " . $result->main->feels_like . "°C" . "\n";
        echo "Wind speed: " . $result->wind->speed . "m/s" . "\n";
        echo "Wind direction: " . getWindDirection($result->wind->deg) . "\n";
        echo "Visibility: " . $result->visibility . "m" . "\n";
        echo "Pressure: " . $result->main->pressure . "hPa" . "\n";
        echo "Humidity: " . $result->main->humidity . "%" . "\n";
    }

    $result = json_decode(getWeather($url));

    if(isset($result->message)) {
        echo $result->message . "\n";
    } else {
        printWeather($result);
    }

