<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Lessor;
use App\Entity\LessorAdminUser;
use App\Service\UserService;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

final class LessorAdminUserAdmin extends AbstractAdmin
{
    private UserService $userService;

    public function __construct(
    UserService $userService, ?string $code = null, ?string $class = null, ?string $baseControllerName = null
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->userService = $userService;
    }

    public function toString($object): string
    {
        return $object instanceof LessorAdminUser ? (string) $object : 'Administrateur bailleur';
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('lessor', null, [
                'show_filter' => true,
                'label' => 'Recherche par bailleur',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('lessor')
            ->add('email', null, [
                'label' => 'Email',
            ])
            ->add('lastName')
            ->add('firstName')
            ->add('enabled', null, [
                'label' => 'Actif',
            ])
            ->add('lastLoginAt', null, [
                'label' => 'Dernière connexion',
                'pattern' => 'H:i:s -- d/m/Y',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
            ])
            ->add('createdAt', null, [
                'label' => 'Date d\'ajout',
                'pattern' => 'dd/MM/yyyy',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général')
                ->add('lessor', EntityType::class, [
                    'class' => Lessor::class,
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                ])
                ->add('firstName')
                ->add('lastName')
            ->end()

            ->with('Informations')
                ->add('createdAt', null, [
                    'widget' => 'single_text',
                    'disabled' => true,
                    'label' => 'Date création',
                    'html5' => false,
                    'format' => DateTimeType::DEFAULT_DATE_FORMAT,
                ])
                ->add('updatedAt', null, [
                    'widget' => 'single_text',
                    'disabled' => true,
                    'label' => 'Date dernière modification',
                    'html5' => false,
                    'format' => DateTimeType::DEFAULT_DATE_FORMAT,
                ])
                ->add('lastLoginAt', null, [
                    'widget' => 'single_text',
                    'disabled' => true,
                    'label' => 'Date dernière modification',
                    'html5' => false,
                    'format' => DateTimeType::DEFAULT_DATE_FORMAT,
                ])
            ->end()

            ->with('Sécurité', [
                'box_class' => 'box box-solid box-danger',
            ]);
        if ($this->isCurrentRoute('edit')) {
            $form
                ->add('plainPassword', PasswordType::class, [
                    'required' => false,
                    'label' => 'Changer de mot de passe',
                ]);
        }

        $form
                ->add('enabled', null, [
                    'required' => false,
                    'label' => 'Compte en service',
                ])
            ->end();
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('Général')
                ->add('email', null, [
                    'label' => 'Email',
                ])
                ->add('firstName')
                ->add('lastName')
            ->end()
            ->with('Informations')
                ->add('roles', null, [
                    'label' => 'Roles',
                ])
                ->add('enabled', null, [
                    'label' => 'Compte actif',
                ])
                ->add('createdAt', 'datetime', [
                    'format' => 'H:i:s -- d/m/Y',
                    'label' => 'Création',
                ])
                ->add('updatedAt', 'datetime', [
                    'format' => 'H:i:s -- d/m/Y',
                    'label' => 'Dernière mise à jour',
                ])
                ->add('lastLoginAt', 'datetime', [
                    'format' => 'H:i:s -- d/m/Y',
                    'label' => 'Dernière connexion',
                ])
            ->end()
        ;
    }

    public function preValidate($object): void
    {
        if ($this->isCurrentRoute('create')) {
            // This password is not sent to the user.
            // the user will "reset" his password on first attempt
            // therefore, it do not need to be 'valid'
            $object->setPlainPassword(md5(random_bytes(10)));
        }
    }

    public function preUpdate(object $object): void
    {
        /* @var Administrator $object */
        $this->userService->hashPassword($object);
    }

    public function prePersist(object $object): void
    {
        /* @var Administrator $object */
        $this->userService->hashPassword($object);
    }
}
