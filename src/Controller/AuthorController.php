<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorController extends AbstractController
{
    /**
     * Get an author based on its id
     *
     * @param   Author                  $author         Author found with id given
     * @param   SerializerInterface     $serializer     Data serializer
     * @return  JsonResponse                            Response
     */
    #[Route('/api/authors/{id}', name: 'detailAuthor', methods: ['GET'])]
    public function getAuthor(Author $author, SerializerInterface $serializer): JsonResponse
    {
        $jsonAuthor = $serializer->serialize($author, 'json', ['groups' => 'getAuthor']);
        return new JsonResponse($jsonAuthor, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * List all authors
     *
     * @param   AuthorRepository        $authorRepository           Authors repository
     * @param   SerializerInterface     $serializer                 Data serializer
     * @return  JsonResponse                                        Response
     */
    #[Route('/api/authors', name: 'listAuthor', methods: ['GET'])]
    public function getAllAuthor(AuthorRepository $authorRepository, SerializerInterface $serializer): JsonResponse
    {
        $authorList = $authorRepository->findAll();
        $jsonAuthorList = $serializer->serialize($authorList, 'json', ['groups' => 'getAuthor']);

        return new JsonResponse($jsonAuthorList, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
