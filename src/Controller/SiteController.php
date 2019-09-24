<?php

namespace App\Controller;

use App\Entity\Annonce;
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

    public function home(UserInterface $user = null, Request $request)
    {
        $dejaVu = $request->cookies->has("popup_first_visit");
       $response = new Response();
        if(!$dejaVu)
        {
            $cookie_info = array(
                'name'  => 'popup_first_visit',
                'value' => date('now'),
                'time'  => time() + 3600 * 24 * 7 *365
            );
            $cookie = new Cookie($cookie_info['name'], $cookie_info['value'], $cookie_info['time']);
            $response->headers->setCookie($cookie);
            $response->sendHeaders();
        }
        $this->get('translator')->setLocale("fr_FR");
        return $this->render(
            'site/accueil.html.twig',array(

            ),$response);
    }


    /**_________________________________________________________________________________________________________________________________________________________________CREATE/EDIT OFFRE */
    /**
     * @Route("/annonce/new", name="annonce_creation")
     * @Route("/annonce/{id}/edit", name="annonce_edition")
     */
    public function manipulationOffre(Offre $offre = null, Request $request, ObjectManager $manager, UserInterface $user = null, \Swift_Mailer $mailer)
    {
        if (!$annonce) {
            $annonce = new Annonce();
        }
        $form = $this->createFormBuilder($annonce)
            ->add('title')
            ->add('mode', EntityType::class, [
                'class' => ModeDeJeu::class,
                'choice_label' => 'libelle'
            ])
            ->add('description', TextareaType::class)
            ->add('secteur', EntityType::class, [
                'class' => Secteur::class,
                'choice_label' => 'libelle'
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'libelle'
            ])
            ->add('niveau')
            ->add('cp', null, [
                'label' => 'Code postal'
            ])
            ->add('ville', null, [
                'label' => 'Ville'
            ])
            ->add('adresse', null, [
                'label' => 'Adresse'
            ])
            ->add('debut', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'attr' => ['class' => 'js-datepicker']

            ])
            ->add('fin', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'empty_data' => '01-01-1970',
                'format' => 'dd-MM-yyyy',
                'attr' => ['class' => 'js-datepicker']

            ])
            ->add('remuneration', RangeType::class, [
                'attr' => [
                    "data-provide" => "slider",
                    "data-slider-min" => "0",
                    "data-slider-max" => "200",
                    "data-slider-step" => "1",
                    "data-slider-tooltip" => "hide",
                ]
            ])
            //->add('save', SubmitType::class, ['label' => 'Créer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
            $uti = $repo->find($userId);
            $offre->setActive(true)->setUtilisateur($uti);
            $dateEmission = new \DateTime();
            $offre->setEmission($dateEmission);
            $manager->persist($offre);
            $manager->flush();
            //
            //
            $prefContrat = $this->getDoctrine()->getRepository(TypeContrat::class)->find($request->request->get('form')['typeContrat']);
            $prefRegion = $this->getDoctrine()->getRepository(Region::class)->find($request->request->get('form')['region']);
            $prefSecteur = $this->getDoctrine()->getRepository(Secteur::class)->find($request->request->get('form')['secteur']);
            $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
            $destinataires = $repo->findBy(array("region" => $prefRegion, "typeContrat" => $prefContrat, "secteur" => $prefSecteur));
            $listeDestinataireFinale = '';
            $listeDestinataire = array() ;
            foreach ($destinataires as $user) {
                $listeDestinataire[] = $user->getUsername();
            }
            if(count($listeDestinataire) > 0 ){
                $listeDestinataireFinale = join(',', $listeDestinataire);
            }
           
            $message = (new \Swift_Message('Une offre est susceptible de vous interesser !'))
                ->setFrom('recrutement@valoxy.fr')
                ->setTo($listeDestinataire)
                ->setBody(
                    $this->renderView(
                        //'Emails/registration.html.twig',
                        'site/accueil.html.twig',
                        ['name' => $user->getId()]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            return $this->redirectToRoute('espace_perso');
        }
        return $this->render('site/creationOffre.html.twig', [
            'formCreationOffre' => $form->createView(),
            'editMode' => $offre->getId() !== null
        ]);
    }
}
