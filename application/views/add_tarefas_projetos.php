<?php $sequencia = 1;

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
                <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
            </div>
        </div>
    </div>
    <form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
        <div class="alert alert-danger">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="editavel" id="editavel" class="form-control editavel" value="<?php echo $editavel;?>">
                    <input type="hidden" name="id_usuario_sessao" id="id_usuario_sessao" class="form-control id_usuario_sessao" value="<?php echo $id_usuario_sessao;?>">
                    <h3>
                        <span type="button" class=" pull-left abre-instrucoes glyphicon glyphicon-chevron-down"></span><span class="pull-left">&nbsp;&nbsp;</span>
                        <span class="pull-left glyphicon glyphicon-info-sign"></span>
                        <span class="pull-left">&nbsp;&nbsp; Instruções &nbsp;&nbsp; <small>** Leia-me antes de qualquer coisa, por favor... </small> </span>
                        <button type="button" class="btn btn-default pull-right status_servico glyphicon glyphicon-download">
                            Status: <span class="status" data-item="<?php echo isset($item->id) ? $item->id : '';?>"><?php echo isset($item->id) ? ( $editavel ? 'Editando' : 'Verificando' ) : 'Novo';?></span>
                        </button>
                    </h3>
                    <br><br>
                    <button type="button" class="btn btn-default pdf-projeto pull-right"><span class="glyphicon glyphicon-dashboard"></span> Ver Doc do projeto</button>
                    <br><br>
                    <button type="button" class="btn btn-default doc-equipe pull-right"><span class="glyphicon glyphicon-dashboard"></span> Enviar Doc para Equipe do projeto</button>
                    
                </div>
                <hr>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide espaco-instrucoes"><!-- hide --> 
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-heading"><strong>O que devo saber sobre Projetos?</strong></li>
                        <li class="list-group-item"><strong>O que não são Projeto?</strong> : Suas demandas diarias, elas são <strong>processos</strong>, um exemplo simples: ver seus emails e responder, sejam com prazos para os projetos ou resolvendo um problema. um exemplo complexo: prospectar os clientes. Se você realiza projetos e processos diariamente, divida seu tempo e não esqueça dos processos na hora que esta definindo as tarefas e atividades do projeto.</li>
                        <li class="list-group-item"><strong>O que são Projetos?</strong> : São demandas, objetivos que uma equipe, setor ou individuo deseja realizar ou alcançar, com um tempo definido de esforço e sabendo qual será este, ao fim do projeto este estará finalizado e servirá apenas de historico ou modelo. <br>
                            - Ex 1: Definir um modelo de atendimento;<br>
                            - Ex 2: Desenvolver uma nova ferramenta;<br>
                            - Ex 3: Otimizar uma ferramenta antiga;<br>
                            - Ex 4: Definir modelos para otimizar os processos diários;<br>
                            - Ex 5: Otimizar o forma como o fluxo de caixa trabalha;<br>
                            - Ex 6: Definir e melhorar as tarefas do dia a dia;<br>
                            - Ex 7: Uma viajem;<br>
                            - Ex 8: Adquirir um bem duravel;<br>
                            Poderia colocar 1000 linhas aqui de itens que são projetos.<br>
                        </li>
                        <li class="list-group-item"><strong>Por que devo criar Projetos?</strong> : Para otimizar seu tempo, de seus colegas, da empresa e assim ter mais agilidade no dia a dia, criando novas ferramentas, otimização de processos e criação. </li>
                        <li class="list-group-item"><strong>O que é escopo do Projeto?</strong> São o grupo de campos que listamos abaixo e que achamos mais importantes para o andamento do nosso modelo de projetos, ele vai resultar em um documento com cronograma, premissas, requisições, restrições, equipe, critérios de aceite, medição e critérios de qualidade, alem de riscos que podem margear o projeto. </li>
                        <li class="list-group-item"><strong>Por que devo preencher todos estes campos com detalhamento?</strong> A primeira vista um projeto pode parecer bastante logico dentro da sua cabeça, onde quer chegar com isto, como deve chegar, mas apartir do momento em que trabalhamos em equipe ou até para objetivos individuais, esta documentação vai colaborar na comunicação entre os envolvidos, demandando tarefas e criterios para que estas tarefas sejam entreques, se não definirmos nenhum <strong>criterio de aceite</strong> por exemplo, significa que o projeto pode ser entregue de qualquer forma. Ou, se não definirmos nem um <strong>risco</strong> quer dizer que o mundo é perfeito e que estamos acima de qualquer imprevisto e vamos agir com impulsividade quando qualquer um deles acontecer. Se não definirmos a equipe e o papel de cada, signfica que este projeto andaria até sozinho, pq somos necessarios?</li>
                        <li class="list-group-item"><strong>Como Projetos vão me ajudar a ser alguem melhor na minha vida?</strong> : Apartir do momento que você tem objetivos claros, traça metas e define a qualidade dos seus objetivos, isto otimizará seu tempo, liberando assim sua mente para ter novas ideias. Tente isto para sua vida, viajens, orçamentos, mercado e até coisas menores.</li>
                        <li class="list-group-item"><strong>O que não posso esquecer em um Projeto?</strong> : Principalmente o detalhamento dos campos, quanto mais detalhado menos surpresas e melhor vai sair a ferramenta, novas ideias vão surgir e tudo ficara mais bonito no fim.</li>
                        <li class="list-group-item"><strong>Somos obrigados a seguir este modelo?</strong> : Não... Você não tem obrigação de utilizar, pode continuar seguindo seus preceitos e crenças sobre o dia a dia desde que não envolva programação, neste caso, você será obrigado a seguir se quiser que suas demandas sejam sanadas.</li>
                        <li class="list-group-item"><strong>Isto o programador tirou da cabeça dele?</strong> : Que bom se fosse... Mas no mundo hoje, os maiores, melhores e bem sucedidos projetos desenvolvidos seguem estes passos e definição.</li>
                        <li class="list-group-item"><strong>Tenho duvidas ainda, onde consigo mais informações sobre isto?</strong> : Que bom que se interessou... utilize o <a href="https://brasil.pmi.org/brazil/PMBOKGuideAndStandards.aspx" target="_blank">PMBOK e PMI, padrões de projeto</a>.</li>
                        <li class="list-group-item list-group-item-info">Siga os passos abaixo para preencher os campos do seu projeto.</li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-heading"><strong>Como vou utilizar esta ferramenta?</strong></li>
                        <li class="list-group-item">Utilize os campos abaixo para descrever detalhadamente o <b>Escopo do projeto</b>;</li>
                        <li class="list-group-item">Lembre que este é um documento e deve ser seguido como um roteiro para que o projeto seja bem sucedido.</li>
                        <li class="list-group-item">Busque ser o mais detalhado possivel, utilizando todos os campos, quantas vezes for necessário;</li>
                        <li class="list-group-item">Os itens Principais e as propriedades principais são salvos automaticamente;</li>
                        <li class="list-group-item">O item ao lado -> "Status", lhe diz qual o estado do documento, Salvo, Editando, novo ou algum problema;</li>
                        <li class="list-group-item">Os itens que acompanham o Botão: "Adicionar", são de multiplas respostas e devem ser preenchidos todos os campos e devem ser salvos a cada inserção ou alteração;</li>
                        <li class="list-group-item">Os itens adicionaveis e de multiplos valores, devem ser salvos após o preenchimento de todos os campos;</li>
                        <li class="list-group-item">Fique tranquilo, você poderá editar este documento quantas vezes for necessario após salva-lo.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="alert alert-info principal">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <input type="hidden" name="id" class="id" value="<?php echo isset($item->id) ? $item->id : 0;?>">
                    <input type="hidden" name="id_tarefas_portfolio" class="id_tarefas_portfolio" value="<?php echo $item_tarefas_portfolio->id;?>">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-heading"><strong>Informações do Portfolio</strong></li>
                        <li class="list-group-item"><strong>Portfolio: </strong><?php echo $item_tarefas_portfolio->titulo;?></li>
                        <li class="list-group-item"><strong>Descrição: </strong><?php echo $item_tarefas_portfolio->descricao;?></li>
                        <li class="list-group-item"><strong>Demanda Semanal: </strong><?php echo $item_tarefas_portfolio->demanda_semanal;?> hs</li>
                        <li class="list-group-item"><strong>Responsável: </strong><?php echo $item_tarefas_portfolio->responsavel;?></li>
                    </ul>

                </div>
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                    <?php
                    $campo = array(
                                    'tipo' => 'text',
                                    'classe' => 'titulo',
                                    'sequencia' => $sequencia,
                                    'class' => '',
                                    'controller' => 'tarefas_projetos',
                                    'tabela' => 'tarefas_projetos',
                                    'valor' => set_value('contrato', ( isset($item->titulo) ? $item->titulo : '' ) ) ,
                                    'titulo' => 'Titulo',
                                );
                    echo set_campo_editavel($campo);
                    unset($campo);
                    $sequencia++;
                    ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group id_setor_responsavel">
                        <label for="id_setor_responsavel">Setor Responsável:</label>
                        <div class="controls ">
                            <?php 
                            $config['valor'] = $cargos; 
                            $config['nome'] = 'id_setor_responsavel'; 
                            $config['extra'] = 'id="id_setor_responsavel" data-sequencia="2"'; 
                            $config['class'] = 'campo-2'; 
                            $config['controller'] = 'tarefas_projetos';
                            $config['tabela'] = 'tarefas_projetos'; 
                            echo form_select($config, set_value('id_setor_responsavel', isset($item->id_setor_responsavel) ? $item->id_setor_responsavel : '' ) );
                            ?>
                        </div>
                        <p class="help-block id_responsavel"></p>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
                    <div class="form-group id_responsavel">
                        <label for="id_responsavel">Gerente do Projeto:</label>
                            <?php 
                            $config['valor'] = $usuarios; 
                            $config['nome'] = 'id_responsavel'; 
                            $config['extra'] = 'id="id_responsavel" data-sequencia="'.$sequencia.'"';
                            $config['class'] = 'campo-'.$sequencia;
                            $config['controller'] = 'tarefas_projetos';
                            $config['tabela'] = 'tarefas_projetos'; 
                            echo form_select($config, set_value('id_responsavel', isset($item->id_responsavel) ? $item->id_responsavel : '' ) ); 
                            $sequencia++;
                        ?>
                        <p class="help-block id_responsavel">Selecione o usuário que é responsável por este Projeto.</p>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12 pull-right">
                    <div class="form-group status_projeto">
                        <label for="status_projeto">Status do Projeto:</label>
                        <div class="controls ">
                            <?php 
                            $config['valor'] = $status; 
                            $config['nome'] = 'status_projeto'; 
                            $config['extra'] = 'id="status_projeto" data-sequencia="'.$sequencia.'"'; 
                            $config['class'] = 'campo-'.$sequencia; 
                            $config['controller'] = 'tarefas_projetos';
                            $config['tabela'] = 'tarefas_projetos'; 
                            echo form_select($config, set_value('status_projeto', isset($item->status_projeto) ? $item->status_projeto : '' ) );
                            $sequencia++;
                            ?>
                        </div>
                        <p class="help-block status_projeto"></p>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12 pull-right">
                    <?php
                    $campo = array(
                                    'tipo' => 'textarea',
                                    'classe' => 'descricao',
                                    'sequencia' => $sequencia,
                                    'class' => '',
                                    'valor' => set_value('descricao', ( isset($item->descricao) ? $item->descricao : '' ) ) ,
                                    'controller' => 'tarefas_projetos',
                                            'tabela' => 'tarefas_projetos',
                                    'titulo' => 'Descriçao',
                                );
                    echo set_campo_editavel($campo);
                    unset($campo);
                    $sequencia++;
                    ?>
                </div>
            </div>
        </div>
        
        <div class="itens hide">
            <ul class="nav nav-pills" role="tablist">
                <li role="presentation" class="active">
                    <a href="#propriedades" aria-controls="propriedades" role="tab" data-toggle="tab">Propriedades principais</a>
                </li>
                <li role="presentation" >
                    <a href="#equipe" aria-controls="equipe" role="tab" data-toggle="tab">Equipe</a>
                </li>
                <li role="presentation" >
                    <a href="#aceite" aria-controls="aceite" role="tab" data-toggle="tab">Critérios de Aceite</a>
                </li>
                <li role="presentation" >
                    <a href="#marcos" aria-controls="marcos" role="tab" data-toggle="tab">Principais Marcos</a>
                </li>
                <li role="presentation" >
                    <a href="#comunicacao" aria-controls="comunicacao" role="tab" data-toggle="tab">Comunicação</a>
                </li>
                <li role="presentation" >
                    <a href="#qualidade" aria-controls="qualidade" role="tab" data-toggle="tab">Qualidade</a>
                </li>
                <li role="presentation" >
                    <a href="#riscos" aria-controls="riscos" role="tab" data-toggle="tab">Riscos</a>
                </li>
                <li role="presentation" >
                    <a href="#tarefas" aria-controls="tarefas" role="tab" data-toggle="tab" class='tarefas_requisita'>Tarefas</a>
                </li>
                <li role="presentation" >
                    <a href="#cronograma" aria-controls="cronograma" role="tab" data-toggle="tab" class="cronograma_requisita">Cronograma</a>
                </li>
                <li role="presentation" >
                    <a href="#iteracao" aria-controls="iteracao" role="tab" data-toggle="tab" class="iteracao_requisita">Interações</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="propriedades" role="tabpanel" class="tab-pane active principal">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php
                                $campo = array(
                                            'tipo' => 'textarea',
                                            'classe' => 'premissas',
                                            'sequencia' => $sequencia,
                                            'class' => '',
                                            'valor' => set_value('premissas', ( isset($item->premissas) ? $item->premissas : '' ) ) ,
                                            'titulo' => 'Referencias / Premissas ',
                                            'controller' => 'tarefas_projetos',
                                            'tabela' => 'tarefas_projetos',
                                            'helper_text' => 'de acordo com o PMBOK “Premissas são fatores que, para fins de planejamento, são considerados verdadeiros, reais ou certos, sem prova ou demonstração.” ou utilize para colocar as referencias de modelo de site por exemplo.'
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
                                            'classe' => 'requisitos',
                                            'sequencia' => $sequencia,
                                            'class' => '',
                                            'valor' => set_value('requisitos', ( isset($item->requisitos) ? $item->requisitos : '' ) ) ,
                                            'titulo' => 'Requisitos',
                                            'tabela' => 'tarefas_projetos',
                                            'controller' => 'tarefas_projetos',
                                            'helper_text' => 'Os requisitos devem ser obtidos, analisados e registrados em detalhes suficientes para serem medidos durante a execução do projeto.<br>Os requisitos serão a base para construção da EAP.'
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
                                            'classe' => 'exclusao_escopo',
                                            'sequencia' => $sequencia,
                                            'class' => '',
                                            'valor' => set_value('exclusao_escopo', ( isset($item->exclusao_escopo) ? $item->exclusao_escopo : '' ) ) ,
                                            'titulo' => 'Exclusao do Escopo',
                                            'controller' => 'tarefas_projetos',
                                            'tabela' => 'tarefas_projetos',
                                            'helper_text' => '“Exclusões do projeto. Identifica de modo geral o que é excluído do projeto. Declarar explicitamente o que está fora do escopo do projeto ajuda no gerenciamento das expectativas das partes interessadas.”'
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
                                            'classe' => 'restricoes',
                                            'sequencia' => $sequencia,
                                            'class' => '',
                                            'valor' => set_value('restricoes', ( isset($item->restricoes) ? $item->restricoes : '' ) ) ,
                                            'tabela' => 'tarefas_projetos',
                                            'controller' => 'tarefas_projetos',
                                            'titulo' => 'Restrições',
                                            'helper_text' => 'de acordo com o PMBOK “O estado, a qualidade ou o sentido de estar restrito a uma determinada ação ou inatividade. Uma restrição ou limitação aplicável, interna ou externa, a um projeto, a qual afetará o desempenho do projeto ou de um processo.”'
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
                                            'classe' => 'riscos_iniciais',
                                            'sequencia' => $sequencia,
                                            'class' => '',
                                            'valor' => set_value('riscos_iniciais', ( isset($item->riscos_iniciais) ? $item->riscos_iniciais : '' ) ) ,
                                            'titulo' => 'Riscos Iniciais',
                                            'controller' => 'tarefas_projetos',
                                            'tabela' => 'tarefas_projetos',
                                            'helper_text' => 'Identifica os riscos iniciais do projeto'
                                            );
                                echo set_campo_editavel($campo);
                                unset($campo);
                                $sequencia++;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="equipe" role="tabpanel" class="tab-pane">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <h4>Equipe</h4>
                                <p class="pull-left">Adicione usuários e seu(s) papel(is) no projeto. Utilize o botão adicionar para incluir mais campos.</p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center">
                                <button type="button" class="btn btn-info adicionar-novo-por-tipo" data-tipo="has_usuarios"><span class="glyphicon glyphicon-plus" ></span>Adicionar</button>
                            </div>
                        </div>
                        <div class="espaco-has-usuarios">
                            <?php 
                            echo $has_usuarios;
                            ?>
                        </div>
                    </div>
                </div>
                <div id="aceite" role="tabpanel" class="tab-pane">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <h4>Critérios de Aceite</h4>
                                <p class="pull-left">Preencha o(s) critério(s) de aceite do projeto. Utilize o botão adicionar para incluir mais campos.</p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center">
                                <button type="button" class="btn btn-info adicionar-novo-por-tipo" data-tipo="aceite" ><span class="glyphicon glyphicon-plus" ></span>Adicionar</button>
                            </div>
                        </div>
                        <div class="espaco-aceite">
                            <?php 
                            echo $aceite;
                            ?>
                        </div>
                    </div>
                </div>
                <div id="marcos" role="tabpanel" class="tab-pane">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <h4>Principais Marcos do Projeto</h4>
                                <p class="pull-left">Preencha o(s) marco(s) do projeto. Utilize o botão adicionar para incluir mais campos.</p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center">
                                <button type="button" class="btn btn-info adicionar-novo-por-tipo" data-tipo="marcos" ><span class="glyphicon glyphicon-plus" ></span>Adicionar</button>
                            </div>
                        </div>
                        <div class="espaco-marcos">
                            <?php 
                            echo $marcos;
                            ?>
                        </div>
                    </div>
                </div>
                <div id="comunicacao" role="tabpanel" class="tab-pane">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <h4>Eventos e Comunicação do Projeto.</h4>
                                <p class="pull-left">Preencha o(s) item(ns) comunicação do projeto. Utilize o botão adicionar para incluir mais campos.</p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center">
                                <button type="button" class="btn btn-info adicionar-novo-por-tipo" data-tipo="comunicacao" ><span class="glyphicon glyphicon-plus" ></span>Adicionar</button>
                            </div>
                        </div>
                        <div class="espaco-comunicacao">
                            <?php 
                            echo $comunicacao;
                            ?>
                        </div>
                    </div>
                </div>
                <div id="qualidade" role="tabpanel" class="tab-pane">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <h4>Medição e critérios de qualidade.</h4>
                                <p class="pull-left">Preencha o(s) item(ns) qualidade do projeto. Utilize o botão adicionar para incluir mais campos.</p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center">
                                <button type="button" class="btn btn-info adicionar-novo-por-tipo" data-tipo="qualidade" ><span class="glyphicon glyphicon-plus" ></span>Adicionar</button>
                            </div>
                        </div>
                        <div class="espaco-qualidade">
                            <?php 
                            echo $qualidade;
                            ?>
                        </div>
                    </div>
                </div>
                <div id="riscos" role="tabpanel" class="tab-pane">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <h4>Possiveis riscos. Positivos e negativos.</h4>
                                <p class="pull-left">Preencha o(s) item(ns) de risco(s) do projeto. Os riscos podem ser positivos ou negativos, sendo medido como beneficio ou possivel prejuizo, podendo assim prever ganhos ou dividendos. Utilize o botão adicionar para incluir mais campos.</p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center">
                                <button type="button" class="btn btn-info adicionar-novo-por-tipo" data-tipo="riscos" ><span class="glyphicon glyphicon-plus" ></span>Adicionar</button>
                            </div>
                        </div>
                        <div class="espaco-riscos">
                            <?php 
                            echo $riscos;
                            ?>
                        </div>
                    </div>
                </div>
                <div id="tarefas" role="tabpanel" class="tab-pane">
                    <div class="alert alert-success">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-heading">Indicações:</li>
                            <li class="list-group-item"><strong>O que são tarefas?</strong> : São objetivos que devem ser alcançados com o andamento do projeto, elas compõe o cronograma e andamento do projeto.</li>
                            <li class="list-group-item"><strong>Quem define?</strong> : O responsável junto da area tecnica.</li>
                            <li class="list-group-item"><strong>Quem precisa de projetos e tarefas?</strong> : Todos os setores da empresa, pode utilizar, criar, produzir com base em demandas e melhorias.</li>
                            <li class="list-group-item"><strong>Que critérios utilizar?</strong><br> 
                                                        - Busque montar as tarefas que tenham objetivos claros e bem definidos;<br> 
                                                        - Utilize o criterio de no maximo 4 horas de ação, para que uma tarefa não estrapole o tempo de demanda semanal do projeto, aqui teremos pequenos pedaços.<br>
                                                        - Utilize as atividades para detalhar mais a tarefa, particionar e até dividir as demandas entre os envolvidos.
                                                        - Detalhes são vitais para este processo, se você não sabe os detalhes da sua tarefa peça ajuda, se reuna com seus colegas e melhore o texto da tarefa.
                                                        - Evite tarefas com titulo curto e descrição deficiente. Seu projeto depende disto para andar.
                            </li>
                            <li class="list-group-item"><strong>Como controlar?</strong> : O controle das tarefas pode ser acompanhado no cronograma do projeto.</li>
                            <li class="list-group-item"><strong>***** O que não posso esquecer?</strong> : Que investir um longo tempo detalhando e definindo o escopo do projeto, seu andamento, riscos e qualidade, vai me economizar tempo trabalhando e pensando nas soluções que quero alcançar, resultando no sucesso do projeto.</li>
                            <li class="list-group-item list-group-item-danger"><strong>Clique na aba para recarregar e atualizar as tarefas.</strong></li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class='btn btn-primary add_tarefas' type="button">Adicionar nova tarefa.</button>
                        </div>
                    </div>
                    <div class="row espaco-tarefas">
                        
                    </div>
                </div>
                <div id="cronograma" role="tabpanel" class="tab-pane">
                    <div class="row espaco-cronograma">
                        
                    </div>
                </div>
                <div id="iteracao" role="tabpanel" class="tab-pane">
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <h4>Interações do projeto.</h4>
                                <p class="pull-left"></p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center">
                                <button type="button" class="btn btn-info adicionar-novo-por-tipo" data-tipo="iteracao" ><span class="glyphicon glyphicon-plus" ></span>Adicionar tópico</button>
                            </div>
                        </div>
                        <div class="espaco-iteracao">
                            <div class="interacoes">
                                <?php 
                                echo $iteracoes;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
      
    

<!-- Desenvolvimento e aperfeiçoamento da ferramenta de Administração de sites, publicidade, Notícias e conteúdo em geral do Pow Internet.  -->