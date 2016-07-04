<?php

class Html_pdf{
    
    private $pdf = NULL;
    
    public function __construct(  ) 
    {
        $this->pdf = new CanGelis\PDF\PDF('/usr/bin/wkhtmltopdf');
    }
    
    public function retorna_pdf($html)
    {
       $retorno = '';
        try
        {
            $retorno = $this->pdf->loadHTML($html)->get();
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die();
            //var_dump($ex);
        }
        return $retorno;
    }
    
}