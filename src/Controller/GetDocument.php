<?php

namespace App\Controller;

use App\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DocumentRepository;

#[AsController]
final class GetDocument extends AbstractController
{

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    public function __invoke(string $id): BinaryFileResponse
    {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $base = $projectRoot.'/public/documents/trips/';
        
        $fileName = $this->documentRepository->find($id)->getFilePath();

        $file = $base.$fileName;

        $response = new BinaryFileResponse($file);
        return $response;

    }

}