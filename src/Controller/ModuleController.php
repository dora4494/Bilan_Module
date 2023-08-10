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
    ): Response // Le type Response indique que cette méthode renverra un objet de type Response en réponse à la requête
    {
        $lstModules =  $moduleRepository->findAll();
        return $this->render('module/liste.html.twig', // renvoie ce contenu html
        compact('lstModules')); // envoie les données
    }

    #[Route('/formulaire', name: '_formulaire')]
    public function afficherFormulaire(
        EntityManagerInterface $entityManager,
        Request $requete
    ): Response
    {

        $module = new Module();
        $formulaire = $this->createForm(ModuleType::class, $module); // création du formulaire en utilisant la classe ModuleType.  Le formulaire est lié à l'objet $module, ce qui signifie que les données saisies dans le formulaire seront liées à cet objet.

        $formulaire->handleRequest($requete); // Cette ligne gère la requête HTTP entrante avec le formulaire. Elle examine les données soumises dans la requête et les lie à l'objet $module en fonction de la structure définie dans ModuleType.

        if ($formulaire->isSubmitted()) {
            $entityManager->persist($module); // Ces lignes utilisent l'EntityManager pour persister l'objet $module en base de données. persist indique que l'objet doit être suivi par l'EntityManager
            $entityManager->flush(); // et flush effectue réellement l'opération de persistance en base de données.
            return $this->redirectToRoute('bilan_modules');
        }





        /*
        return $this->render('module/formulaire.html.twig',
            [
                "formulaire"=>$formulaire->createView()
            ]);

        */
        return $this->render(
            'module/formulaire.html.twig',
            compact('formulaire')
        );


            // Pour ajouter le bouton "valider" afin de soumettre le formulaire, aller dans le fichier "ModuleType".

    }








    #[Route('/{module}', name: '_detail')]
    public function AfficherDetailsAvis(
        Module $module
    ): Response
    {
        return $this->render(
            'module/details.html.twig',
            compact('module')
        );
    }


}
