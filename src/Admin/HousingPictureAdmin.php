<?php

namespace App\Admin;

use App\Entity\HousingPicture;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HousingPictureAdmin extends AbstractAdmin
{
    private string $pictureBaseUrl;

    public function __construct(string $pictureBaseUrl)
    {
        parent::__construct();

        $this->pictureBaseUrl = $pictureBaseUrl;
    }

    public function toString(object $object): string
    {
        return $object instanceof HousingPicture ? (string) $object : 'Photo de logement';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
            ->add('label', TextType::class, [
                'label' => 'Titre de la photo',
                'required' => false,
            ])
            ->add('housing', ModelType::class, [
                'label' => 'Logement concerné',
            ])
            ->add('file', VichImageType::class, [
                'label' => 'Photo du logement',
                'row_attr' => [
                    'class' => 'admin-housing-picture-image-form-field',
                ],
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('label', null, [
            'label' => 'Recherche par titre',
        ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('label', null, [
                'label' => 'Titre de la photo',
            ])
            ->add('housing.housingGroup', null, [
                'label' => 'Groupe de logements',
            ])
            ->add('housing', null, [
                'label' => 'Logement',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $image = $this->getSubject();

        $imageUrl = sprintf('%s/%s', $this->pictureBaseUrl, $image->getPicture());

        $show
            ->add('label', null, [
                'label' => 'Titre de la photo',
            ])
            ->add('housing.housingGroup.name', null, [
                'label' => 'Résidence',
            ])
            ->add('housing.type', null, [
                'label' => 'Type de logement',
            ])
            ->add('file', 'file', [
                'label' => 'Photo',
                'template' => 'sonata/_image_show_template.html.twig',
                'imageUrl' => $imageUrl,
            ])
            ->add('createdAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Création',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Dernière mise à jour',
            ])
        ;
    }
}
