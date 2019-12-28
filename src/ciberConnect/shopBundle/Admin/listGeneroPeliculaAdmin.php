<?php
namespace ciberConnect\shopBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;

class listGeneroPeliculaAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add("nombre", TextType::class, [
              "label" => "Nombre"
            ])
        ->add("tipo", ChoiceFieldMaskType::class, [
              "label" => "Tipo de apartado",
              'choices' => [
                  'Convencionales' => '1',
                  'Otros' => '2',
              ],
            ])
        ->add("descripcion", TextareaType::class, [
              "label" => "Descripción"
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add("nombre",null,["label"=>"Nombre"])
        ->add("tipo",null,["label"=>"Tipo"])
        ->add("descripcion",null,["label"=>"Descripciónn"]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
      $listMapper
      ->addIdentifier("nombre",TextType::class,["label" => "Nombre"])
      ->add("tipo",TextType::class,["label" => "Tipo"])
      ->add("descripcion",TextType::class,["label" => "Descripción"]);
    }

}
?>
