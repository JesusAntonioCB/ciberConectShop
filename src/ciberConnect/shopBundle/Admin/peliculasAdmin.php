<?php
namespace ciberConnect\shopBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Form\Type\ModelType;

class peliculasAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $fileFieldOptions = ['required' => false];
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath();
        if($this->getSubject() != null){
          $fullPath = "/bundles/ciberconnectshop/imagenes/".$this->getSubject()->getImagen();
        }
        else{
          $fullPath = "/bundles/ciberconnectshop/imagenes/";
        }
        $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';

        $fileTrailer = ['required' => false];
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath();
        $mp4= false;
        $webm= false;
        $ogg= false;
        if($this->getSubject() != null){
          $fullPath = "/bundles/ciberconnectshop/trailers/".$this->getSubject()->getVideo();
          if (!empty($this->getSubject()->getVideo())) {
            $tituloPartes= explode(".",$this->getSubject()->getVideo())[1];
          }else {
            $tituloPartes="mp4";
          }
        }
        else{
          $fullPath = "/bundles/ciberconnectshop/trailers/";
        }
        $fileTrailer['help'] ='
          <video width="320" height="240" controls>
            <source src="'.$fullPath.'" type="video/'.$tituloPartes.'">
            Your browser does not support the video element.
          </video>
        ';

        $formMapper
        ->add("nombreEsp", TextType::class, [
              "label" => "Nombre en español"
            ])
        ->add("nombreOrigen", TextType::class, [
              "label" => "Nombre en ingles"
            ])
        ->add("descripcion", TextareaType::class, [
              "label" => "Descripción",
              'attr' => ["maxlength" => "2000"],
              'required'  => false
            ])
        ->add("genero", null, [
              "label" => 'Genero',
            ])
        ->add("year", NumberType::class, [
              "label" => "Año"
            ])
        ->add("imagen", "file", [
              "data_class"   =>  NULL,
              "label" => "Imagen de Portada",
              "required" => false,
              "empty_data" => null
            ],$fileFieldOptions)
        ->add("video", "file", [
              "data_class"   =>  NULL,
              "label" => "Trailer",
              "required" => false,
              "empty_data" => null
            ],$fileTrailer)
        ->add("puntaje", TextType::class, [
              "label" => "Puntaje",
              'required'  => false
            ])
        ->add("precio", MoneyType::class, [
              "label" => "Precio",
              "currency" => "MXN",
              'required'  => false
            ])
        ->add("calidad", TextType::class, [
              "label" => "Calidad"
            ])
        ->add("audio", TextType::class, [
              "label" => "Idioma(s)"
            ])
        ->add("formato", ChoiceFieldMaskType::class, [
              "label" => "Formato de la pelicula",
              'choices' => [
                  'MP4' => 'mp4',
                  'AVI' => 'avi',
                  'MKV' => 'mkv',
                  'FLV' => 'flv',
                  'MOV' => 'mov',
                  'WMV' => 'wmv'
              ],
            ])
        ->add("checkbox", CheckboxType::class, [
              "label" => "¿Completado?",
              'required'  => false
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add("nombreEsp",null,["label"=>"Nombre"])
        ->add("descripcion",null,["label"=>"Descripciónn",'row_align' => "hola"])
        ->add("genero",null,["label"=>"Genero"])
        ->add("year",null,["label"=>"Genero"])
        ->add('checkbox',null,["label"=>"Completado"]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
      $listMapper
      ->addIdentifier("nombreEsp",TextType::class,["label" => "Nombre"])
      ->add("getShortDescripcion",TextType::class,["label" => "Descripciónn"])
      ->add("genero",TextType::class,["label" => "Genero"])
      ->add("year",TextType::class,["label" => "Genero"])
      ->add('checkbox','boolean',array("label" => "¿Completado?"));
    }

    public function preValidate($object)
    {
      $normalizeName= $this->normalizeText($object->getNombreOrigen());
      $slug="/Peliculas/".$normalizeName;
      $object->setNombreNormalizado($normalizeName);
      $object->setSlug($slug);
      return $object;
    }

    public function prePersist($object){
      $destinoImagen= __DIR__."/../Resources/public/imagenes/";
      $destinoVideo= __DIR__."/../Resources/public/trailers/";
      if (!is_null($object->getImagen())) {
        if (!is_null($object->getImagen())) {
          $imgOrigen= $object->getImagen()->getrealPath();
          $imgName= $object->getImagen()->getClientOriginalName();
          move_uploaded_file($imgOrigen,$destinoImagen.$imgName);
          $object->setImagen($imgName);
        }
      }
      if (!is_null($object->getVideo())) {
        if (!is_null($object->getVideo())) {
          $vidOrigen= $object->getVideo()->getrealPath();
          $vidName= $object->getVideo()->getClientOriginalName();
          move_uploaded_file($vidOrigen,$destinoVideo.$vidName);
          $object->setVideo($vidName);
        }
      }
      return $object;
    }

    public function preUpdate($object){
      $container = $this->getConfigurationPool()->getContainer();
      $em = $container->get('doctrine.orm.entity_manager');
      if (!is_null($object->getImagen())) {
        $destino= __DIR__."/../Resources/public/imagenes/";
        $imagen= $object->getImagen();
        $imgOrigen= $imagen->getrealPath();
        $imgName= $imagen->getClientOriginalName();
        move_uploaded_file($imgOrigen,$destino.$imgName);
        $object->setImagen($imgName);
      }else {
        $id =$object->getId();
        $queryimg = $em->createQuery('
          SELECT p
          FROM ciberConnect\shopBundle\Entity\peliculas p
          WHERE p.id = :id');
        $queryimg->setParameter('id', $id);
        $imgRes=$queryimg->getArrayResult();
        $imgName=$imgRes[0]['imagen'];
        $object->setImagen($imgName);
      }
      if (!is_null($object->getVideo())) {
        $destino= __DIR__."/../Resources/public/trailers/";
        $video= $object->getVideo();
        $vidOrigen= $video->getrealPath();
        $vidName= $video->getClientOriginalName();
        move_uploaded_file($vidOrigen,$destino.$vidName);
        $object->setVideo($vidName);
      }else {
        $id =$object->getId();
        $queryVid = $em->createQuery('
          SELECT p
          FROM ciberConnect\shopBundle\Entity\peliculas p
          WHERE p.id = :id');
        $queryVid->setParameter('id', $id);
        $vidRes=$queryVid->getArrayResult();
        $vidName=$vidRes[0]['video'];
        $object->setVideo($vidName);
      }
      return $object;
    }

    function normalizeText($string){
      $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
      );
      $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
      );
      $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
      );
      $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
      );
      $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
      );
      $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
      );
      $string = str_replace(
        " ",
        "-",
        $string
      );
      $string = str_replace(
        array("\\", "¨", "º", "~", "#",
              "@", "|", "!", "\"", "·",
              "$", "%", "&", "/", "(",
              ")", "?", "'", "¡", "¿",
              "[", "^", "<code>", "]",
              "+", "}", "{", "¨", "´",
              ">", "< ", ";", ",", ":",
              "."),
        '',
        $string
      );
      $string = strtolower($string);
      return $string;
    }
}
?>
