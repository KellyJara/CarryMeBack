<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable ;
use App\Entity\User;
use AppBundle\Entity\Reward;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
 public function __construct (
  private EntityManagerInterface $manager,
  private SerializerInterface $serializer,
  private UserPasswordHasherInterface $passwordHasher,
  )  {
     
    }
    /**
     * Regustrate an user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     *@Route("/registration", methods={"POST"})
     *@OA\Parameter(
     *     email="toto@gmail.com",
     *     password="MyPassword",
     * @OA\Response(
     *     response=200,
     *     description="Registration of an user,
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     * )
    
     * )
     * @OA\Tag(name="user")
     */

      
    #[Route('/registration', name: 'registration', methods: 'POST')]
    public function register(Request $request): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse([
          'user'  => $user->getUserIdentifier(), 
          'apiToken' => $user->getApiToken(), 
          'roles' => $user->getRoles()],
            Response::HTTP_CREATED
        );
    }
    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user'  => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }
}

