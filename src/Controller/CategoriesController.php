<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);

        $categories = $repository->findAll();


        return $this->render('categories/index.html.twig', [
            "categories"=>$categories,
        ]);
    }

    /**
     * @Route("/categories/ajouter", name="ajouter_categories")
     */
    public function ajouter(Request $request)
    {
        $categorie = new Categorie();


        //creation du formulaire
        $formulaire = $this->createForm(CategorieType::class, $categorie);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récuperer l'entity manager (sorte de connexion à la BDD
            $em = $this->getDoctrine()->getManager();

            //je dis au manager que je veux ajouter la categorie dans la BDD
            $em->persist($categorie);

            $em->flush();

            return $this->redirectToRoute("categories");
        }

        return $this->render('categories/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Ajouter une categorie ",
        ]);
    }

    /**
     * @Route("/categories/modifier/{id}", name="modifier_categories")
     */
    public function modifier(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repository->find($id);

        //creation du formulaire
        $formulaire = $this->createForm(CategorieType::class, $categorie);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récuperer l'entity manager (sorte de connexion à la BDD
            $em = $this->getDoctrine()->getManager();

            //je dis au manager que je veux ajouter la categorie dans la BDD
            $em->persist($categorie);

            $em->flush();

            return $this->redirectToRoute("categories");
        }

        return $this->render('categories/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Modification de la categorie <i>".$categorie->GetTitre()."</i>",
        ]);
    }

    /**
     * @Route("/categories/supprimer/{id}", name="supprimer_categories")
     */
    public function supprimer(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repository->find($id);

        //creation du formulaire
        $formulaire = $this->createFormBuilder()
            ->add("submit",SubmitType::class, ["label" => "OK", "attr"=>["class"=>"btn btn-succes"]])
            ->getForm();
        //$formulaire = $this->createForm(CategorieType::class, $categorie);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récuperer l'entity manager (sorte de connexion à la BDD
            $em = $this->getDoctrine()->getManager();

            $em->remove($categorie);
            $em->flush();

            return $this->redirectToRoute("categories");
        }

        return $this->render('categories/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Voulez-vous supprimer la categorie <i>".$categorie->GetTitre()." ?</i>",
        ]);
    }


}