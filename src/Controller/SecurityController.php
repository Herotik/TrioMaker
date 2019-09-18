<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends Controller
{
    /**
    * @Route("/inscription",name="security_registration")
    */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();
        
        $form = $this->createForm(RegistrationType::class, $utilisateur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploads_directory = $this->getParameter('uploads_directory');
            if ($utilisateur->getType() == 1) {
                
                $file = $request->files->get('registration')['cv'];
                $filenameCV = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($uploads_directory, $filenameCV);
                $utilisateur->setCv($filenameCV);
                $utilisateur->setCvBlur("blur-" . $filenameCV);
            } else {
                $logo = $request->files->get('registration')['logo'];
                $filenameLogo = md5(uniqid()) . '.' . $logo->guessExtension();
                $logo->move(
                    $uploads_directory,
                    $filenameLogo
                );
                $utilisateur->setLogo($filenameLogo);
            }
            
            $hash = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($hash);
            $manager->persist($utilisateur);
            $manager->flush();
            
            return $this->redirectToRoute('login');
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
            ]);
        }
        
        /**
        * @Route("/login", name="login")
        */
        public function login(Request $request, AuthenticationUtils $utils)
        {
            $error = $utils->getLastAuthenticationError();
            $lastUsername = $utils->getLastUsername();
            
            
            return $this->render('security/login.html.twig', [
                'error' => $error,
                'last_username' => $lastUsername
                ]);
            }
            
            
            /**
            * @Route("/forget-password", name="forgetPassword")
            */
            public function forgetPassword(Request $request, AuthenticationUtils $utils,ObjectManager $manager, \Swift_Mailer $mailer, UserInterface $user = null,TokenGeneratorInterface $tokenGenerator) :Response
            {
                $info = '';
                $color = '';
                $data = array();
                
                $form = $this->createFormBuilder($data)->add('username')->getForm();
                if ($request->isMethod('POST')) {
                    $form->handleRequest($request);
                    $username = $form["username"]->getData('username');
                    
                    $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(array('username' => $username));
                    dump($utilisateur);
                    if ($utilisateur === null || count($utilisateur)!= 1) {
                        $info = "Impossible de retrouver un compte associé à cette adresse email";
                        $color = "red";
                    }else{
                        $info = "Un email vient d'être envoyé a l'adresse renseignée. Suivez les instructions qu'il contient";
                        $color = "green";
                        $token = $tokenGenerator->generateToken();
                        
                        $utilisateur[0]->setToken($token);
                        $manager->flush();
                        $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
                        $message = (new \Swift_Message("Réinitialisation de mot de passe Valo'RH"))
                        ->setFrom("report@valorh.fr")
                        ->setTo($username)
                        ->setBody(
                            $this->renderView(
                                //'Emails/registration.html.twig',
                                'security/templateMailResetPassword.html.twig',
                                ['url' => $url]
                            ),
                            'text/html'
                        );
                        
                        $mailer->send($message);
                        $this->addFlash('notice', 'Mail envoyé');
                    }
                    
                    
                    
                    
                    
                    
                }
                
                
                
                
                
                return $this->render('security/forget-password.html.twig', [
                    'form' => $form->createView(),
                    'info' => $info,
                    'color' => $color
                    ]);
                }
                
                
                
                /**
                * @Route("/reset_password/{token}", name="app_reset_password")
                */
                public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
                {
                    
                    if ($request->isMethod('POST')) {
                        $entityManager = $this->getDoctrine()->getManager();
                        
                        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneByToken($token);
                        /* @var $user User */
                        
                        if ($utilisateur === null) {
                            $this->addFlash('danger', 'Token Inconnu');
                            return $this->redirectToRoute('accueil');
                        }
                        
                        $utilisateur->setToken("");
                        $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $request->request->get('password')));
                        $entityManager->flush();
                        
                        $this->addFlash('notice', 'Mot de passe mis à jour');
                        
                        return $this->redirectToRoute('accueil');
                    }else {
                        
                        return $this->render('security/reset_password.html.twig', ['token' => $token]);
                    }
                    
                }
                
                
                /**
                * @Route("/logout", name="logout")
                */
                
                public function logout()
                { }
            }
            