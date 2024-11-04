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
use DateTimeImmutable ;


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
    #[Route(methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
      $deliverytour= $this->serializer->deserialize($request->getContent(), DeliveryTour::class,'json');
      $deliverytour= setCreatedAt(new DateTimeImmutable());

      /*$deliverytour= new DeliveryTour();
      $deliverytour= setOrigin('Paris');
      $deliverytour= setDestination('Bordeaux');
      $deliverytour= setMeansOfTransport('Plane');
      $deliverytour= setDescription('I have some space on my suitcase so I can carry a parsel for you');*/
      

      // Tell Doctrine you want to (eventually) save the deliverytour (no queries yet)
      $this->manager->persist($deliverytour);
      // Actually executes the queries (i.e. the INSERT query)
      $this->manager->flush(); 
       // A stocker en base de donnees

       $responseData = $this->serializer->serialize($deliverytour, 'json');
       $location= $this->urlGenerator->generate(
        'app_api_restaurant_show',
        ['id'=>$deliverytour->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL,
       );
     return new JsonResponse($responseData, 
     Response::HTTP_CREATED,["Location"=>$location],true);
    }
    

    #[Route(('/{id}'), name: 'show', methods: 'GET')]
    public function show(int $id): JsonResponse
    {
      $deliverytour = $this->repository->findOneBy(['id'=>$id]);

      if(!$deliverytour){
        throw $this->createNotFoundException("No Delivery Tour found for {$id} id");
      }
      return $this->json(['message'=>"A Delivery Tour from: {$deliverytour->getOrigin()}, to {$deliverytour->getDestination()} with id:{$deliverytour->getId()}"]);
    }
    
    #[Route(('/{$id}'), name: 'edit', methods: 'PUT')]
    public function edit(int $id): JsonResponse
    {
      $deliverytour = $this->repository->findOneBy(['id'=>$id]);

      if(!$deliverytour){
        throw $this->createNotFoundException("No Delivery Tour found for {$id} id");
      }

      $deliverytour= setOrigin('Origin Updated');
      $deliverytour= setDestination('Destination Updated');
      $deliverytour= setMeansOfTransport('Mean of Trasport  updated');
      $deliverytour= setDescription('Description Updated');
      $deliverytour= setUpdatedAt(new DateTimeImmutable());
      $this->manager->flush();

      return $this ->redirectToRoute('app_api_deliverytour_show',['id'=>$deliverytour->getId()]);
    }
   
    #[Route(('/{id}'), name: 'delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $deliverytour = $this->repository->findOneBy(['id'=>$id]);

        if(!$deliverytour){
            throw $this->createNotFoundException("No Delivery Tour found for {$id} id");
          }

          $this->manager->remove($deliverytour);
          $this->manager->flush();

          return $this->json(['message'=>"Delivery Tour source deleted"], Response::HTTP_NO_CONTENT);      
    }
}
