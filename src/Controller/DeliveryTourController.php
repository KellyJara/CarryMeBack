<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\DeliveryTour;
use App\Repository\DeliveryTourRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


#[Route('api/deliverytour', name: 'app_api_deliverytour_')]
class DeliveryTourController extends AbstractController
{
  public function __construct(private EntityManagerInterface $manager, private DeliveryTourRepository $repository)
{
  
}
    #[Route(('/'), name:'new', methods: 'POST')]
    public function new(): JsonResponse
    {
      $deliverytour= new DeliveryTour();
      $deliverytour= setOrigin('Paris');
      $deliverytour= setDestination('Bordeaux');
      $deliverytour= setMeansOfTransport('Plane');
      $deliverytour= setDescription('I have some space on my suitcase so I can carry a parsel for you');
      $deliverytour= setCreatedAt(new DateTimeImmutable());

      // Tell Doctrine you want to (eventually) save the deliverytour (no queries yet)
      $this->manager->persist($deliverytour);
      // Actually executes the queries (i.e. the INSERT query)
      $this->manager->flush(); 
       // A stocker en base de donnees
     return $this->json(['message' => "New DeliveryTour created with {$deliverytour->getId()} id"],
     Response::HTTP_CREATED, );
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
