<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\MediaRepository;
use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class HomepageController extends AbstractController
{
    /**
     * @Route("/homepage", name="homepage")
     * 
     */
    public function index(MediaRepository $mediatRepository, EventRepository $eventRepository)
    {
        $roles = $this->getUser()->getRoles();
        $user =  $this->getUser();

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'medias' => $mediatRepository->findAllByRol($roles, $user),
            'user' => $this->getUser(),
            'roles' => $roles
        ]);
    }
}
