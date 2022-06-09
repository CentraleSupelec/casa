<?php

namespace App\Controller\Admin;

use App\Service\MapService;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerThrowable;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class GeocodingController extends CRUDController
{
    private MapService $mapService;

    public function __construct(MapService $service)
    {
        $this->mapService = $service;
    }

    /**
     * @throws LockException
     * @throws ModelManagerThrowable
     */
    public function geocodeAction($id): Response
    {
        $object = $this->admin->getSubject();

        $coordinates = $this->mapService->getCoordinatesFromAddress($object->getAddress()->getFullAddress());

        $object->getAddress()->setCoordinates($coordinates);

        $this->admin->update($object);

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
