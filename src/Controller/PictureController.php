<?php

namespace App\Controller;

use App\Dto\PictureInput;
use App\Entity\Album;
use App\Entity\Location;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Trip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
final class PictureController extends AbstractController
{
    #[Route('/api/pictures', name: 'picture_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        try {
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

            return $this->json($picture, 201, [], ['groups' => 'picture:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/pictures/{id}', name: 'picture_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var PictureInput $pictureInput */
            $pictureInput = $serializer->deserialize($data, PictureInput::class, 'json');

            /** @var Picture $picture */
            $picture = $entityManager->getRepository(Picture::class)->find($id);

            if ($picture == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Picture " . $id . " not found"
                ], 400);
            }

            /** @var Album $album */
            $album = $entityManager->getRepository(Album::class)->find($pictureInput->getAlbum());

            $album?->addAlbumElement($picture);

            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $picture);
            $entityManager->persist($picture);
            $entityManager->flush();

            return $this->json($picture, 201, [], ['groups' => 'picture:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
