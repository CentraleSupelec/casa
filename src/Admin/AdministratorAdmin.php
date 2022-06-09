<?php

namespace App\Admin;

use App\Entity\Administrator;
use App\Service\UserService;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdministratorAdmin extends AbstractAdmin
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
        return $object instanceof Administrator ? (string) $object : 'Admin';
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('email', null, [
                'label' => 'Email',
            ])
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
                ->add('id', null, ['disabled' => true])
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                ])
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
            ])
                ->add('plainPassword', PasswordType::class, [
                    'required' => false,
                    'label' => 'Changer de mot de passe',
                ])
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
