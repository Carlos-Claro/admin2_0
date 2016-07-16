        <div class="alert alert-success">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group restricoes">
                        <label for="restricoes">Restrições</label>
                        <textarea name="item[restricoes]" class="form-control" id="restricoes" placeholder="Restrições"><?php echo set_value('item[restricoes]', isset($item->restricoes) ? $item->restricoes : '');?></textarea>
                        <p class="help-block restricoes">de acordo com o PMBOK “O estado, a qualidade ou o sentido de estar restrito a uma determinada ação ou inatividade. Uma restrição ou limitação aplicável, interna ou externa, a um projeto, a qual afetará o desempenho do projeto ou de um processo.”</p>
                    </div>
                </div>
            </div>
        </div>