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

                $resultado = array('group_ordenado' => $original, 'ordenado' => TRUE);
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

}
