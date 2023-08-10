<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bilan', name: 'bilan')]
class ModuleController extends AbstractController
{
    #[Route('/modules', name: '_modules')]
    public function afficherLstModules(
        ModuleRepository $moduleRepository
    ): Response
    {
        $lstModules =  $moduleRepository->findAll();
        return $this->render('module/liste.html.twig',
        compact('lstModules'));
    }

    #[Route('/formulaire', name: '_formulaire')]
    public function afficherFormulaire(
        EntityManagerInterface $entityManager,
        Request $requete
    ): Response
    {

        $module = new Module();
        $formulaire = $this->createForm(ModuleType::class, $module);

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted()) {
            $entityManager->persist($module);
            $entityManager->flush();
            return $this->redirectToRoute('bilan_modules');
        }


        return $this->render('module/formulaire.html.twig',
            [
                "formulaire"=>$formulaire->createView()
            ]);

            // Pour ajouter le bouton "valider" afin de soumettre le formulaire, aller dans le fichier "ModuleType".


    }


    #[Route('/{module}', name: '_detail')]
    public function wish(
        Module $module
    ): Response
    {
        return $this->render(
            'module/details.html.twig',
            compact('module')
        );
    }


}
