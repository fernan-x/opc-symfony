<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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

    #[Route('/api/authors', name: 'createAuthor', methods: ['POST'])]
    public function createAuthor(Request $request, SerializerInterface $serializer,
                                 EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $author = $serializer->deserialize($request->getContent(), Author::class, 'json');
        $em->persist($author);
        $em->flush();

        $jsonAuthor = $serializer->serialize($author, 'json', ['groups' => 'getAuthor']);

        $url = $urlGenerator->generate('detailAuthor', ['id' => $author->getId()], UrlGeneratorInterface::ABSOLUTE_PATH);

        return new JsonResponse($jsonAuthor, Response::HTTP_CREATED, ['Location' => $url]);
    }

    #[Route('/api/authors/{id}', name: 'updateAuthor', methods: ['PUT'])]
    public function updateAuthor(Request $request, SerializerInterface $serializer,
                                 EntityManagerInterface $em, Author $currentAuthor, AuthorRepository $authorRepository): JsonResponse
    {
        $updatedBook = $serializer->deserialize(
            $request->getContent(),
            Author::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentAuthor]
        );

        $em->persist($updatedBook);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/authors/{id}', name: 'deleteAuthor', methods: ['DELETE'])]
    public function deleteAuthor(Author $author, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($author);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
