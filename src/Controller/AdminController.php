<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Entity\Option;
use App\Repository\OptionRepository;
use App\Form\EditOptionsType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
 
    /**
     * @Route("/admin/modifOptions/{question}", name="modifOptions")
     */
    public function editOptions(Question $question, OptionRepository $oRepo, Request $request, EntityManagerInterface $manager)
    {
        $currentOptions= $oRepo->findBy(array('question' => $question->getId()), array('id' => 'ASC'));
        $option = new Option();
        $count = 0;
        $forms = [];

        foreach($currentOptions as $option)
        {
            ${'var'.$count} = $this->createForm(EditOptionsType::class, $option);
            $forms[] = ${'var'.$count};
            $count++;
        }

        $count = 0;
        foreach($forms as $form)
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                dump($option);
                // $manager->persist();
                // $manager->flush();
                // $this->addFlash('message', 'Option modifié avec succès');
                $count++;
            }
        }

        foreach($forms as &$form)
        {
            $form = $form->createView();
        }


        return $this->render('admin/editOptions.html.twig', [
            'forms' => $forms,
            'options' => $currentOptions
        ]); 
    }
}
