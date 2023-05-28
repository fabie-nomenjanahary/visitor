<?php

namespace App\Controller;

use App\Entity\Visitor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api",name="api_")
 */
class VisitorController extends AbstractController
{
    /**
     * @Route("/visitor", name= "visitor_index",methods={"GET"})
    */
     public function index(ManagerRegistry $doctrine): Response
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
    /**
     * @Route("/visitor", name="visitor_new", methods={"POST"})
     */
    public function new(ManagerRegistry $doctrine, Request $request):Response
    {
        $entityManager=$doctrine->getManager();

        $visitor=new Visitor();

        $visitor->setNumero($request->request->get('numero'));
        $visitor->setNom($request->request->get('nom'));
        $visitor->setNbJours($request->request->get('nbJours'));
        $visitor->setTarifJournalier($request->request->get('tarifJournalier'));

        $entityManager->persist($visitor);
        $entityManager->flush();

        return $this->json('Nouveau visiteur crée avec succès');
    }
    /**
     * @Route("/visitor/{id}",name="visitor_show",methods={"GET"})
     */
    public function show(ManagerRegistry $doctrine,int $id):Response
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

    /**
     * @Route("/visitor/{id}", name="visitor_edit", methods={"PUT"})
     */
    public function edit(ManagerRegistry $doctrine, Request $request, int $id):Response
    {
        $entityManager=$doctrine->getManager();
        $visitor=$entityManager->getRepository(Visitor::class)->find($id);

        if (!$visitor) {
            return $this->json('Aucun visiteur trouvé pour id '. $id,404);
        }

        $visitor->setNumero($request->request->get('numero'));
        $visitor->setNom($request->request->get('noù'));
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

    /**
     * @Route("visitor/{id}",name="visitor_delete", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id):Response
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
