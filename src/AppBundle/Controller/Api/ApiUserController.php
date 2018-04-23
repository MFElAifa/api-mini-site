<?php
namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiUserController extends Controller
{
    /**
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="User creation parameters",
     *     @Model(type=UserType::class)
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Success",
     *     @Model(type=User::class)
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form invalid"
     * )
     * @SWG\Tag(name="Users")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"preference"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all());
        //dump($request);
        //dump($form->isValid());
        //exit;
        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            //dump($user); exit;
            return $user;
        } else {
            return $form;
        }
    }

    /**
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="User creation parameters",
     *     @Model(type=UserType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @Model(type=User::class)
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form invalid"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="User Not Found"
     * )
     * @SWG\Tag(name="Users")
     * @Rest\View(serializerGroups={"preference"})
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="User creation parameters",
     *     @Model(type=UserType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @Model(type=User::class)
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form invalid"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="User Not Found"
     * )
     * @SWG\Tag(name="Users")
     * @Rest\View(serializerGroups={"preference"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    private function updateUser(Request $request, $clearMissing)
    {
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }

        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = []; // Le groupe de validation par défaut de Symfony est Default
        }

        $form = $this->createForm(UserType::class, $user, $options);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            // Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em = $this->get('doctrine.orm.entity_manager');
            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    private function userNotFound()
    {
        throw new NotFoundHttpException('User not found');
    }
}