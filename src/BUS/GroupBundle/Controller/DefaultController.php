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

                $this->agruparOrdenamiento($rango, $ordenado, $num_elementos);

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

        for ($x=0; $x < $num_elementos; $x++) { // buscar el menor
            $menor = $x; // 1er posicion

            for ($y=$x+1; $y <= $num_elementos-1; $y++) { // comparar con el resto
                if ( $ordenado[$y] < $ordenado[$menor] ) {
                    $menor = $y;
                }
            }

            /**
             * Cambiarlos de posicion y volver a hacer el recorrido
             */
            $comodin            = $ordenado[$x];        // var temporal como comodin
            $ordenado[$x]       = $ordenado[$menor];    // menor al inicio
            $ordenado[$menor]   = $comodin;             // 1er posicion al lugar que tenia el menor
        }

        return $ordenado;
    }

    private function agruparOrdenamiento($rango, $ordenado = array(), $num_elementos){
        $nvo = array();
        $rangos = array();

        $min = $ordenado[0];
        $max = $ordenado[$num_elementos-1];


        for ($indice=$min; $indice <= $max; $indice++) {
            if ( ($indice % $rango) == 0 ) {
                array_push($rangos, $indice);
            }
        }

        print_r($rangos);

        for ($x=0; $x <= count($rangos)-1; $x++) {

            /*if ( $x == 0 ) {
                echo '[' . $rangos[$x] . ']';
            }else{
                if ( $x == count($rangos)-1) {
                    echo '[' . $rangos[$x] . ']';
                }else{
                    echo '[' . $rangos[$x] . ' ' . $rangos[$x+1] . ']';
                }
            }*/

            $nvo1 = array();
            for ($y=0; $y < $num_elementos; $y++) {

                if ( $x == 0 ) {

                    if ( $ordenado[$y] <=  $rangos[$x] ) {
                        array_push($nvo1, $ordenado[$y]);
                    }

                }else{
                    if ( $x == count($rangos)-1) {

                        if ( $ordenado[$y] >  $rangos[$x] ) {
                            array_push($nvo1, $ordenado[$y]);
                        }

                    }else{

                        if ( $ordenado[$y] >  $rangos[$x] && $ordenado[$y] <= $rangos[$x+1] ) {
                            array_push($nvo1, $ordenado[$y]);
                        }

                    }
                }
            }

            if ( count($nvo1) > 0 ) {
                array_push($nvo, $nvo1);
            }

        }
        print_r($nvo);
    }

}
