<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Camisetas extends MY_Controller
{
    private $e = 0;
    private $c = 0;
    private $t = 0;
    private $b = 0;
    
    public function _construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        echo 'CAMISETA<br><br>';
        
        $this->estilo_mostrar();
        $this->cor_mostrar();
        $this->tamanho_mostrar();
        if($this->e==0)
        {
            $this->banda_mostrar();
        }
    }
    
    public function estilo($atributo1 = NULL, $linha = 0)
    {
        $data[] = array (
                        'estilo'=> array(
                                    'rock',
                                    'normal'
                        )
        );
        if(isset($atributo1))
        {
            $retorno = $data[$linha][$atributo1];
        }
        else
        {
            $retorno = $data[$linha];
        }
        
        return $retorno;
    }
    
    public function estilo_mostrar()
    {
        $atributo1 = array(
            'estilo'
        );
        for($a=0;$a<=0;$a++)
        {
            foreach ($atributo1 as $valor)
            {
                $estilo = $this->estilo($valor,$a);
                if(is_array($estilo))
                {
                    echo $valor.'= ';
                    foreach ($estilo as $key => $value) {
                        if($key==$this->e)
                        {
                            echo $value.'<br>';
                        }
                    }
                }
                else
                {
                   echo $valor.'= '.$estilo.'<br>'; 
                }
            }
        }
    }
    
    public function cor($atributo2 = NULL, $linha = 0)
    {
        $data[] = array (
                        'cor'=> array(
                                    'preto',
                                    'branco',
                        )
        );
        if(isset($atributo2))
        {
            $retorno = $data[$linha][$atributo2];
        }
        else
        {
            $retorno = $data[$linha];
        }
        
        return $retorno;
    }
    
    public function cor_mostrar()
    {
        $atributo2 = array(
            'cor'
        );
        for($a=0;$a<=0;$a++)
        {
            foreach ($atributo2 as $valor)
            {
                $cor = $this->cor($valor,$a);
                if(is_array($cor))
                {
                    echo $valor.'= ';
                    foreach ($cor as $key => $value) {
                        if($key==$this->c)
                        {
                            echo $value.'<br>';
                        }
                    }
                }
                else
                {
                   echo $valor.'= '.$cor.'<br>';  
                }
            }
        }
    }
    
    public function tamanho($atributo3 = NULL, $linha = 0)
    {
        $data[] = array(
                    'tamanho'=> array(
                                    'P',
                                    'M',
                                    'G',
                                    'GG' 
                    )
        );
        if(isset($atributo3))
        {
            $retorno = $data[$linha][$atributo3];
        }
        else
        {
            $retorno = $data[$linha];
        }
        
        return $retorno;
    }
    
    public function tamanho_mostrar()
    {
        $atributo3 = array(
                            'tamanho'
        );
        for($a=0;$a<=0;$a++)
        {
            foreach ($atributo3 as $valor)
            {
                $tamanho = $this->tamanho($valor,$a);
                if(is_array($tamanho))
                {
                    echo $valor.'= ';
                    foreach ($tamanho as $key => $value) {
                        if($key==$this->t)
                        {
                            echo $value.'<br>';
                        }
                    }
                }
                else
                {
                   echo $valor.'= '.$tamanho.'<br>';  
                }
            }
        }
    }

    public function banda($atributo4 = NULL, $linha = 0)
    {
        $data[] = array(
                        'banda'=> array(
                                        'nome'=>'Iron Maiden',
                                        'album'=>'The Tropper',
                                        'estilo'=>'Heavy Metal', 
                        )
        );
        $data[] = array(
                        'banda'=> array(
                                        'nome'=>'AC/DC',
                                        'album'=>'Back in Black',
                                        'estilo'=>'Hard Rock',
                        )
        );
        $data[] = array(
                        'banda'=> array(
                                        'nome'=>'Slipknot',
                                        'album'=>'All Hope is Gone',
                                        'estilo'=>'Metal',
                        )
        );
        $data[] = array(
                        'banda'=> array(
                                        'nome'=>'Avenged Sevenfold',
                                        'album'=>'Nightmare',
                                        'estilo'=>'Metal',
                        )
        );
        if(isset($atributo4))
        {
            $retorno = $data[$linha][$atributo4];
        }
        else
        {
            $retorno = $data[$linha];
        }
        
        return $retorno;
    }
    
    public function banda_mostrar()
    {
        $atributo4 = array(
                            'banda'
        );
        for($a=$this->b;$a<=$this->b;$a++)
        {
            foreach ($atributo4 as $valor)
            {
                $banda = $this->banda($valor,$a);
                if(is_array($banda))
                {
                    echo $valor.'= <br>';
                    foreach ($banda as $key => $value) {
                        echo '______'.$key.': '.$value.'<br>';
                    }
                }
                else
                {
                   echo $valor.'= '.$banda.'<br>';  
                }
            }
        }
    }
}


