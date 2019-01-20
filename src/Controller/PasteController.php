<?php

namespace App\Controller;

use DT\Bundle\GeshiBundle\Highlighter\HighlighterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Entity\Copypaste;
use App\Form\CopypasteType;
use \GeSHi\GeSHi;

class PasteController extends AbstractController
{
    public function createAction(Request $request): Response
    {
        $paste = new Copypaste();
        $form = $this->createCreateForm($paste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check "captcha"
            // @todo check internally in transformation or somewhere else
            if (null === $form->get('captcha')->getData()) {
                throw $this->createNotFoundException('This isn\'t the service you\'re spamming to.');
            }

            $em = $this->getDoctrine()->getManager();
            
            if ($form->get('private')->getData()) {
                // Private paste
                $paste->setSecret(substr(md5($paste->getText()), 0, 16));
            }
            
            $expire = (int) $form->get('expire')->getData();
            //var_dump($expire); exit();
            if ($expire) {
                $dateExpire = new \DateTime();
                $dateExpire->add(new \DateInterval('PT' . $expire . 'S'));
                $paste->setDateExpire($dateExpire);
            } 
            
            $paste->setIp($request->getClientIp());
            
            $em->persist($paste);
            $em->flush();
            
            if ($paste->isPrivate()) {
                return $this->redirect($this->generateUrl('paste_show_private', ['id' => $paste->getId(), 'secret' => $paste->getSecret()]));
            } else {
                return $this->redirect($this->generateUrl('paste_show_public', ['id' => $paste->getId()]));
            }          
        }

        throw $this->createAccessDeniedException('Sorry :(');
    }

    public function newAction(): Response
    {
        $paste = new Copypaste();
        $createForm = $this->createCreateForm($paste);

        return $this->render('Paste/new.html.twig', [
            'entity' => $paste,
            'form_create'   => $createForm->createView(),
        ]);
    }

    public function showAction(int $id, ?string $secret, HighlighterInterface $highlighter): Response
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $paste \App\Entity\Copypaste */
        $paste = $em->getRepository(Copypaste::class)->findOneBy([
            'id' =>$id,
            'secret' => $secret
        ]);

        if (!$paste) {
            throw $this->createNotFoundException('Unable to find copypaste.');
        }
        
        $editForm = $this->createCreateForm($paste);
        
        $highlightedCode = $highlighter->highlight($paste->getText(), $paste->getLanguage()->getCode(), function(GeSHi $geshi) {
            $geshi->set_header_type(GESHI_HEADER_PRE);
            $geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);
        });

        return $this->render('Paste/show.html.twig', [
            'paste' => $paste,
            'highlighted_text' => $highlightedCode,
            'form_create' => $editForm->createView(),
        ]);
    }

    public function sidebarAction(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $pastes = $em->getRepository(Copypaste::class)->findBy(
            ['secret' => null],
            ['id' => 'DESC'],
            // @todo move to the config
            15
        );

        return $this->render('sidebar.html.twig', ['pastes' => $pastes]);
    }

    private function createCreateForm(Copypaste $entity): FormInterface
    {
        $form = $this->createForm(CopypasteType::class, $entity, [
            'action' => $this->generateUrl('paste_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Create']);

        return $form;
    }
}
