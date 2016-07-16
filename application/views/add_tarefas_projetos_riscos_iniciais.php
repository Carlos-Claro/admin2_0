<div class="alert alert-success">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group riscos_iniciais">
                <label for="riscos_iniciais">Riscos Iniciais</label>
                <textarea placeholder="Riscos Iniciais" id="riscos_iniciais" class="form-control" name="item[riscos_iniciais]"><?php echo set_value('item[riscos_iniciais]', isset($item->riscos_iniciais) ? $item->riscos_iniciais : '');?></textarea>
                <p class="help-block riscos_iniciais">Identifica os riscos iniciais do projeto</p>
            </div>
        </div>
    </div>
</div>