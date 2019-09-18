<?php

namespace App\Controller;

use App\Repository\OffreRepository;
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
}
