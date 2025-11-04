<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Moeda</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <form action="" method="POST">
        <h1> Conversor de Moeda</h1>
        <input id="valor" placeholder="Digite seu valor em Real R$:" type="number" name="valorReal" id="valorReal" step="0.01">
        <input type="submit" name="button" id="button" value="Converter">
        <p>Conversor de Réal Brasileiro para Dólar Amêricano</p>
    </form>

    <div id="resultDiv">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Cotação gerada da API do Banco Central do Brasil
            $inicio = date("m-d-Y", strtotime("-7 days"));
            $fim = date("m-d-Y");

            $url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\'' . $inicio . '\'&@dataFinalCotacao=\'' . $fim . '\'&$top=1&$orderby=dataHoraCotacao%20desc&$format=json&$select=cotacaoCompra,dataHoraCotacao';
            $dados = json_decode(file_get_contents($url), true);

            if (isset($dados["value"][0]["cotacaoCompra"])) {
                $cotacao = $dados["value"][0]["cotacaoCompra"];

                $real = floatval($_POST["valorReal"]);

                $dolar = $real / $cotacao;

                $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);

                echo "<h2>Seu valor de " . numfmt_format_currency($padrao , $real , "BRL") ." em dólar é: " . numfmt_format_currency($padrao, $dolar, "USD") . "</h2>";

                echo "<p>Cotação atual: $" . number_format($cotacao, 2, ',', '.') . "</p>";
            } else {
                echo "<p>Não foi possível obter a cotação. Tente novamente mais tarde.</p>";
            }
        }
        ?>

    </div>

</body>
</html>