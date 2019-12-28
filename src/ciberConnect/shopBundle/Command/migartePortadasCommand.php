<?php
namespace ciberConnect\shopBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ciberConnect\shopBundle\Entity\peliculas;
class migartePortadasCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('migrate:portadas')
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
                                          // copy($vidOrigen,$destinoVideo.$vidName);
                                          // $output->writeln([
                                          //   "----trailer: ".$vidName,
                                          //   "----origen del trailer: ".$vidOrigen
                                          // ]);
                                        }
                                    }elseif ($peliculasFolder->getExtension()==="gif"||$peliculasFolder->getExtension()==="jpg"
                                            ||$peliculasFolder->getExtension()==="jpeg"||$peliculasFolder->getExtension()==="png"){
                                            $destino= __DIR__."/../Resources/public/imagenes/";
                                            $imgOrigen= $peliculasFolder->getPathname();
                                            $imgName= $peliculasFolder->getBasename();
                                            copy($imgOrigen,$destino.$imgName);
                                            $output->writeln([
                                              "----imagen: ".$imgName,
                                              // "----origen de la imagen: ".$imgOrigen,
                                              // "----origen de la imagen: ".$destino.$imgName,
                                            ]);
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
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
                                  // copy($vidOrigen,$destinoVideo.$vidName);
                                  // $output->writeln([
                                  //   "----trailer: ".$vidName,
                                  //   "----origen del trailer: ".$vidOrigen
                                  // ]);
                                }
                            }elseif ($peliculasFolder->getExtension()==="gif"||$peliculasFolder->getExtension()==="jpg"
                                    ||$peliculasFolder->getExtension()==="jpeg"||$peliculasFolder->getExtension()==="png"){
                                      $destino= __DIR__."/../Resources/public/imagenes/";
                                      $imgOrigen= $peliculasFolder->getPathname();
                                      $imgName= $peliculasFolder->getBasename();
                                      copy($imgOrigen,$destino.$imgName);
                                      $output->writeln([
                                        "----imagen: ".$imgName,
                                        // "----origen de la imagen: ".$imgOrigen,
                                        // "----origen de la imagen: ".$destino.$imgName,
                                      ]);
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
