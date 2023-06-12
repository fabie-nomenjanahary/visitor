<?php

namespace App\Controller;

use App\Entity\Visitor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api',name:'api_')]
class VisitorController extends AbstractController
{
    
    #[Route('/visitors',name:'visitor_index',methods:['GET'])]
     public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $visitors=$doctrine
            ->getRepository(Visitor::class)
            ->findAll();

        $data=[];

        foreach ($visitors as $visitor) {
            $data[]=[
                'id'=>$visitor->getId(),
                'nom'=>$visitor->getNom(),
                'numero'=>$visitor->getNumero(),
                'nbJours'=>$visitor->getNbJours(),
                'tarifJournalier'=>$visitor->getTarifJournalier()
            ];
        }

       return $this->json($data);
    }
   
    #[Route('/visitors',name:'visitor_create',methods:['POST'])]
    public function create(ManagerRegistry $doctrine, Request $request):JsonResponse
    {
        $entityManager=$doctrine->getManager();

        $visitor=new Visitor();
        // dump($request);
        $visitor->setNumero($request->request->get('numero'));
        $visitor->setNom($request->request->get('nom'));
        $visitor->setNbJours($request->request->get('nbJours'));
        $visitor->setTarifJournalier($request->request->get('tarifJournalier'));

        $entityManager->persist($visitor);
        $entityManager->flush();

        return $this->json('Nouveau visiteur crée avec succès');
    }
    
    #[Route('/visitors/{id}',name:'visitor_show',methods:['GET'])]
    public function show(ManagerRegistry $doctrine,int $id):JsonResponse
    {
        $visitor=$doctrine->getRepository(Visitor::class)->find($id);

        if (!$visitor) {
            return $this->json('Aucun visiteur trouvé pour id '. $id,404);
        }

        $data=[
            'id'=>$visitor->getId(),
            'numero'=>$visitor->getNumero(),
            'nom'=>$visitor->getNom(),
            'nbJours'=>$visitor->getNbJours(),
            'tarifJournalier'=>$visitor->getTarifJournalier(),
        ];

        return $this->json($data);
    }

  
    #[Route('/visitors/{id}',name:'visitor_update',methods:['PUT','PATCH'])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id):JsonResponse
    {
        $entityManager=$doctrine->getManager();
        $visitor=$entityManager->getRepository(Visitor::class)->find($id);

        if (!$visitor) {
            return $this->json('Aucun visiteur trouvé pour id '. $id,404);
        }

        $visitor->setNumero($request->request->get('numero'));
        $visitor->setNom($request->request->get('nom'));
        $visitor->setNbJours($request->request->get('nbJours'));
        $visitor->setTarifJournalier($request->request->get('tarifJournalier'));

        $entityManager->flush();

        $data=[
            'id'=>$visitor->getId(),
            'numero'=>$visitor->getNumero(),
            'nom'=>$visitor->getNom(),
            'nbJours'=>$visitor->getNbJours(),
            'tarifJournalier'=>$visitor->getTarifJournalier(),
        ];

        return $this->json($data);
    }
    
    #[Route('/visitors/{id}',name:'visitor_delete',methods:['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id):JsonResponse
    {
        $entityManager=$doctrine->getManager();
        $visitor=$entityManager->getRepository(Visitor::class)->find($id);
     
        if (!$visitor) {
            return $this->json('Aucun visiteur trouvé pour id '. $id,404);
        }

        $entityManager->remove($visitor);
        $entityManager->flush();

        return $this->json('Visiteur supprimé avec succès');
    }
}
