<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\DeliveryTour;
use App\Repository\DeliveryTourRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use DateTimeImmutable ;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


#[Route('api/deliverytour', name: 'app_api_deliverytour_')]
class DeliveryTourController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $manager, 
    private DeliveryTourRepository $repository,
    private SerializerInterface $serializer,
    private UrlGeneratorInterface $urlGenerator
    )
{
  
}


     /**
     *  Get the information of a Delivery Tour.
     *
     * Fill the body with the parameters requested
     *
     * @Route("/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns a delivery tour info",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=DeliveryTour::class))
     *     )
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="user")
     * @Security(name="user")
     */

    #[Route(('/'), name: 'new',methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
      $deliverytour= $this->serializer->deserialize($request->getContent(), DeliveryTour::class,'json');
      $deliverytour-> setCreatedAt(new DateTimeImmutable());    

      // Tell Doctrine you want to (eventually) save the deliverytour (no queries yet)
      $this->manager->persist($deliverytour);
      // Actually executes the queries (i.e. the INSERT query)
      $this->manager->flush(); 
       // A stocker en base de donnees

     return new JsonResponse($responseData, 
     Response::HTTP_CREATED,["Location"=>$location],true);
    }
   /**
     *  Get the information of a Delivery Tour.
     *
     * Fill the body with the parameters requested
     *
     * @Route("/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns a delivery tour info",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=DeliveryTour::class))
     *     )
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="user")
     * @Security(name="user")
     */

    #[Route(('/{id}'), name: 'show', methods: 'GET')]
    public function show(int $id): JsonResponse
    {
      $deliverytour = $this->repository->findOneBy(['id'=>$id]);

      if($deliverytour){
        $responseData= $this->serializer->serialize($deliverytour,'json');
        
        return new JsonResponse($responseData, Response::HTTP_OK,[],true);
      }
      
      return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
    /*
    #[Route(('/{$id}'), name: 'edit', methods: 'PUT')]
    public function edit(int $id, Request $request): JsonResponse
    {
      $deliverytour = $this->repository->findOneBy(['id'=>$id]);

      if($deliverytour){
        $deliverytour= $this->serializer->deserialize(
          $request->getContent(),
          DeliveryTour::class,
          'json',
          [AbstractNormalizer::OBJECT_TO_POPULATE=>$deliverytour]
        );
        $deliverytour->setUpdatedAt(new DateTimeImmutable());
        
        $this->manager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
      }

      return new JsonResponse(null, Response::HTTP_NOT_FOUND);
/*
      $deliverytour= setOrigin('Origin Updated');
      $deliverytour= setDestination('Destination Updated');
      $deliverytour= setMeansOfTransport('Mean of Trasport  updated');
      $deliverytour= setDescription('Description Updated');
      $deliverytour= setUpdatedAt(new DateTimeImmutable());
      $this->manager->flush();

      return $this ->redirectToRoute('app_api_deliverytour_show',['id'=>$deliverytour->getId()]);
    }*/
    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id, Request $request):JsonResponse
    {
        $deliverytour = $this->repository->findOneBy(['id' => $id]);
                if ($deliverytour) {
                    $deliverytour = $this->serializer->deserialize(
                     $request->getContent(),
                     DeliveryTour::class,
                     'json',
                     [AbstractNormalizer::OBJECT_TO_POPULATE => $deliverytour]
                    );
                    $deliverytour->setUpdatedAt(new DateTimeImmutable());

                    $this->manager->flush();

                    return new JsonResponse(null,Response::HTTP_NO_CONTENT);
                }
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
   
    #[Route(('/{id}'), name: 'delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $deliverytour = $this->repository->findOneBy(['id'=>$id]);

        if($deliverytour){
          $this->manager->remove($deliverytour);
          $this->manager->flush();

          return new JsonResponse(null,Response::HTTP_NO_CONTENT);
          }

          

          return new JsonResponse(null, Response::HTTP_NO_CONTENT);      
    }
}
