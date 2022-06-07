<?php

namespace App\Admin;

use App\Admin\Embed\AddressEmbeddedAdmin;
use App\Entity\Equipment;
use App\Entity\HousingGroup;
use App\Entity\Lessor;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HousingGroupAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('geocode', $this->getRouterIdParameter().'/geocode');
    }

    protected function configureTabMenu(MenuItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild('View', $admin->generateMenuUrl('show', ['id' => $id]));

        if ($this->isGranted('EDIT')) {
            $menu->addChild('Edit', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Services', $admin->generateMenuUrl('admin.housing_group_service.list', ['id' => $id]));
            $menu->addChild('Logements', $admin->generateMenuUrl('admin.housing.list', ['id' => $id]));
            $menu->addChild('POI', $admin->generateMenuUrl('admin.point_of_interest.list', ['id' => $id]));
        }
    }

    public function toString(object $object): string
    {
        return $object instanceof HousingGroup ? (string) $object : 'Groupe de logements';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('name', TextType::class, [
                    'label' => 'Nom du groupe',
                ])
                ->add('lessor', ModelType::class, [
                    'label' => 'Bailleur',
                    'class' => Lessor::class,
                ])
            ->end()
            ->with('Adresse', [
                'class' => 'col-md-8 col-md-offset-2',
            ]);

        AddressEmbeddedAdmin::addFormField($form);

        $form
            ->end()
            ->with('Équipements', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('equipments', ModelType::class, [
                    'class' => Equipment::class,
                    'multiple' => true,
                    'label' => 'Équipements des logements',
                    'btn_add' => false,
                ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('name', null, [
            'show_filter' => true,
            'label' => 'Recherche par nom',
        ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('name', null, [
                'label' => 'Nom du groupe de logements',
            ])
            ->add('lessor.name', null, [
                'label' => 'Nom du bailleur',
            ])
            ->add('address.coordinates')
            ->add('createdAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Création',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Dernière mise à jour',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'geocode' => [
                        'template' => 'admin\_list_action_geocode.html.twig',
                    ],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('name', null, [
                'Nom du groupe de logements',
            ])
            ->add('lessor.name', null, [
                'Nom du bailleur',
            ]);

        AddressEmbeddedAdmin::addShowField($show);

        $show
            ->add('equipments', null, [
                'label' => 'Équipements',
            ])
            ->add('pointsOfInterest', null, [
                'label' => 'Points d\'intérêt',
            ])
            ->add('createdAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Création',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Dernière mise à jour',
            ]);
    }
}
