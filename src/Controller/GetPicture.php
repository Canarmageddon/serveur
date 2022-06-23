<?php

namespace App\Controller;

use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PictureRepository;

#[AsController]
final class GetPicture extends AbstractController
{

    public function __construct(PictureRepository $pictureRepository)
    {
        $this->pictureRepository = $pictureRepository;
    }

    public function __invoke(string $id): BinaryFileResponse
    {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $base = $projectRoot.'/public/images/trips/';
        
        $fileName = $this->pictureRepository->find($id)->getFilePath();

        $file = $base.$fileName;

        $response = new BinaryFileResponse($file);
        return $response;

    }

}

?>