<?php

namespace App\Controller;

use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\Document;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Attribute\Template;

class DefaultController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */
    /**
     * @Route("/", name="home")
     */
    public function defaultAction(Request $request, Document $document): Response
    {
        // Fetch the teaser image and text from the document
        $teaserImage = $document->getEditable('teaserImage');
        $text = $document->getEditable('text');

        return $this->render('default/index.html.twig', [
            'document' => $document,
            'teaserImage' => $teaserImage,
            'teaserText' => $text,
        ]);
    }

    /**
     * @Route("/product", name="product")
     */
    public function productAction(Request $request): Response
    {
        return $this->render('content/product.html.twig');
    }

    /**
     * @Route("/page1", name="page1")
     */
    public function pageAction(Request $request): Response
    {
        return $this->render('content/page1.html.twig');
    }

    /**
     * Forwards the request to admin login
     */
    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }
}
