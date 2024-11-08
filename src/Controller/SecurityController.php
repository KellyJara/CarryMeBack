<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable ;
use App\Entity\User;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
 public function __construct (private EntityManagerInterface $manager,SerializerInterface $serializer)  
    {

    }

    #[Route('/registration', name: 'registration', methods: 'POST')]
    public function register(Request $Request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTimeImmutable());
    
        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse(
        ['user'  => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
        Response::HTTP_CREATED
      );
    }
}
