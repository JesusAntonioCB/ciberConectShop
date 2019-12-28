<?php

namespace ciberConnect\shopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DefaultController extends Controller
{

    public function indexAction() {
      $jsondata = file_get_contents(__DIR__.'../../json/listMovie.json');
      $data = json_decode($jsondata, true);
      $active=true;
      return $this->render('ciberConnectshopBundle:Default:index.html.twig',array(
          'listRecomendamos' => $data,
          'home' => $active
         )
       );
    }
    public function peliulasAction($page) {

      // dump($page);
      // die;
      // $jsonListEstrenos = file_get_contents(__DIR__.'../../json/listMovie.json');
      // $listEstrenos = json_decode($jsonListEstrenos, true);
      $listPeliculas= $this->getMovies(null,null,null,$page);
      $jsonListGeneros = file_get_contents(__DIR__.'../../json/listGenerosMovies.json');
      $listGeneros = json_decode($jsonListGeneros, true);
      $active=true;
        return $this->render('ciberConnectshopBundle:ciberConnectShop:peliculas.html.twig',array(
            'listPeliculas' => $listPeliculas,
            'listGeneros' => $listGeneros,
            'peliculas' => $active
           )
         );
    }

    public function peliulaAction($movie) {
      $pelicula= $this->getMovie($movie);
      return $this->render('ciberConnectshopBundle:ciberConnectShop:insideMovie.html.twig',array(
          'pelicula' => $pelicula
         )
       );
    }

    public function serviciosAction() {
      $jsonListEstrenos = file_get_contents(__DIR__.'../../json/listMovie.json');
      $listEstrenos = json_decode($jsonListEstrenos, true);
      $jsonListGeneros = file_get_contents(__DIR__.'../../json/listGenerosMovies.json');
      $listGeneros = json_decode($jsonListGeneros, true);
      $active=true;
        return $this->render('ciberConnectshopBundle:ciberConnectShop:peliculas.html.twig',array(
          'listPeliculas' => $listEstrenos,
          'listGeneros' => $listGeneros,
          'servicios' => $active
        )
        );
    }
    public function musicaAction() {
      $jsonListEstrenos = file_get_contents(__DIR__.'../../json/listMovie.json');
      $listEstrenos = json_decode($jsonListEstrenos, true);
      $jsonListGeneros = file_get_contents(__DIR__.'../../json/listGenerosMovies.json');
      $listGeneros = json_decode($jsonListGeneros, true);
      $active=true;
        return $this->render('ciberConnectshopBundle:ciberConnectShop:peliculas.html.twig',array(
          'listPeliculas' => $listEstrenos,
          'listGeneros' => $listGeneros,
          'musica' => $active
        )
        );
    }
    public function productosAction() {
      $jsonListEstrenos = file_get_contents(__DIR__.'../../json/listMovie.json');
      $listEstrenos = json_decode($jsonListEstrenos, true);
      $jsonListGeneros = file_get_contents(__DIR__.'../../json/listGenerosMovies.json');
      $listGeneros = json_decode($jsonListGeneros, true);
      $active=true;
      return $this->render('ciberConnectshopBundle:ciberConnectShop:peliculas.html.twig',array(
        'listPeliculas' => $listEstrenos,
        'listGeneros' => $listGeneros,
        'productos' => $active
      )
      );
    }

    public function getMovies($genero="",$filtro=[],$busqueda="",$pagina=1){
      $em = $this->getDoctrine()->getManager();
      $limit=20;
      $movies=[
        "titulo"=>"",
        "principio"=>false,
        "final"=>false,
        "peliculas"=>[],
        "paginadorActual"=>0,
        "paginadorTotal"=>0,
        "total"=>0,
      ];
      if ($genero!="") {
        if ($filtro!=[]) {
          // consulta de peliculas con su respectivo genero y filtros
        }else {
          // consulta de peliculas con su respectivo genero
        }
      }elseif ($busqueda!="") {
        if ($filtro!=[]) {
          // consulta de la busqueda con su respectivo filtro
        }else {
          // consulta de la busqueda
        }
      }elseif ($filtro!=[]) {
        // consulta de las peliculas en general con su respectivo filtro
      }else {
        //consulta de todas las peliculas
        $query = $em->createQuery('
              SELECT p, partial g.{id,nombre}
              FROM ciberConnect\shopBundle\Entity\peliculas p
              LEFT JOIN p.genero g
        ');
        $query->setFirstResult(($pagina-1)*20);
        $query->setMaxResults($limit);
        $queryRes= new Paginator($query, $fetchJoinCollection = true);
        $allPaginator= (int)ceil($queryRes->count()/$limit);
        $listPeliculas=$queryRes->getIterator()->getArrayCopy();
        $movies["total"]=$queryRes->count();
        $movies["paginadorActual"]=$pagina;
        $movies["paginadorTotal"]=$allPaginator;
        $movies["principio"]=true;
        $movies["final"]=false;
        $movies["titulo"]="Todas las peliculas &#128558;";
        if (!empty($listPeliculas)) {
          $movies["peliculas"]= $listPeliculas;
        }
      }
      // dump($movies);
      // die;
      return $movies;
    }

    public function getMovie($nombreNormalizado){
      $em = $this->getDoctrine()->getManager();
      $movies=[
        "pelicula"=>[],
      ];
      //consulta de todas las peliculas
      $query = $em->createQuery('
            SELECT p, partial g.{id,nombre}
            FROM ciberConnect\shopBundle\Entity\peliculas p
            LEFT JOIN p.genero g
            WHERE p.nombreNormalizado = :nombreNormalizado
      ');
      $query->setParameter("nombreNormalizado",$nombreNormalizado);
      $queryRes= new Paginator($query, $fetchJoinCollection = true);
      $pelicula=$queryRes->getIterator()->getArrayCopy();
      if (!empty($pelicula)) {
        $movie["pelicula"]= $pelicula[0];
      }
      // dump($movie);
      // die;
      return $movie;
    }
}
