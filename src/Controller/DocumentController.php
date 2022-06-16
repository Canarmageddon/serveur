<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\User;
use App\Entity\MapElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class DocumentController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Document
    {
        $creatorId = $request->request->get('creator');
        $mapElementId = $request->request->get('mapElement');
        $name = $request->request->get('name');

        /** @var User $creator */
        $creator = $entityManager->getRepository(User::class)->find($creatorId);

        /** @var mapElement $mapElement */
        $mapElement = $entityManager->getRepository(MapElement::class)->find($mapElementId);


        

        
        
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $document = new Document($creator, $mapElement, $name);
        //Access control
        $this->denyAccessUnlessGranted('TRIP_EDIT', $document);
        $document->file = $uploadedFile;

        return $document;
    }
}