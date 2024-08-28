<?php
$config = require 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика посещений</title>
</head>
<body>
<h1>Добро пожаловать!</h1>

<button onclick="window.location.href='login.php'">Перейти к статистике</button>

<script>
    var ipstackAccessKey = '<?= $config['ipstack_access_key'] ?>';

    function getVisitorData() {
        var xhrIp = new XMLHttpRequest();
        xhrIp.open('GET', 'https://api.ipify.org?format=json', true);
        xhrIp.onreadystatechange = function() {
            if (xhrIp.readyState === 4 && xhrIp.status === 200) {
                var ipData = JSON.parse(xhrIp.responseText);
                var ip = ipData.ip;

                var xhrGeo = new XMLHttpRequest();
                xhrGeo.open('GET', 'https://api.ipstack.com/' + ip + '?access_key=' + ipstackAccessKey, true);
                xhrGeo.onreadystatechange = function() {
                    if (xhrGeo.readyState === 4 && xhrGeo.status === 200) {
                        var geoData = JSON.parse(xhrGeo.responseText);
                        var city = geoData.city;

                        var device = navigator.userAgent;

                        var xhrSend = new XMLHttpRequest();
                        xhrSend.open('POST', '/save_visit.php', true);
                        xhrSend.setRequestHeader('Content-Type', 'application/json');
                        xhrSend.onreadystatechange = function() {
                            if (xhrSend.readyState === 4 && xhrSend.status === 200) {
                                console.log('Данные успешно отправлены на сервер');
                            }
                        };
                        var visitorData = {
                            ip: ip,
                            city: city,
                            device: device
                        };
                        xhrSend.send(JSON.stringify(visitorData));
                    }
                };
                xhrGeo.send();
            }
        };
        xhrIp.send();
    }

    getVisitorData();
</script>
</body>
</html>
