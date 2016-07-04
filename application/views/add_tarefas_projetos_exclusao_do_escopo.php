        <div class="alert alert-success">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group exclusao_escopo">
                        <label for="exclusao_escopo">Exclusão do Escopo</label>
                        <textarea name="item[exclusao_escopo]" class="form-control" id="exclusao_escopo" placeholder="Exclusão do Escopo"><?php echo set_value('item[exclusao_escopo]', isset($item->exclusao_escopo) ? $item->exclusao_escopo : '');?></textarea>
                        <p class="help-block exclusao_escopo">“Exclusões do projeto. Identifica de modo geral o que é excluído do projeto. Declarar explicitamente o que está fora do escopo do projeto ajuda no gerenciamento das expectativas das partes interessadas.”</p>
                    </div>
                </div>
            </div>
        </div>
