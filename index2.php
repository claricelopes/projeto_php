<?php
$cidade = $_REQUEST['cidade'];
$uf = $_REQUEST['estado'];

class OpenWeatherMap {
    const BASE_URL = 'https://api.openweathermap.org';
    private $apiKey;

    public function __construct($apiKey){
        $this->apiKey = $apiKey;
    }

    public function consultarClimaAtual($cidade, $uf){
        return $this->get('/data/2.5/weather', [
            'q' => $cidade . ',BR-' . $uf . ',BRA'
        ]);
    }

    public function consultarPrevisaoTempo($cidade, $uf){
        return $this->get('/data/2.5/forecast', [
            'q' => $cidade . ',BR-' . $uf . ',BRA'
        ]);
    }

    private function get($resource, $params = []){
        $params['units'] = 'metric';
        $params['lang'] = 'pt_br';
        $params['appid'] = $this->apiKey;

        $endpoint = OpenWeatherMap::BASE_URL . $resource . '?' . http_build_query($params);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}

$obOpenWeatherMap = new OpenWeatherMap('741c04753d5faef62af8760a5eb05dd4');

$dadosClima = $obOpenWeatherMap->consultarClimaAtual($cidade, $uf);
$dadosPrevisao = $obOpenWeatherMap->consultarPrevisaoTempo($cidade, $uf);

$temperaturaAtual = intval($dadosClima['main']['temp'] ?? 'Desconhecido');
$temperaturaProximoDia = intval($dadosPrevisao['list'][7]['main']['temp'] ?? 'Desconhecido');
$temperaturaSegundoDia = intval($dadosPrevisao['list'][15]['main']['temp'] ?? 'Desconhecido');
$temperaturaTerceiroDia = intval($dadosPrevisao['list'][23]['main']['temp'] ?? 'Desconhecido');
$temperaturaQuartoDia = intval($dadosPrevisao['list'][31]['main']['temp'] ?? 'Desconhecido');

$descricaoClimaAtual = $dadosClima['weather'][0]['description'] ?? 'Desconhecido';
$descricaoProximoDia = $dadosPrevisao['list'][7]['weather'][0]['description'] ?? 'Desconhecido';
$descricaoSegundoDia = $dadosPrevisao['list'][15]['weather'][0]['description'] ?? 'Desconhecido';
$descricaoTerceiroDia = $dadosPrevisao['list'][23]['weather'][0]['description'] ?? 'Desconhecido';
$descricaoQuartoDia = $dadosPrevisao['list'][31]['weather'][0]['description'] ?? 'Desconhecido';


echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Previsão do Tempo</title>
    <link rel='stylesheet' href='style2.css' />
</head>
<body>
    <div class='container '>
        <div class='form'>
            <h1>Dia 1</h1> <br>
            <p id='temperatura'> $temperaturaAtual °C</p> <br>
            <p id='clima'>$descricaoClimaAtual</p>
            <div class='form-input-container'>
            </div>
        </div>
    </div>

    <div class='container' id='container2'>
        <div class='form'>
            <h1>Dia 2</h1> <br>
            <p id='temperatura'> $temperaturaProximoDia °C</p> <br>
            <p id='clima'>$descricaoProximoDia</p>
            <div class='form-input-container'>
            </div>
        </div>
    </div>

    <div class='container' id='container2'>
        <div class='form'>
            <h1>Dia 3</h1> <br>
            <p id='temperatura'> $temperaturaSegundoDia °C</p> <br>
            <p id='clima'>$descricaoSegundoDia</p>
            <div class='form-input-container'>
            </div>
        </div>
    </div>

    <div class='container' id='container2'>
        <div class='form'>
            <h1>Dia 4</h1> <br>
            <p id='temperatura'> $temperaturaTerceiroDia °C</p><br>
            <p id='clima'>$descricaoTerceiroDia</p>
            <div class='form-input-container'>
            </div>
        </div>
    </div>

    <div class='container' id='container2'>
        <div class='form'>
            <h1>Dia 5</h1> <br>
            <p id='temperatura'> $temperaturaQuartoDia °C</p> <br>
            <p id='clima'>$descricaoQuartoDia</p>
            <div class='form-input-container'>
            </div>
        </div>
    </div>

    <footer><a href='teste.html'> <button id='search' > voltar</button></a></footer>
</body>
</html>";
?>