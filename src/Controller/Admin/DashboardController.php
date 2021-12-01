<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Theme;
use App\Entity\Article;
use App\Entity\Question;
use App\Entity\Bug;
use App\Repository\UserRepository;
use App\Repository\ThemeRepository;
// use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{

    protected $userRepo;
    protected $themeRepo;
    
    public function __construct(UserRepository $userRepo, ThemeRepository $themeRepo)
    {
     $this->userRepo = $userRepo;
     $this->themeRepo = $themeRepo;
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig',
    [
        'countUsers' => count($this->userRepo->findAll()),
        'countTheme' => count($this->themeRepo->findAll()),
        'countThemeDispo' => count($this->themeRepo->findBy(['active' => 1]))
    ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sensibilis');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'home');

        yield MenuItem::Section('Gestion Front', 'fa fa-home');

        yield MenuItem::linktoCrud('Gerer les Thèmes', 'fa fa-book', Theme::class);
        yield MenuItem::linktoCrud('Gerer les Questions', 'fa fa-question', Question::class);
        yield MenuItem::linktoCrud('Gerer les Articles', 'fas fa-book-reader', Article::class);



        yield MenuItem::Section('Gestion Back', 'fa fa-home');

        yield MenuItem::linkToCrud('Gérer les utilisateurs','fas fa-user', User::class);
        yield MenuItem::linkToRoute('Voir les statistiques', 'fa fa-bar-chart', 'adminStats');
        yield MenuItem::linktoCrud('Dysfonctionnements', 'fa fa-bug', Bug::class);



    }
}
