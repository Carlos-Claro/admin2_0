<div class="itens"></div>
<style type="text/css">
    label {
        line-height: 35px;
    }
    .form-group {
        min-height: 100px;
    }
</style>
<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="resultado-email"></div>
    <input type="hidden" name="id" id="id_empresa" class="form-control id" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
    <input type="hidden" name="editavel" id="editavel" class="form-control editavel" value="<?php echo $editavel;?>">
    <div class="alert alert-danger">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>
                        <button type="button" class="btn btn-default pull-right status_servico glyphicon glyphicon-download">
                            Status: <span class="status" data-item="<?php echo isset($item->id) ? $item->id : '';?>"><?php echo isset($item->id) ? 'Editando' : 'Novo';?></span>
                        </button>
                    </h3>
                </div>
                <hr>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide espaco-instrucoes"><!-- hide --> 
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-heading"><strong></strong></li>
                        <li class="list-group-item"></li>
                    </ul>
                </div>
            </div>
        </div>
    <div class="alert alert-<?php echo $item->bloqueado ? 'danger' : 'success' ;?> identificacao">
        
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-6">
                <div class="form-group">
                    <label>Código: <center><?php echo $item->id;?></center></label>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6">
                <div class="form-group">
                    <label>Razão Social: <?php echo $item->empresa_razao_social;?></label><br>
                    <label>Nome Fantasia: <?php echo $item->empresa_nome_fantasia;?></label><br>
                    <label>Subcategoria: <?php echo $item->subcategoria;?></label>
                    
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="form-group">
                    <label>Contrato: <?php echo $item->contrato;?></label>
                    <br>
                    <label>Data Inclusão: <?php echo $item->data;?></label>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <?php 
                    $botao['classe'] = 'bloqueado';
                    $botao['valor'] = set_value('bloqueado', ( isset($item->bloqueado) ? $item->bloqueado : '' ) ) ;
                    $botao['texto']['on'] = 'Bloqueado';
                    $botao['texto']['off'] = 'Liberado';
                    $botao['reverse'] = 1;
                    echo set_botao_editavel($botao);
                    ?>
                <button class="estatistica-dia btn btn-primary" data-item="<?php echo $item->id;?>" type="button">Estatisticas por dia</button>
                <button class="estatistica-local btn btn-primary" data-item="<?php echo $item->id;?>" type="button">Estatisticas por local</button>
                <button class="deleta-mongo btn btn-danger" data-item="<?php echo $item->id;?>" type="button">Deletar imoveis do Mongo</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 menu-empresas">
            <p class="alert alert-warning">
                Menu
            </p>
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="#sobre" aria-controls="sobre" role="tab" data-toggle="tab">Sobre</a></li>
                <li role="presentation" ><a href="#localizacao" aria-controls="contrato" role="tab" data-toggle="tab">Localização</a></li>
                <li role="presentation" ><a href="#contatos" aria-controls="contatos" role="tab" data-toggle="tab">Contatos</a></li>
                <li role="presentation" ><a href="#integracao" aria-controls="integracao" role="tab" data-toggle="tab">Integração</a></li>
                <li role="presentation" ><a href="#servicos" aria-controls="servicos" role="tab" data-toggle="tab">Serviços</a></li>
                <li role="presentation" ><a href="#publicidade" aria-controls="publicidade" role="tab" data-toggle="tab">Publicidade</a></li>
                <li role="presentation" ><a href="#pagina" aria-controls="pagina" role="tab" data-toggle="tab">Pagina</a></li>
                <li role="presentation" ><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
            </ul>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12 corpo-empresas">
            <?php $sequencia = 1;?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active alert alert-warning" id="sobre">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Sobre:</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'contrato',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('contrato', ( isset($item->contrato) ? $item->contrato : '' ) ) ,
                                                    'titulo' => 'Contrato',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'dia_pgto',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('dia_pgto', ( isset($item->dia_pgto) ? $item->dia_pgto : '' ) ) ,
                                                    'titulo' => 'Dia do Pagamento',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'data',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'data_hora_pt_br',
                                                    'valor' => set_value('data', ( isset($item->data) ? $item->data : '' ) ) ,
                                                    'titulo' => 'Data',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="form-group id_categoria">
                                        <label for="id_categoria">Categoria</label>
                                        <?php
                                        $config['valor'] = $categorias; 
                                        $config['nome'] = 'id_categoria'; 
                                        $config['extra'] = 'id="id_categoria" data-sequencia="'.$sequencia.'" data-nao-salva="1"'; 
                                        $config['class'] = 'campo-'.$sequencia.' categorias'; 
                                        echo form_select($config, set_value('id_categoria', (isset($item->id_categoria) ? $item->id_categoria : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="id_categoria help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="form-group id_subcategoria">
                                        <label for="id_subcategoria">SubCategoria</label>
                                        <div class="select_subcategorias">
                                            <?php
                                            $config['valor'] = $subcategorias; 
                                            $config['nome'] = 'id_subcategoria'; 
                                            $config['extra'] = 'id="id_subcategoria" data-sequencia="'.$sequencia.'"'; 
                                            $config['class'] = 'campo-'.$sequencia; 
                                            echo form_select($config, set_value('id_subcategoria', (isset($item->id_subcategoria) ? $item->id_subcategoria : '') ) ); 
                                            $sequencia++;
                                            ?>
                                        </div>
                                        
                                        <p class="id_subcategoria help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_razao_social',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_razao_social', ( isset($item->empresa_razao_social) ? $item->empresa_razao_social : '' ) ) ,
                                                    'titulo' => 'Razao Social',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_nome_fantasia',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_nome_fantasia', ( isset($item->empresa_nome_fantasia) ? $item->empresa_nome_fantasia : '' ) ) ,
                                                    'titulo' => 'Nome Fantasia',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_cnpj',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'cnpj',
                                                    'valor' => set_value('empresa_cnpj', ( isset($item->empresa_cnpj) ? $item->empresa_cnpj : '' ) ) ,
                                                    'titulo' => 'CNPJ',
                                                    'complemento' => '<a href="http://www.receita.fazenda.gov.br/PessoaJuridica/CNPJ/cnpjreva/Cnpjreva_Solicitacao.asp" target="_blank">Conferir CNPJ</a>',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'data_abertura',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'data',
                                                    'valor' => set_value('data_abertura', ( isset($item->data_abertura) ? $item->data_abertura : '' ) ) ,
                                                    'titulo' => 'Data de Abertura',
                                                    'complemento' => '',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_telefone',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'telefone_sem_ddd',
                                                    'valor' => set_value('empresa_telefone', ( isset($item->empresa_telefone) ? $item->empresa_telefone : '' ) ) ,
                                                    'titulo' => 'Telefone (sem DDD, apenas numeros)',
                                                    'prefixo' => '('.$item->cidade_ddd.')',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_email',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_email', ( isset($item->empresa_email) ? $item->empresa_email : '' ) ) ,
                                                    'titulo' => 'Email Comercial',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_dominio',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_dominio', ( isset($item->empresa_dominio) ? $item->empresa_dominio : '' ) ) ,
                                                    'titulo' => 'Empresa Dominio',
                                                    'complemento' => ( ( ! empty($item->empresa_dominio) ) ? '<a class="btn btn-info" href="'.$item->empresa_dominio.'" target="_blank">Acessar Site</a>' : '' ), 
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_creci',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_creci', ( isset($item->pagina_creci) ? $item->pagina_creci : '' ) ) ,
                                                    'titulo' => 'Creci',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_funcionarios',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_funcionarios', ( isset($item->empresa_funcionarios) ? $item->empresa_funcionarios : '' ) ) ,
                                                    'titulo' => 'Qtde Funcionarios',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'inscricao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('inscricao', ( isset($item->inscricao) ? $item->inscricao : '' ) ) ,
                                                    'titulo' => 'Inscriçao / login',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'password',
                                                    'classe' => 'senha',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('senha', ( isset($item->senha) ? $item->senha : '' ) ) ,
                                                    'titulo' => 'Senha',
                                                    'complemento' => '<div class="btn-group btn-group-xs" role="group" aria-label=""><button class="btn btn-danger ver-senha" type="button">Ver senha</button>'
                                                                   . '<a class="btn btn-primary" href="http://www.pow.com.br/ope/painel_login.php?inscricao='.$item->inscricao.'&key=41be7336a7f841675f5ac0ae4317ae86-'.$item->inscricao.'" target="_blank">Acessar POWPainel</a></div>'
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'boletos_usuario',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('boletos_usuario', ( isset($item->boletos_usuario) ? $item->boletos_usuario : '' ) ) ,
                                                    'titulo' => 'Boleto Usuario',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'boletos_senha',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('boletos_senha', ( isset($item->boletos_senha) ? $item->boletos_senha : '' ) ) ,
                                                    'titulo' => 'Boleto Senha',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'textarea',
                                                    'classe' => 'empresa_descricao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_descricao', ( isset($item->empresa_descricao) ? $item->empresa_descricao : '' ) ) ,
                                                    'titulo' => 'Empresa descriçao',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_titulo',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_titulo', ( isset($item->pagina_titulo) ? $item->pagina_titulo : '' ) ) ,
                                                    'titulo' => 'Pagina titulo',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_nome_inicial',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_nome_inicial', ( isset($item->pagina_nome_inicial) ? $item->pagina_nome_inicial : '' ) ) ,
                                                    'titulo' => 'Pagina nome inicial',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'nome_seo',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('nome_seo', ( isset($item->nome_seo) ? $item->nome_seo : '' ) ) ,
                                                    'titulo' => 'Nome SEO',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane alert alert-warning" id="localizacao">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Localização:</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 resposta-endereco">
                                    
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'cep',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'cep',
                                                    'valor' => set_value('cep', ( isset($item->cep) ? $item->cep : '' ) ) ,
                                                    'titulo' => 'CEP',
                                                    'nao-salva' => 1
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'cidade',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('cidade', ( isset($item->cidade) ? $item->cidade : '' ) ) ,
                                                    'titulo' => 'Cidade',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="form-group logradouro">
                                        <label for="logradouro">Logradouro</label>
                                        <input name="id_logradouro" id="id_logradouro" type="hidden" value="<?php echo set_value('id_logradouro', isset($item->id_logradouro ) ? $item->id_logradouro : '');?>">
                                        <input name="logradouro" type="text" class="form-control campo-<?php echo $sequencia;?>" data-sequencia="<?php echo $sequencia;?>" data-nao-salva="1" id="logradouro" placeholder="Escreva o Logradouro" value="<?php echo set_value('logradouro', isset($item->logradouro ) ? $item->logradouro : '');?>">
                                        <?php $sequencia++;?>
                                        <p class="logradouro help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_numero',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_numero', ( isset($item->empresa_numero) ? $item->empresa_numero : '' ) ) ,
                                                    'titulo' => 'Numero',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_complemento',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_complemento', ( isset($item->empresa_complemento) ? $item->empresa_complemento : '' ) ) ,
                                                    'titulo' => 'Complemento',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'bairro',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('bairro', ( isset($item->bairro) ? $item->bairro : '' ) ) ,
                                                    'titulo' => 'Bairro',
                                                    'nao_salva' => 1
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="form-group estado">
                                        <label for="estado">Estado</label>
                                        <?php
                                        $config['valor'] = $estados; 
                                        $config['nome'] = 'estado'; 
                                        $config['extra'] = 'id="estado" data-sequencia="" data-nao-salva="1"'; 
                                        $config['class'] = 'campo'; 
                                        echo form_select($config, set_value('estado', (isset($item->estado) ? $item->estado : '') ) ); 
                                        ?>
                                        <p class="estado help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="mapa">
                                        <?php 
                                        if ( ENVIRONMENT != 'development' )
                                        {
                                            $sender = 'http://www.guiasjp.com.br/gmap/gmap_popup.php?address='.urlencode((isset($item->logradouro) ? iconv('UTF-8', 'ISO-8859-1', $item->logradouro) : '').( isset($item->empresa_numero) ? ', '.$item->empresa_numero : '' ).( isset($item->cidade) ? '- '.iconv('UTF-8', 'ISO-8859-1', $item->cidade) : '' ).( isset($item->estado) ? '- '.$item->estado : '' )).'&latitude='.$item->latitude.'&longitude='.$item->longitude.'&info='.urlencode(iconv('UTF-8', 'ISO-8859-1',$item->empresa_nome_fantasia)).'&id='.$item->id.'&tabela=empresas&sender=';
                                            $endereco_mapa = $sender.''.urlencode($sender);
                                            ?>
                                            <iframe src="<?php echo $endereco_mapa;?>" width="100%" height="600px" border="none">
                                            <?php
                                        }
                                        ?>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane alert alert-warning" id="contatos">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Contatos:</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                    <h4>Contato da empresa:</h4>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'contato_nome',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('contato_nome', ( isset($item->contato_nome) ? $item->contato_nome : '' ) ) ,
                                                    'titulo' => 'Nome',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'contato_email',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('contato_email', ( isset($item->contato_email) ? $item->contato_email : '' ) ) ,
                                                    'titulo' => 'Email',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'contato_telefone',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'telefone',
                                                    'valor' => set_value('contato_telefone', ( isset($item->contato_telefone) ? $item->contato_telefone : '' ) ) ,
                                                    'titulo' => 'Telefone',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_emailagenda',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_emailagenda', ( isset($item->empresa_emailagenda) ? $item->empresa_emailagenda : '' ) ) ,
                                                    'titulo' => 'Email agenda',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_emaillocacao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('empresa_emaillocacao', ( isset($item->empresa_emaillocacao) ? $item->empresa_emaillocacao : '' ) ) ,
                                                    'titulo' => 'Email Locação',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                    <h4 class="pull-left">Autorizador:</h4>
                                    <br>
                                    <div class="autorizador-message">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'autorizador_cpf',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'cpf autorizador',
                                                    'valor' => set_value('autorizador_cpf', ( isset($item->autorizador_cpf) ? $item->autorizador_cpf : '' ) ) ,
                                                    'titulo' => 'CPF',
                                                    'nao-salva' => 1,
                                                    'complemento' => '<a href="http://www.receita.fazenda.gov.br/Aplicacoes/ATCTA/CPF/ConsultaPublica.asp" target="_blank">conferir CPF</a>',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'autorizador_nome',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'autorizador',
                                                    'valor' => set_value('autorizador_nome', ( isset($item->autorizador_nome) ? $item->autorizador_nome : '' ) ) ,
                                                    'titulo' => 'Nome',
                                                    'nao-salva' => 1,
                                                    'complemento' => '<input name="id_autorizador" type="hidden" class="form-control autorizador" id="id_autorizador" placeholder="Autorizador ID" value="'.set_value('id_autorizador', isset($item->id_autorizador ) ? $item->id_autorizador : '').'">',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'autorizador_nascimento',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'autorizador data',
                                                    'valor' => set_value('autorizador_nascimento', ( isset($item->autorizador_nascimento) ? $item->autorizador_nascimento : '' ) ) ,
                                                    'titulo' => 'Nascimento',
                                                    'nao-salva' => 1,
                                                    'complemento' => '',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'autorizador_cargo',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('autorizador_cargo', ( isset($item->autorizador_cargo) ? $item->autorizador_cargo : '' ) ) ,
                                                    'titulo' => 'Cargo',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'autorizador_email',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('autorizador_email', ( isset($item->autorizador_email) ? $item->autorizador_email : '' ) ) ,
                                                    'titulo' => 'Email',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'autorizador_telefone',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'telefone',
                                                    'valor' => set_value('autorizador_telefone', ( isset($item->autorizador_telefone) ? $item->autorizador_telefone : '' ) ) ,
                                                    'titulo' => 'Telefone',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                    
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane alert alert-warning" id="integracao">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Integração:</h2>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group sistema">
                                        <label for="sistema">Sistema</label>
                                        <?php
                                        $config['valor'] = $sistema; 
                                        $config['nome'] = 'sistema'; 
                                        $config['extra'] = 'id="sistema" data-sequencia="'.$sequencia.'"'; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('sistema', (isset($item->sistema) ? $item->sistema : '') ) ); 
                                        ?>
                                        <p class="sistema help-block"></p>
                                        <?php $sequencia++;?>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'chave_empresa',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('chave_empresa', ( isset($item->chave_empresa) ? $item->chave_empresa : '' ) ) ,
                                                    'titulo' => 'Endereço para integração',
                                                    'complemento' => '<a href="'.base_url().'integracao/download/'.$item->id.'" class="btn btn-info" target="_blank">Verificar arquivo</a>
                                                                        <a href="'.base_url().'integracao/por_empresa/'.$item->id.'" class="btn btn-info" target="_blank">Integrar agora</a>',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <?php 
                                            $botao['classe'] = 'email_log';
                                            $botao['valor'] = set_value('email_log', ( isset($item->email_log) ? $item->email_log : '' ) ) ;
                                            $botao['texto']['on'] = 'Nao recebe log integraçao';
                                            $botao['texto']['off'] = 'Recebe log integraçao';
                                            echo set_botao_editavel($botao);
                                            unset($botao);
                                            ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="form-group ultima_integracao">
                                        <label for="ultima_integracao">Data da Ultima integração</label>
                                        <p class="ultima_integracao help-block">
                                            <?php echo isset($item->ultima_integracao) ? $item->ultima_integracao : 'Sem nenhuma integração.';?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div role="tabpanel" class="tab-pane alert alert-warning" id="servicos">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Serviços:</h2>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3>Site: </h3>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                    <br>
                                    <a href="http://www.powinternet.com/contrato_powsites/contrato_reimpresso.php?inscricao=<?php echo $item->inscricao;?>&base=empresas" class="btn btn-primary" target="_blank">Contrato site</a>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'data_site',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'data_hora_pt_br',
                                                    'valor' => set_value('data_site', ( isset($item->data_site) ? $item->data_site : '' ) ) ,
                                                    'titulo' => 'Data Site',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                    <div class="form-group plano_desejado">
                                        <label for="plano_desejado">Plano Site</label>
                                        <?php
                                        $config['valor'] = $planos_sites; 
                                        $config['nome'] = 'plano_desejado'; 
                                        $config['extra'] = 'id="plano_desejado" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('plano_desejado', (isset($item->plano_desejado) ? $item->plano_desejado : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="plano_desejado help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
                                    <div class="form-group plano_mensal">
                                        <label for="plano_mensal">Plano Hospedagem Mensal</label>
                                        <?php
                                        $config['valor'] = $planos_mensal; 
                                        $config['nome'] = 'plano_mensal'; 
                                        $config['extra'] = 'id="plano_mensal" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('plano_mensal', (isset($item->plano_mensal) ? $item->plano_mensal : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="plano_mensal help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                        <?php 
                                        $botao['classe'] = 'tem_site';
                                        $botao['valor'] = set_value('tem_site', ( isset($item->tem_site) ? $item->tem_site : '' ) ) ;
                                        $botao['texto']['on'] = 'Tem site';
                                        $botao['texto']['off'] = 'Não tem site';
                                        echo set_botao_editavel($botao);
                                        unset($botao);
                                        ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                        <?php 
                                        $botao['classe'] = 'mobile';
                                        $botao['valor'] = set_value('mobile', ( isset($item->mobile) ? $item->mobile : '' ) ) ;
                                        $botao['texto']['on'] = 'Site mobile';
                                        $botao['texto']['off'] = 'Não site mobile';
                                        echo set_botao_editavel($botao);
                                        unset($botao);
                                        ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                            <?php 
                                            $botao['classe'] = 'venda_ativa';
                                            $botao['valor'] = set_value('venda_ativa', ( isset($item->venda_ativa) ? $item->venda_ativa : '' ) ) ;
                                            $botao['texto']['on'] = 'Venda Ativa';
                                            $botao['texto']['off'] = 'Venda inativa';
                                            echo set_botao_editavel($botao);
                                            unset($botao);
                                            ?>
                                    </div>
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                    <?php 
                                            $botao['classe'] = 'menu_guiasjp';
                                            $botao['valor'] = set_value('menu_guiasjp', ( isset($item->menu_guiasjp) ? $item->menu_guiasjp : '' ) ) ;
                                            $botao['texto']['on'] = 'Tem menu GuiaSJP';
                                            $botao['texto']['off'] = 'Não tem menu GuiaSJP';
                                            echo set_botao_editavel($botao);
                                            unset($botao);
                                            ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                    <?php 
                                            $botao['classe'] = 'newsletter_ativo';
                                            $botao['valor'] = set_value('newsletter_ativo', ( isset($item->newsletter_ativo) ? $item->newsletter_ativo : '' ) ) ;
                                            $botao['texto']['on'] = 'Tem Newsletter';
                                            $botao['texto']['off'] = 'Não tem Newsletter';
                                            echo set_botao_editavel($botao);
                                            unset($botao);
                                            ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <?php 
                                        $botao['classe'] = 'servicos_album';
                                        $botao['valor'] = set_value('servicos_album', ( isset($item->servicos_album) ? $item->servicos_album : '' ) ) ;
                                        $botao['texto']['on'] = 'Serviço Album';
                                        $botao['texto']['off'] = 'Não serviço Album';
                                        $botao['datas'] = array(
                                                                'inicio' => array( 'valor' => set_value('servicos_album_inicio', ( isset($item->servicos_album_inicio) ? $item->servicos_album_inicio : '' ) ) ), 
                                                                'fim' => array( 'valor' => set_value('servicos_album_termino', ( isset($item->servicos_album_termino) ? $item->servicos_album_termino : '' ) ) ), 
                                                                );
                                        $complemento = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'servicos_album_limite',
                                                    'class' => '',
                                                    'valor' => set_value('servicos_album_limite', ( isset($item->servicos_album_limite) ? $item->servicos_album_limite : '' ) ) ,
                                                    'titulo' => $botao['texto']['on'].' Limite',
                                                );
                                        $botao['complemento'] = set_campo_editavel($complemento);
                                        echo set_botao_editavel($botao);
                                        unset($botao);
                                        ?>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3>Portal: </h3>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                    <br>
                                    <a href="http://www.portaisimobiliarios.com.br/contrato/contratoKK.php?base=empresas&inscricao=<?php echo $item->inscricao;?>" class="btn btn-primary" target="_blank">Contrato Imoveis</a>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                    <br>
                                    <a href="http://guiasjp.com/contrato.php?&inscricao=<?php echo $item->inscricao;?>&base=empresas&status=" class="btn btn-primary" target="_blank">Contrato GuiaSJP</a>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'data_portal',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'data_hora_pt_br',
                                                    'valor' => set_value('data_portal', ( isset($item->data_portal) ? $item->data_portal : '' ) ) ,
                                                    'titulo' => 'Data Portal',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group plano_publicidade">
                                        <label for="plano_publicidade">Plano publicidade</label>
                                        <?php
                                        $config['valor'] = $planos_pi; 
                                        $config['nome'] = 'plano_publicidade'; 
                                        $config['extra'] = 'id="plano_publicidade" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('plano_publicidade', (isset($item->plano_publicidade) ? $item->plano_publicidade : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="plano_publicidade help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-8">
                                    <div class="form-group plano_promocao">
                                        <label for="plano_promocao">Promoção</label>
                                        <?php
                                        $config['valor'] = $empresas_promocao; 
                                        $config['nome'] = 'plano_promocao'; 
                                        $config['extra'] = 'id="plano_promocao" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('plano_promocao', (isset($item->plano_promocao) ? $item->plano_promocao : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="plano_promocao help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'desconto_pub',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('desconto_pub', ( isset($item->desconto_pub) ? $item->desconto_pub : '' ) ) ,
                                                    'titulo' => 'Desconto Publicidade',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                        <?php 
                                        $botao['classe'] = 'aciap';
                                        $botao['valor'] = set_value('aciap', ( isset($item->aciap) ? $item->aciap : '' ) ) ;
                                        $botao['texto']['on'] = 'Membro ACIAP';
                                        $botao['texto']['off'] = 'Não membro ACIAP';
                                        echo set_botao_editavel($botao);
                                        unset($botao);
                                        ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                        <?php 
                                        $botao['classe'] = 'shopping';
                                        $botao['valor'] = set_value('shopping', ( isset($item->shopping) ? $item->shopping : '' ) ) ;
                                        $botao['texto']['on'] = 'Shopping';
                                        $botao['texto']['off'] = 'Sem Shopping';
                                        echo set_botao_editavel($botao);
                                        unset($botao);
                                        ?>
                                </div>
                                
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                        <?php 
                                        $botao['classe'] = 'descricao_linhas';
                                        $botao['valor'] = set_value('descricao_linhas', ( isset($item->descricao_linhas) ? $item->descricao_linhas : '' ) ) ;
                                        $botao['texto']['on'] = 'Descrição Linhas ';
                                        $botao['texto']['off'] = 'Não descrição linhas';
                                        $complemento = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'servicos_album_limite',
                                                    'class' => '',
                                                    'valor' => set_value('servicos_album_limite', ( isset($item->servicos_album_limite) ? $item->servicos_album_limite : '' ) ) ,
                                                    'titulo' => $botao['texto']['on'].' Limite',
                                                );
                                        $botao['complemento'] = set_campo_editavel($complemento);
                                        echo set_botao_editavel($botao);
                                        unset($botao);
                                        ?>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <?php 
                                        $botao['classe'] = 'servicos_pagina';
                                        $botao['valor'] = set_value('servicos_pagina', ( isset($item->servicos_pagina) ? $item->servicos_pagina : '' ) ) ;
                                        $botao['texto']['on'] = 'Serviço Pagina';
                                        $botao['texto']['off'] = 'Não serviço Pagina';
                                        $botao['datas'] = array(
                                                                'inicio' => array( 'valor' => set_value('servicos_pagina_inicio', ( isset($item->servicos_pagina_inicio) ? $item->servicos_pagina_inicio : '' ) ) ), 
                                                                'fim' => array( 'valor' => set_value('servicos_pagina_termino', ( isset($item->servicos_pagina_termino) ? $item->servicos_pagina_termino : '' ) ) ), 
                                                                );
                                        echo set_botao_editavel($botao);
                                        unset($botao);
                                        ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_limite_produtos',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_limite_produtos', ( isset($item->pagina_limite_produtos) ? $item->pagina_limite_produtos : '' ) ) ,
                                                    'titulo' => 'Limite Produtos / serviços / imoveis',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_limite_ofertas',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_limite_ofertas', ( isset($item->pagina_limite_ofertas) ? $item->pagina_limite_ofertas : '' ) ) ,
                                                    'titulo' => 'Limite ofertas / fotos',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'itens_liberados',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('itens_liberados', ( isset($item->itens_liberados) ? $item->itens_liberados : '' ) ) ,
                                                    'titulo' => 'Itens Liberados',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group pagina_visivel">
                                        <label for="pagina_visivel">Pagina Visivel</label>
                                        <?php
                                        $config['valor'] = $pagina_visivel; 
                                        $config['nome'] = 'pagina_visivel'; 
                                        $config['extra'] = 'id="pagina_visivel" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('pagina_visivel', (isset($item->pagina_visivel) ? $item->pagina_visivel : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="pagina_visivel help-block"></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                                
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'servicos_sms_limite',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('servicos_sms_limite', ( isset($item->servicos_sms_limite) ? $item->servicos_sms_limite : '' ) ) ,
                                                    'titulo' => 'Limite SMS',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'empresa_fone_sms',
                                                    'sequencia' => $sequencia,
                                                    'class' => 'telefone',
                                                    'valor' => set_value('empresa_fone_sms', ( isset($item->empresa_fone_sms) ? $item->empresa_fone_sms : '' ) ) ,
                                                    'titulo' => 'Telefone SMS',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'ordenacao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('ordenacao', ( isset($item->ordenacao) ? $item->ordenacao : '' ) ) ,
                                                    'titulo' => 'Ordenação',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
    
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane alert alert-warning" id="publicidade">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Publicidade:</h2>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3>Banners:</h3>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php 
                                    if ( isset($publicidade) && count($publicidade) > 0 ) :
                                        ?>
                                    <ul class="list-group">
                                        <?php 
                                        foreach( $publicidade as $banner ) :
                                            ?>
                                            <li class="list-group-item banners banner-<?php echo $banner->id;?>" data-item="<?php echo $banner->id;?>">
                                                <?php echo $banner->descricao;?> 
                                                <span class="pull-right">
                                                    <a href="<?php echo base_url();?>publicidade_campanhas/editar/<?php echo $banner->id;?>" class="btn btn-warning" target="_blank">Editar</a>
                                                    <a href="<?php echo base_url();?>estatisticas/listar/publicidade_campanhas/<?php echo $banner->id;?>" class="btn btn-default" target="_blank">Estatisticas</a>
                                                </span>
                                            </li>
                                            <?php
                                        endforeach;
                                        ?>
                                    </ul>
                                        <?php
                                    else:
                                        ?>
                                    <ul class="list-group">
                                        <li class="list-group-item">Nenhum item</li>
                                    </ul>
                                        <?php
                                    endif;
                                    ?>
                                    <a href="<?php echo base_url();?>publicidade_campanhas/adicionar/<?php echo $item->id;?>" target="_blank" class="btn btn-primary add-banner">Adicionar Banner</a>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3>Destaques:</h3>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <h4>Destaques Home:</h4>
                                    <?php 
                                    if ( isset($destaques) && count($destaques) > 0 ) :
                                        ?>
                                    <ul class="list-group">
                                        <?php 
                                        foreach( $destaques as $destaque ) :
                                            ?>
                                            <li class="list-group-item destaque destaque-<?php echo $destaque->id;?>" data-item="<?php echo $destaque->id;?>">
                                                <?php echo $destaque->descricao;?> 
                                            </li>
                                            <?php
                                        endforeach;
                                        ?>
                                    </ul>
                                        <?php
                                    else:
                                        ?>
                                    <ul class="list-group">
                                        <li class="list-group-item">Nenhum item</li>
                                    </ul>
                                        <?php
                                    endif;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <h4>Vitrine de tipo:</h4>
                                    <?php 
                                    if ( isset($dest_listagem) && count($dest_listagem) > 0 ) :
                                        ?>
                                    <ul class="list-group">
                                        <?php 
                                        foreach( $dest_listagem as $dest ) :
                                            ?>
                                            <li class="list-group-item dest dest-<?php echo $dest->id;?>" data-item="<?php echo $dest->id;?>">
                                                <?php echo $dest->descricao;?> 
                                                <span class="pull-right">
                                                    <a href="<?php echo base_url();?>imoveis_dest_listagem/editar/<?php echo $dest->id;?>" class="btn btn-warning" target="_blank">Editar</a>
                                                    <a href="<?php echo base_url();?>imoveis_dest_listagem/estatiticas/<?php echo $dest->id;?>" class="btn btn-default" target="_blank">estatisticas</a>
                                                </span>
                                            </li>
                                            <?php
                                        endforeach;
                                        ?>
                                    </ul>
                                        <?php
                                    else:
                                        ?>
                                    <ul class="list-group">
                                        <li class="list-group-item">Nenhum item</li>
                                    </ul>
                                        <?php
                                    endif;
                                    ?>
                                    <a href="<?php echo base_url();?>imoveis_dest_listagem/adicionar/<?php echo $item->id;?>" target="_blank" class="btn btn-primary add-banner">Adicionar vitrine de tipo</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <h4>Vitrine de Bairro:</h4>
                                    <?php 
                                    if ( isset($destaques_bairro['itens']) && $destaques_bairro['qtde'] > 0 ) :
                                        ?>
                                    <ul class="list-group">
                                        <?php 
                                        foreach( $destaques_bairro['itens'] as $destaque_bairro ) :
                                        ?>
                                            <li class="list-group-item destaque destaque-<?php echo $destaque_bairro->id;?>" data-item="<?php echo $destaque_bairro->id;?>">
                                                <?php echo $destaque_bairro->negocio_.' - '.$destaque_bairro->tipo.' - '.$destaque_bairro->cidade.'('.$destaque_bairro->bairro.') - ate '.$destaque_bairro->data_fim;?> 
                                                <span class="pull-right">
                                                    <a href="<?php echo base_url();?>imoveis_destaque_bairro/editar/<?php echo $destaque_bairro->id;?>" class="btn btn-warning" target="_blank">Editar</a>
                                                    <!--<a href="<?php echo base_url();?>imoveis_destaque_bairro/estatiticas/<?php echo $destaque_bairro->id;?>" class="btn btn-default" target="_blank">estatisticas</a>-->
                                                </span>
                                            </li>
                                            <?php
                                        endforeach;
                                        ?>
                                    </ul>
                                        <?php
                                    else:
                                        ?>
                                    <ul class="list-group">
                                        <li class="list-group-item">Nenhum item</li>
                                    </ul>
                                        <?php
                                    endif;
                                    ?>
                                    <a href="<?php echo base_url();?>imoveis_destaque_bairro/adicionar/<?php echo $item->id;?>" target="_blank" class="btn btn-primary add-banner">Adicionar vitrine de bairro</a>
                                </div>
                                
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane alert alert-warning" id="pagina">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Pagina:</h2>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class='alert alert-info'>
                                        <?php
                                        $campo = array(
                                                        'tipo' => 'text',
                                                        'classe' => 'pagina_link',
                                                        'sequencia' => $sequencia,
                                                        'class' => '',
                                                        'valor' => set_value('pagina_link', ( isset($item->pagina_link) ? $item->pagina_link : '' ) ) ,
                                                        'titulo' => 'Link',
                                                    );
                                        echo set_campo_editavel($campo);
                                        unset($campo);
                                        $sequencia++;
                                        $campo = array(
                                                        'tipo' => 'textarea',
                                                        'classe' => 'pagina_link_desc',
                                                        'sequencia' => $sequencia,
                                                        'class' => '',
                                                        'valor' => set_value('pagina_link_desc', ( isset($item->pagina_link_desc) ? $item->pagina_link_desc : '' ) ) ,
                                                        'titulo' => 'Descriçao  Link',
                                                    );
                                        echo set_campo_editavel($campo);
                                        unset($campo);
                                        $sequencia++;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class='alert alert-info'>
                                        <?php
                                        $campo = array(
                                                        'tipo' => 'text',
                                                        'classe' => 'titulo_site',
                                                        'sequencia' => $sequencia,
                                                        'class' => '',
                                                        'valor' => set_value('titulo_site', ( isset($item->titulo_site) ? $item->titulo_site : '' ) ) ,
                                                        'titulo' => 'Titulo site',
                                                    );
                                        echo set_campo_editavel($campo);
                                        unset($campo);
                                        $sequencia++;
                                        $campo = array(
                                                        'tipo' => 'textarea',
                                                        'classe' => 'pagina_texto',
                                                        'sequencia' => $sequencia,
                                                        'class' => '',
                                                        'valor' => set_value('pagina_texto', ( isset($item->pagina_texto) ? $item->pagina_texto : '' ) ) ,
                                                        'titulo' => 'Texto livre',
                                                    );
                                        echo set_campo_editavel($campo);
                                        unset($campo);
                                        $sequencia++;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'textarea',
                                                    'classe' => 'pagina_funcionamento',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_funcionamento', ( isset($item->pagina_funcionamento) ? $item->pagina_funcionamento : '' ) ) ,
                                                    'titulo' => 'Texto horario de funcionamento',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'textarea',
                                                    'classe' => 'palavraschave',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('palavraschave', ( isset($item->palavraschave) ? $item->palavraschave : '' ) ) ,
                                                    'titulo' => 'Palavras-chave',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                
                                
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_linhas',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_linhas', ( isset($item->pagina_linhas) ? $item->pagina_linhas : '' ) ) ,
                                                    'titulo' => 'Numero de linhas',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                    <?php
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_links',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_links', ( isset($item->pagina_links) ? $item->pagina_links : '' ) ) ,
                                                    'titulo' => 'Numero de Paginas',
                                                );
                                    echo set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group pagina_tipo">
                                        <label for="pagina_tipo">Tipo Pagina</label>
                                        <?php
                                        $config['valor'] = $pagina_tipo; 
                                        $config['nome'] = 'pagina_tipo'; 
                                        $config['extra'] = 'id="pagina_tipo" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('pagina_tipo', (isset($item->pagina_tipo) ? $item->pagina_tipo : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="pagina_tipo help-block"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group largura">
                                        <label for="largura">Largura</label>
                                        <?php
                                        $config['valor'] = $largura; 
                                        $config['nome'] = 'largura'; 
                                        $config['extra'] = 'id="largura" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('largura', (isset($item->largura) ? $item->largura : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="largura help-block"></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group modelo">
                                        <label for="modelo">Cor site presença</label>
                                        <?php
                                        $config['valor'] = $modelo; 
                                        $config['nome'] = 'modelo'; 
                                        $config['extra'] = 'id="modelo" data-sequencia="'.$sequencia.'" '; 
                                        $config['class'] = 'campo-'.$sequencia; 
                                        echo form_select($config, set_value('modelo', (isset($item->modelo) ? $item->modelo : '') ) ); 
                                        $sequencia++;
                                        ?>
                                        <p class="modelo help-block"></p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane alert alert-warning" id="images">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2>Images:</h2>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-4">
                                    <?php
                                    $image['classe'] = 'pagina_logo_pequeno';
                                    $image['image'] = ( isset($item->pagina_logo_pequeno) && ! empty($item->pagina_logo_pequeno) ) ? IMAGE_POW.$item->id.'/'.$item->pagina_logo_pequeno : NULL;
                                    $image['titulo'] = 'Logo pequeno';
                                    echo set_image_editavel($image);
                                    unset($image);
                                    ?>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                    <?php
                                    $image['classe'] = 'pagina_logo_grande';
                                    $image['image'] = ( isset($item->pagina_logo_grande) && ! empty($item->pagina_logo_grande) ) ? IMAGE_POW.$item->id.'/'.$item->pagina_logo_grande : NULL;
                                    $image['titulo'] = 'Logo grande';
                                    echo set_image_editavel($image);
                                    unset($image);
                                    ?>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                    <?php
                                    $image['classe'] = 'pagina_foto1';
                                    $image['image'] = ( isset($item->pagina_foto1) && ! empty($item->pagina_foto1) ) ? IMAGE_POW.$item->id.'/'.$item->pagina_foto1 : NULL;
                                    $image['titulo'] = 'Foto 1';
                                    
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_foto1_descricao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_foto1_descricao', ( isset($item->pagina_foto1_descricao) ? $item->pagina_foto1_descricao : '' ) ) ,
                                                    'titulo' => 'Foto 1 Descrição',
                                                );
                                    $image['complemento'] = set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    echo set_image_editavel($image);
                                    unset($image);
                                    ?>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                    <?php
                                    $image['classe'] = 'pagina_foto2';
                                    $image['image'] = ( isset($item->pagina_foto2) && ! empty($item->pagina_foto2) ) ? IMAGE_POW.$item->id.'/'.$item->pagina_foto2 : NULL;
                                    $image['titulo'] = 'Foto 2';
                                    
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_foto2_descricao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_foto2_descricao', ( isset($item->pagina_foto2_descricao) ? $item->pagina_foto2_descricao : '' ) ) ,
                                                    'titulo' => 'Foto 2 Descrição',
                                                );
                                    $image['complemento'] = set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    echo set_image_editavel($image);
                                    unset($image);
                                    ?>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                    <?php
                                    $image['classe'] = 'pagina_foto3';
                                    $image['image'] = ( isset($item->pagina_foto3) && ! empty($item->pagina_foto3) ) ? IMAGE_POW.$item->id.'/'.$item->pagina_foto3 : NULL;
                                    $image['titulo'] = 'Foto 3';
                                    
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_foto3_descricao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_foto3_descricao', ( isset($item->pagina_foto3_descricao) ? $item->pagina_foto3_descricao : '' ) ) ,
                                                    'titulo' => 'Foto 3 Descrição',
                                                );
                                    $image['complemento'] = set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    echo set_image_editavel($image);
                                    unset($image);
                                    ?>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                    <?php
                                    $image['classe'] = 'pagina_foto4';
                                    $image['image'] = ( isset($item->pagina_foto4) && ! empty($item->pagina_foto4) ) ? IMAGE_POW.$item->id.'/'.$item->pagina_foto4 : NULL;
                                    $image['titulo'] = 'Foto 4';
                                    
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_foto4_descricao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_foto4_descricao', ( isset($item->pagina_foto4_descricao) ? $item->pagina_foto4_descricao : '' ) ) ,
                                                    'titulo' => 'Foto 4 Descrição',
                                                );
                                    $image['complemento'] = set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    echo set_image_editavel($image);
                                    unset($image);
                                    ?>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                    <?php
                                    $image['classe'] = 'pagina_foto5';
                                    $image['image'] = ( isset($item->pagina_foto5) && ! empty($item->pagina_foto5) ) ? IMAGE_POW.$item->id.'/'.$item->pagina_foto5 : NULL;
                                    $image['titulo'] = 'Foto 5';
                                    
                                    $campo = array(
                                                    'tipo' => 'text',
                                                    'classe' => 'pagina_foto5_descricao',
                                                    'sequencia' => $sequencia,
                                                    'class' => '',
                                                    'valor' => set_value('pagina_foto5_descricao', ( isset($item->pagina_foto5_descricao) ? $item->pagina_foto5_descricao : '' ) ) ,
                                                    'titulo' => 'Foto 5 Descrição',
                                                );
                                    $image['complemento'] = set_campo_editavel($campo);
                                    unset($campo);
                                    $sequencia++;
                                    echo set_image_editavel($image);
                                    unset($image);
                                    ?>
                                </div>


                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</form>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-base">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title"></h4>
</div>
<div class="modal-body">

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->