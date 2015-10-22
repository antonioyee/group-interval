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

                $ordenado = $this->ordenarGrupo($original, $num_elementos); // ordenar grupo

                $agrupado = $this->agruparOrdenamiento($rango, $ordenado, $num_elementos); // agrupar grupos en sub-grupos

                $resultado = array('group_ordenado' => $agrupado, 'ordenado' => TRUE);
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
        $agrupados  = array();
        $rangos     = array();
        $min        = $ordenado[0];                 // valor minimo
        $max        = $ordenado[$num_elementos-1];  // valor maximo

        for ($indice=$min; $indice <= $max; $indice++) {    // recorrer las cifras min hasta max
            if ( ($indice % $rango) == 0 ) {                // determinar los multiplos del rango especificado
                array_push($rangos, $indice);
            }
        }

        for ($x=0; $x <= count($rangos)-1; $x++) { // recorrer el arreglo con los multiplos del rango especificado

            $sub_agrupados = array();
            for ($y=0; $y < $num_elementos; $y++) { // recorrer el arreglo de los valores ha agrupar
                                                    // determinar el rango el que estan y agruparlos

                if ( $x == 0 ) { // primer / menor

                    if ( $ordenado[$y] <=  $rangos[$x] ) {
                        array_push($sub_agrupados, $ordenado[$y]);
                    }

                }else{
                    if ( $x == count($rangos)-1) { // max / ultimo

                        if ( $ordenado[$y] >  $rangos[$x] ) {
                            array_push($sub_agrupados, $ordenado[$y]);
                        }

                    }else{ // valores intermedios

                        if ( $ordenado[$y] > $rangos[$x] && $ordenado[$y] <= $rangos[$x+1] ) {
                            array_push($sub_agrupados, $ordenado[$y]);
                        }

                    }
                }
            }

            if ( count($sub_agrupados) > 0 ) { // valores sub-agrupados procede a agruparlos
                array_push($agrupados, $sub_agrupados);
            }
        }

        return $agrupados;
    }

}
