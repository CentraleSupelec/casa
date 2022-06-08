<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Embed\AddressEmbeddedAdmin;
use App\Constants;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class SchoolAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('geocode', $this->getRouterIdParameter().'/geocode');
    }

    protected function configureTabMenu(
        MenuItemInterface $menu, string $action, ?AdminInterface $childAdmin = null
    ): void {
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
            $menu->addChild(
                'Gestion des urgences',
                $admin->generateMenuUrl('admin.school_emergency_qualification_question.list', ['id' => $id])
            );
        }
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name', null, [
                'label' => 'Nom',
            ])
            ->add('parentSchool', null, [
                'label' => 'Etablissement principal', ])
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('name', null, [
                'label' => 'Nom',
                ])
            ->add('parentSchool', null, [
                'label' => 'Etablissement principal',
            ])
            ->add('idGovernment', null, [
                'label' => 'Identifiant National',
                ])
            ->add('campus', null, [
                'label' => 'Campus',
                ])
            ->add('websiteUrl', null, [
                'label' => 'Adresse Internet',
            ])
            ->add('address.coordinates', null, [
                'label' => 'Coordonnées',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'geocode' => [
                        'template' => 'admin\_list_action_geocode.html.twig',
                    ],
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
           ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('name', TextType::class, [
                    'label' => 'Nom',
                ])
                ->add('parentSchool', ModelType::class, [
                    'label' => 'Etablissement Principal',
                     'btn_add' => false,
                ])
                ->add('idGovernment', TextType::class, [
                    'label' => 'Identifiant National',
                    'required' => false,
                ])
                ->add('acronym', TextType::class, [
                    'label' => 'Sigle',
                    'required' => false,
                ])
                ->add('campus', ChoiceType::class, [
                    'label' => 'Campus',
                    'choices' => Constants::getCampus(),
                ])
                ->add('housingServiceEmail', EmailType::class, [
                    'label' => 'Email de contact du service logement',
                ])
                ->add('websiteurl', UrlType::class, [
                    'label' => 'Adresse Internet',
                    'required' => false,
                ])
            ->end()
            ->with('Adresse', [
                'class' => 'col-md-8 col-md-offset-2',
            ]);

        AddressEmbeddedAdmin::addFormField($form);

        $form
            ->end()
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('name', null, [
                    'label' => 'Nom',
                ])
            ->add('parentSchool', null, [
                'label' => 'Etablissement principal',
                'required' => true,
            ])
            ->add('idGovernment', null, [
                    'label' => 'Identifiant National',
                    'required' => false,
                ])
            ->add('acronym', null, [
                'label' => 'Sigle',
                'required' => false,
            ])
            ->add('campus', null, [
                'label' => 'Campus',
            ])
            ->add('housingServiceEmail', null, [
                'label' => 'Email de contact du service logement',
            ])
            ->add('websiteurl', null, [
                    'label' => 'Adresse Internet',
                    'required' => false,
            ]);

        AddressEmbeddedAdmin::addShowField($show);

        $show
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
