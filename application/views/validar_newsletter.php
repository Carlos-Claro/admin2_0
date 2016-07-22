<?php if(isset($valida) && $valida) : ?>
<!DOCTYPE html>
<html lang="pt-br">
   <head>
       <meta charset="utf-8"/>
       <title>Validação Newsletter</title>
       <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
       <style type="text/css">
           .valida-nw{
               margin: 5% 35%;
               border: 1px solid #4DB849;
               padding: 10px;
               border-radius: 5px;
           }
       </style>
   </head>
   <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 valida-nw">
                    <fieldset>
                        <legend> 
                            <?php if(isset($loguinho) && $loguinho): ?>
                            <img src="http://www.guiasjp.com/paginas/<?php echo $loguinho; ?>" />
                            <?php endif; ?>
                            <span style="margin-left: 65px;">Sucesso!</span>
                        </legend>
                        <p class="text-center">Seu cadastro foi efetivado, Obrigado!</p>
                    </fieldset>
                </div>
            </div>
        </div>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
   </body>
</html>
<?php endif; ?>