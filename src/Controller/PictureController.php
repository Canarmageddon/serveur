<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Trip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManagerInterface;

#[AsController]
final class PictureController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Picture
    {
        $creatorId = $request->request->get('creator');
        $tripId = $request->request->get('trip');

        /** @var User $creator */
        $creator = $entityManager->getRepository(User::class)->find($creatorId);

        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($tripId);

        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $picture = new Picture();
        $picture->file = $uploadedFile;

        $creator?->addAlbumElement($picture);
        $trip?->addAlbumElement($picture);

        $entityManager->persist($picture);
        $entityManager->flush();

        return $picture;
    }
}
