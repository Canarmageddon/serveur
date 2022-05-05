<?php

namespace App\Controller;


use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
final class PictureController extends AbstractController
{


    public function __invoke(Request $request): Picture
    {
        $creator = $request->request->get('creator');
        $location = $request->request->get('location');
        $album = $request->request->get('album');
        
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $picture = new Picture($creator, $location, $album);
        $picture->file = $uploadedFile;

        return $picture;
    }

    
}
