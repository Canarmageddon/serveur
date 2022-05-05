<?php

namespace App\Controller;


use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Album;
use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


#[AsController]
final class PictureController extends AbstractController
{


    public function __invoke(Request $request, EntityManagerInterface $entityManager): Picture
    {
        $creatorId = $request->request->get('creator');
        $locationId = $request->request->get('location');
        $albumId = $request->request->get('album');

        /** @var User $creator */
        $creator = $entityManager->getRepository(User::class)->find($creatorId);

        /** @var Location $location */
        $location = $entityManager->getRepository(Location::class)->find($locationId);

        /** @var Album $creator */
        $album = $entityManager->getRepository(Album::class)->find($albumId);

        

        
        
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $picture = new Picture($creator, $location, $album);
        $picture->file = $uploadedFile;

        return $picture;
    }

    
}
