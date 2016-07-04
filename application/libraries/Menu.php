<?php

class Menu 
{
    private $itens = array();
    //array( 'titulo', 'tipo', 'link' => array( 'href', 'base', 'title' ), 'class', 'itens' => array( 'titulo', 'link' => array( 'href', 'base', 'title' ), 'extra' )  );
    private $selecionado = array();
    //array( 'principal', 'secundario' )
    private $tipo = 'principal';
    //
    
    public function inicia( $data = array() ) 
    {
        $this->itens = isset($data['itens']) ? $data['itens'] : array();
        $this->tipo = isset($data['tipo']) ? $data['tipo'] : 'principal';
        $this->selecionado = isset($data['selecionado']) ? $data['selecionado'] : array();
        $menu = $this->_set_menu();
        return $menu;
    }
    
    private function _set_menu ()
    {
        
        $menu = '<ul class="nav navbar-nav">'.PHP_EOL;
        foreach ( $this->itens as $item )
        {
            $drop = ( isset($item->itens) && count($item->itens) > 0 ) ? TRUE : FALSE; 
            $menu .= '<li class="';
            $menu .= $drop ? 'dropdown ' : '';
            $menu .= ( ( $this->selecionado['classe'] == $item->classe ) ) ? ' active ' : '';
            $menu .= '" >'.PHP_EOL;
            $menu .= '<a href="'. ( $item->classe == '#' ? '#' : base_url().$item->classe ).'" ';
            $menu .= 'title="'.$item->titulo.'" ';
            $menu .= 'class="'.( isset($item->class) ? $item->class : '' ).' '.( $drop ? 'dropdown-toggle' : '' ).'" ';
            $menu .= $drop ? ' data-toggle="dropdown" ' : '';
            $menu .= ' >'.$item->titulo.' '.($drop ? '<b class="caret"></b>' : '');
            $menu .= '</a>';
            if ( $drop )
            {
                $menu .= '<ul class="dropdown-menu">';
                foreach ( $item->itens as $itens )
                {
                    $menu .= '<li class="';
                    if ( ( $this->selecionado['classe'] == $item->classe ) && ( $this->selecionado['function'] == $itens->classe ) )
                    {
                        $menu .= 'active';
                    }
                    $menu .= '">'.PHP_EOL;
                    $menu .= '<a href="'. base_url().($item->classe == '#' ? '' : $item->classe.'/').$itens->classe .'">';
                    $menu .= $itens->titulo;
                    $menu .= '</a>'.PHP_EOL;
                    $menu .= '</li>'.PHP_EOL;
                }
                $menu .= '</ul>'.PHP_EOL;
            }
            $menu .= '</li>'.PHP_EOL;
                    
        }
        $menu .= '</ul>'.PHP_EOL;
        return $menu;
    }

}