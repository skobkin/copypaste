<?php

namespace Skobkin\Bundle\CopyPasteBundle\Controller;

use DT\Bundle\GeshiBundle\Highlighter\HighlighterInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Form\Form;
use Skobkin\Bundle\CopyPasteBundle\Entity\Copypaste;
use Skobkin\Bundle\CopyPasteBundle\Form\CopypasteType;
use \GeSHi\GeSHi;

class PasteController extends Controller
{

    /**
     * Creates a new Copypaste entity.
     *
     * @return Response
     */
    public function createAction(Request $request)
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

    /**
     * Creates a form to create a Copypaste entity.
     *
     * @param Copypaste $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Copypaste $entity)
    {
        $form = $this->createForm(CopypasteType::class, $entity, [
            'action' => $this->generateUrl('paste_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Copypaste entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $paste = new Copypaste();
        $createForm = $this->createCreateForm($paste);

        return $this->render('Paste/new.html.twig', [
            'entity' => $paste,
            'form_create'   => $createForm->createView(),
        ]);
    }

    /**
     * Finds and displays a Copypaste entity.
     *
     * @return Response
     */
    public function showAction(int $id, ?string $secret, HighlighterInterface $highlighter)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $paste Copypaste */
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
    
    /**
     * Main page
     *
     * @return Response
     */
    public function sidebarAction()
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
}
