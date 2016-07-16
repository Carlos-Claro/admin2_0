        <div class="alert alert-success">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group premissas">
                        <label for="premissas">Premissas</label>
                        <textarea name="item[premissas]" class="form-control" id="premissas" placeholder="Premissas"><?php echo set_value('item[premissas]', isset($item->premissas) ? $item->premissas : '');?></textarea>
                        <p class="help-block premissas">de acordo com o PMBOK “Premissas são fatores que, para fins de planejamento, são considerados verdadeiros, reais ou certos, sem prova ou demonstração.”</p>
                    </div>
                </div>
            </div>
        </div>
