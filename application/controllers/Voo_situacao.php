<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voo_Situacao extends MY_Controller 
{

    public function __construct() 
    {
        $valida = ( isset($_GET['usuario'] ) && $_GET['usuario'] == '41be7336a7f841675f5ac0ae4317ae86' ) ? FALSE : TRUE;
        parent::__construct($valida);
        $this->load->model('voo_situacao_model');
    }
    
    /**
     * Função que pega a situação dos voos de Curitiba do site da infraero e 
     * trata para que sejam salvos no banco de dados.
     * 
     * @author Breno Henrique Moreno Nunes
     * @since 1.0 23/09/2014 
     */
    public function sincronizar()
    {
        $retorno = NULL;
        $pagina = file_get_contents('http://voos.infraero.gov.br/hstvoos/RelatorioPortal.aspx');
        $pagina = trim($pagina);
        if(strstr('<td>Curitiba-PR</td>',$pagina[0]))
        {
            $pagina = preg_replace('/#\d{6}/', '', $pagina);
            $pagina = str_replace('<font color="" size="1">', '', $pagina);
            $pagina = str_replace('</font>', '', $pagina);
            $this->voo_situacao_model->excluir();
           
            $a = explode('<td>Curitiba-PR</td>', $pagina);
            $b = explode('</tr>', $a[1]);
            $c = str_replace('<td>', '', $b[0]);
            $c = str_replace('</td>', '', $c);
            $c = str_replace('</span>', '', $c);
            $d = explode('<span', $c);
            foreach($d as $e)
            {
                $f[] = preg_replace('/^(\d\.\d)|[id=">|)|(|%]|((\w)+(\d)+[^\.%])/', '', trim($e));
            }
            $data['qtde_voos'] = $f[0];
            $data['atrasados'] = $f[1];
            $data['atrasados_momento'] = $f[5];
            $data['cancelados'] = $f[9];
            $data['data'] = date('Y-m-d H:i:s');
            $retorno = $this->voo_situacao_model->adicionar($data);
        }
        return $retorno;
    }
    
}
