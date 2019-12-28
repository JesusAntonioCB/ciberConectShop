<?php
namespace ciberConnect\shopBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ciberConnect\shopBundle\Entity\peliculas;
class migratePelisCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('migrate:peliculas')
      ->setDescription('Toma las peliculas de una carpeta y las sube a la BD')
      ->setDescription('Sube las peliculas de una carpeta al bucket configurado en Amazon S3');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $direction = dirname(__FILE__).'/../Resources/public/prueba';
    $dir = new \DirectoryIterator($direction);
    $em = $this->getContainer()->get('doctrine')->getManager();
    $pelis = 0;
    foreach ($dir as $key => $firstFolder) {
      if (!$firstFolder->isDot()) {
        if ($firstFolder->isDir()) {
          $secondaryDir= new \DirectoryIterator($firstFolder->getPathname());
          foreach ($secondaryDir as $key2 => $secondaryFolder) {
            if (!$secondaryFolder->isDot()) {
              if ($secondaryFolder->isDir()) {
                if (substr($secondaryFolder->getBasename(), 0, 2) === "- " or substr($secondaryFolder->getBasename(), 0, 2) === "+ "){
                  $initText=substr($secondaryFolder->getBasename(), 0, 2);
                  $thirdDir= new \DirectoryIterator($secondaryFolder->getPathname());
                  foreach ($thirdDir as $key3 => $thirdFolder) {
                    if (!$thirdFolder->isDot()) {
                      if ($thirdFolder->isDir()) {
                        if (substr($thirdFolder->getBasename(), 0, 2) === $initText) {
                          //sub carpeta dentro de la carpeta
                          $output->writeln('--subSubGenero '.str_replace($initText, "", $thirdFolder->getBasename()).' Dentro del subGenero '.$secondaryFolder->getBasename().' Dentro del Genero '.$firstFolder->getBasename().':');
                        }else {
                          if (substr($thirdFolder->getBasename(), 0, 1) !== ".") {
                            $parte1=explode('(',$thirdFolder->getBasename());
                            $parte2=explode(')',$parte1[1]);
                            $nombreEsp= $parte2[1];
                            $año= $parte2[0];
                            $PeliculaDir= new \DirectoryIterator($thirdFolder->getPathname());
                            $pelicula= new peliculas();
                            foreach ($PeliculaDir as $key3 => $peliculasFolder) {
                              if (!$peliculasFolder->isDot()) {
                                if (!$peliculasFolder->isDir()) {
                                  if (substr($peliculasFolder->getBasename(), 0, 1) !== ".") {
                                    if ($peliculasFolder->getExtension()==="mp4"||$peliculasFolder->getExtension()==="avi"||$peliculasFolder->getExtension()==="mkv"
                                      ||$peliculasFolder->getExtension()==="flv"||$peliculasFolder->getExtension()==="mov"||$peliculasFolder->getExtension()==="wmv") {
                                        if (substr($peliculasFolder->getBasename(), 0, 7) == "trailer") {
                                          $destinoVideo= __DIR__."/../Resources/public/trailers/";
                                          $vidOrigen= $peliculasFolder->getPathname();
                                          $vidName= $peliculasFolder->getBasename();
                                          copy($vidOrigen,$destinoVideo.$vidName);
                                          $pelicula->setVideo($vidName);
                                          $output->writeln([
                                            "----trailer: ".$vidName
                                          ]);
                                        }else {
                                          $infoPeli=explode('-',$peliculasFolder->getBasename());
                                          $infocalidad=explode('.',$infoPeli[2]);
                                          $querygenero = $em->createQuery('
                                            SELECT g
                                            FROM ciberConnect\shopBundle\Entity\listGeneroPelicula g
                                            ');
                                          $genRes=$querygenero->getArrayResult();
                                          foreach ($genRes as $value) {
                                            $value["nombre"];
                                            // $output->writeln("------------------------------".$value["nombre"]);
                                            if ($firstFolder->getBasename()==$value["nombre"]) {
                                              $output->writeln("------------------------------ Genero".$value["nombre"]);
                                              $valueGenero1= $this->getContainer()->get('doctrine')
                                                                   ->getRepository('ciberConnect\shopBundle\Entity\listGeneroPelicula')
                                                                   ->find((int)$value['id']);
                                            }
                                            if (str_replace($initText, "", $secondaryFolder->getBasename())==$value["nombre"]) {
                                              $output->writeln("------------------------------ sub genero:".$value["nombre"]);
                                              $valueGenero2= $this->getContainer()->get('doctrine')
                                                                   ->getRepository('ciberConnect\shopBundle\Entity\listGeneroPelicula')
                                                                   ->find((int)$value['id']);
                                            }
                                          }

                                          $pelicula->setNombreEsp($nombreEsp);
                                          $pelicula->setNombreOrigen($infoPeli[0]);
                                          $pelicula->setYear($año);
                                          $pelicula->setAudio($infoPeli[1]);
                                          $pelicula->setCalidad($infocalidad[0]);
                                          $pelicula->setFormato($peliculasFolder->getExtension());
                                          $pelicula->addGenero($valueGenero1);
                                          $pelicula->setDescripcion("");
                                          $pelicula->setPuntaje(0);
                                          $pelicula->setCheckbox(0);
                                          $pelicula->setPrecio(10);
                                          if ($valueGenero2!="") {
                                            $pelicula->addGenero($valueGenero2);
                                          }
                                          $output->writeln([
                                            $pelis.".-".$nombreEsp,
                                            "----nombre en ingles: ".$infoPeli[0],
                                            "----año: ".$año,
                                            "----idioma: ".$infoPeli[1],
                                            "----calidad: ".$infocalidad[0],
                                            "----formato: ".$peliculasFolder->getExtension(),
                                            "----Genero: ".$firstFolder->getBasename(),
                                            "----SubGenero: ".str_replace($initText, "", $secondaryFolder->getBasename())
                                          ]);
                                          $pelis++;
                                        }
                                    }elseif ($peliculasFolder->getExtension()==="gif"||$peliculasFolder->getExtension()==="jpg"
                                            ||$peliculasFolder->getExtension()==="jpeg"||$peliculasFolder->getExtension()==="png"){
                                            $destino= __DIR__."/../Resources/public/imagenes/";
                                            $imgOrigen= $peliculasFolder->getPathname();
                                            $imgName= $peliculasFolder->getBasename();
                                            $pelicula->setImagen($imgName);
                                            copy($imgOrigen,$destino.$imgName);
                                            $output->writeln([
                                              "----imagen: ".$imgName
                                            ]);
                                    }
                                    $valueGenero2="";
                                    $em->persist($pelicula);
                                  }
                                }else {
                                  //si ya dentro de la carpeta de la pelicula es hay una carpeta
                                  $output->writeln("carpeta dentro de la pelicula");
                                }
                              }
                            }
                            $em->persist($pelicula);
                          }
                        }
                      }else {
                        //hay un achivoaqui en ves de carpetas
                      }
                    }
                  }
                }else {
                  if (substr($secondaryFolder->getBasename(), 0, 1) !== ".") {
                    $parte1=explode('(',$secondaryFolder->getBasename());
                    $parte2=explode(')',$parte1[1]);
                    $nombreEsp= $parte2[1];
                    $año= $parte2[0];
                    $PeliculaDir= new \DirectoryIterator($secondaryFolder->getPathname());
                    $pelicula= new peliculas();
                    foreach ($PeliculaDir as $key3 => $peliculasFolder) {
                      if (!$peliculasFolder->isDot()) {
                        if (!$peliculasFolder->isDir()) {
                          if (substr($peliculasFolder->getBasename(), 0, 1) !== ".") {
                            if ($peliculasFolder->getExtension()==="mp4"||$peliculasFolder->getExtension()==="avi"||$peliculasFolder->getExtension()==="mkv"
                              ||$peliculasFolder->getExtension()==="flv"||$peliculasFolder->getExtension()==="mov"||$peliculasFolder->getExtension()==="wmv") {
                                if (substr($peliculasFolder->getBasename(), 0, 7) == "trailer") {
                                  $destinoVideo= __DIR__."/../Resources/public/trailers/";
                                  $vidOrigen= $peliculasFolder->getPathname();
                                  $vidName= $peliculasFolder->getBasename();
                                  $pelicula->setVideo($vidName);
                                  copy($vidOrigen,$destinoVideo.$vidName);
                                  $output->writeln([
                                    "----trailer: ".$vidName
                                  ]);
                                }else {
                                  $infoPeli=explode('-',$peliculasFolder->getBasename());
                                  $infocalidad=explode('.',$infoPeli[2]);
                                  $querygenero = $em->createQuery('
                                    SELECT g
                                    FROM ciberConnect\shopBundle\Entity\listGeneroPelicula g
                                    ');
                                  $genRes=$querygenero->getArrayResult();
                                  foreach ($genRes as $value) {
                                    $value["nombre"];
                                    $generoValue= str_replace(" ","",$value["nombre"]);
                                    $generoActual= str_replace(" ","",$firstFolder->getBasename());
                                    if ($generoActual==$generoValue) {
                                      $valueGenero1= $this->getContainer()->get('doctrine')
                                                           ->getRepository('ciberConnect\shopBundle\Entity\listGeneroPelicula')
                                                           ->find((int)$value['id']);
                                    }
                                  }
                                  $pelicula->setNombreEsp($nombreEsp);
                                  $pelicula->setNombreOrigen($infoPeli[0]);
                                  $pelicula->setYear($año);
                                  $pelicula->setAudio($infoPeli[1]);
                                  $pelicula->setCalidad($infocalidad[0]);
                                  $pelicula->setFormato($peliculasFolder->getExtension());
                                  $pelicula->addGenero($valueGenero1);
                                  $pelicula->setDescripcion("");
                                  $pelicula->setPuntaje(10);
                                  $pelicula->setCheckbox(0);
                                  $pelicula->setPrecio(10);
                                  $output->writeln([
                                    $pelis.".-".$nombreEsp,
                                    "----nombre en ingles: ".$infoPeli[0],
                                    "----año: ".$año,
                                    "----idioma: ".$infoPeli[1],
                                    "----calidad: ".$infocalidad[0],
                                    "----formato: ".$peliculasFolder->getExtension(),
                                    "----Genero: ".$firstFolder->getBasename()
                                  ]);
                                  $pelis++;
                                }
                            }elseif ($peliculasFolder->getExtension()==="gif"||$peliculasFolder->getExtension()==="jpg"
                                    ||$peliculasFolder->getExtension()==="jpeg"||$peliculasFolder->getExtension()==="png"){
                                      $destino= __DIR__."/../Resources/public/imagenes/";
                                      $imgOrigen= $peliculasFolder->getPathname();
                                      $imgName= $peliculasFolder->getBasename();
                                      $pelicula->setImagen($imgName);
                                      copy($imgOrigen,$destino.$imgName);
                                      $output->writeln([
                                        "----imagen: ".$imgName
                                      ]);
                            }
                          }
                        }else {
                          //si ya dentro de la carpeta de la pelicula es hay una carpeta
                          $output->writeln("carpeta dentro de la pelicula");
                        }
                      }
                    }
                    $em->persist($pelicula);
                  }
                }
              }else {
                //hay un archivo en la segunda carpeta
              }
            }
          }
        }else {
          // $output->writeln('***no son carpetas***');
          // if (substr($firstFolder->getBasename(), 0, 1) !== ".") {
          //   $parte1=explode('(',$firstFolder->getBasename());
          //   $parte2=explode(')',$parte1[1]);
          //   $nombreEsp= $parte2[1];
          //   $año= $parte2[0];
          //   $output->writeln([
          //     $pelis.".-".$nombreEsp,
          //     "-año: ".$año,
          //   ]);-
          //   $pelis++;
          // }
        }
      }
    }$em->flush();
  }
}
