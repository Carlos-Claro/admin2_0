      <div class="alert alert-success">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group requisitos">
                        <label for="requisitos">Requisitos</label>
                        <textarea name="item[requisitos]" class="form-control" id="requisitos" placeholder="Requisitos"><?php echo set_value('item[requisitos]', isset($item->requisitos) ? $item->requisitos : '');?></textarea>
                        <p class="help-block requisitos">Os requisitos devem ser obtidos, analisados e registrados em detalhes suficientes para serem medidos durante a execução do projeto.<br>Os requisitos serão a base para construção da EAP.</p>
                    </div>
                </div>
            </div>
        </div>