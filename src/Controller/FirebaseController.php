<?php

namespace App\Controller;

use Kreait\Firebase\Contract\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirebaseController extends AbstractController
{


    #[Route('/firebase', name: 'app_firebase')]
    public function index(Request $request, Auth $auth): Response
    {

        

        if($request->request->get('email') != ''){

            $email = $request->request->get('email');
            $clearTextPassword = $request->request->get('password');

            $signInResult = $auth->signInWithEmailAndPassword($email, $clearTextPassword);
            dd($signInResult);
        }

        return $this->render('firebase/index.html.twig', [
            'controller_name' => 'FirebaseController',
        ]);
    }
}
