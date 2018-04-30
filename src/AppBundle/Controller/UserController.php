<?php
/**
 * Created by PhpStorm.
 * User: melaifa
 * Date: 27/04/2018
 * Time: 16:39
 */

namespace AppBundle\Controller;

use AppBundle\Form\UserRegistrationForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Welcome '.$user->getEmail());
            //return $this->redirectToRoute('homepage');
            return $this->get('security.authentication.guard_handler')->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
            );

        }


        return $this->render('AppBundle::user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

}