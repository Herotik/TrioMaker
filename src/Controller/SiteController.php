<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Annonce;
use App\Entity\Secteur;
use App\Entity\ModeDeJeu;
use App\Entity\Plateforme;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SiteController extends Controller
{




    /**_____________________________________________________________________________________________________________________________________________________________HOME___________ */
    /**
     * @Route("/", name="accueil")
     */

    public function home(UserInterface $user = null, Annonce $annonce = null, Request $request, ObjectManager $manager)
    {
        $dejaVu = $request->cookies->has("popup_first_visit");
       $response = new Response();
        
       if (!$annonce) {
        $annonce = new Annonce();
    }

    $listAnnonce = $this->getDoctrine()
        ->getRepository(Annonce::class)
        ->findAll();


    $form = $this->createFormBuilder($annonce)
        ->add('titre')
        ->add('modeDeJeu', EntityType::class, [
            'class' => ModeDeJeu::class,
            'choice_label' => 'libelle'
        ])
        ->add('description', TextareaType::class)
        ->add('plateforme', EntityType::class, [
            'class' => Plateforme::class,
            'choice_label' => 'libelle'
        ])
        ->add('cote')
        ->add('pseudo')
        //->add('save', SubmitType::class, ['label' => 'Créer'])
        ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $manager->persist($annonce);
        $manager->flush();

        return $this->redirectToRoute('accueil');
    }
        $this->get('translator')->setLocale("fr_FR");
        return $this->render(
            'site/accueil.html.twig',array(
            'formCreationAnnonce' => $form->createView(),
            'listAnnonce' => $listAnnonce
            ),$response);
    }


    /**_____________________________________________________________________________________________________________________________________________________________HOME___________ */
    /**
     * @Route("/test", name="test")
     */

    public function test(UserInterface $user = null, Annonce $annonce = null, Request $request, ObjectManager $manager)
    {
        const Fortnite = require("fortnite-api");

let fortniteAPI = new Fortnite(
    [
        "alex.mks6@gmail.com",
        "alex5926",
        "MzQ0NmNkNzI2OTRjNGE0NDg1ZDgxYjc3YWRiYjIxNDE6OTIwOWQ0YTVlMjVhNDU3ZmI5YjA3NDg5ZDMxM2I0MWE=",
        "ZWM2ODRiOGM2ODdmNDc5ZmFkZWEzY2IyYWQ4M2Y1YzY6ZTFmMzFjMjExZjI4NDEzMTg2MjYyZDM3YTEzZmM4NGQ="
    ]
);

fortniteAPI.login().then(() => {
    //YOUR CODE
});


        return $this->render(
            'site/test.html.twig',array());
    }
}
