<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Location;
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
        $albumId = $request->request->get('album');
        $uploadedFile = $request->files->get('file');
        $locationId = $request->request->get('location');
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');

        /** @var User $creator */
        $creator = $entityManager->getRepository(User::class)->find($creatorId);

        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($tripId);

        /** @var Album $album */
        $album = $entityManager->getRepository(Album::class)->find($albumId);

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if ($locationId != null) {
            /** @var Location $location */
            $location = $entityManager->getRepository(Location::class)->find($locationId);
        } else if ($latitude != null && $longitude != null) {
            $location = new Location();
            $location->setLongitude($longitude);
            $location->setLatitude($latitude);
            $entityManager->persist($location);
        } else {
            $location = null;
        }

        $picture = new Picture();


        $creator?->addAlbumElement($picture);
        $trip?->addAlbumElement($picture);
        $album?->addAlbumElement($picture);
        $location?->addAlbumElement($picture);

        //Access control
        $this->denyAccessUnlessGranted('TRIP_EDIT', $picture);
        $picture->file = $uploadedFile;
        $entityManager->persist($picture);
        $entityManager->flush();

        return $picture;
    }
}
