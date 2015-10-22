<?php

namespace BUS\GroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller{

    public function indexAction(){
        return $this->render('BUSGroupBundle:Default:index.html.twig');
    }

    public function ordenarAction(){
        $rango      = $_POST['rango'];
        $original   = $_POST['group'];

        if ( $original != 0 && $rango != '' ) { // valores existentes
            $num_elementos = count($original);
            $num = 0;

            foreach($original as $digito) { // verificar que el arreglo sea valido
                if( is_numeric($digito) ) {
                    $num++;
                }
            }

            if ( $num == $num_elementos && is_numeric($rango) ) {

                $ordenado = $this->ordenarGrupo($original, $num_elementos);

                $resultado = array('group_ordenado' => $ordenado, 'ordenado' => TRUE);
            }else{
                $resultado = array('mensaje' => 'throw InvalidArgumentException', 'ordenado' => FALSE);
            }
        }else{
            $resultado = array('mensaje' => 'Empty Set', 'ordenado' => FALSE);
        }

        $response = new Response();
        $response->setContent(json_encode($resultado));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function ordenarGrupo($ordenado = array(), $num_elementos){
        $menor      = NULL;
        $comodin    = NULL;

        for ($x=0; $x < $num_elementos; $x++) {
            $menor = $x;

            for ($y=$x+1; $y <= $num_elementos-1; $y++) {
                if ( $ordenado[$y] < $ordenado[$menor] ) {
                    $menor = $y;
                }
            }

            $comodin            = $ordenado[$x];
            $ordenado[$x]       = $ordenado[$menor];
            $ordenado[$menor]   = $comodin;
        }

        return $ordenado;
    }

}
