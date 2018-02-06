<?php

namespace App\Controller\Forum;

use App\Entity\Category;
use App\Entity\Forum;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CatController extends BaseController
{
    /**
     * ForumController constructor.
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \Symfony\Component\HttpFoundation\RequestStack             $request
     */
    public function __construct(SessionInterface $session, RequestStack $request)
    {
        parent::__construct($session, $request);
        $this->title = 'Category';
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('base');
    }

    /**
     * @param string $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \LogicException
     */
    public function show(string $slug): Response
    {
        $getInfoCat = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['slug' => $slug]);

        $this->breadcrumb[] = ['url' => 'active', 'name' => $getInfoCat->getName()];

        $this->data['slug_forum'] = $slug;

        $this->data['cat_info'] = [
            'id' => $getInfoCat->getId(),
            'name' => $getInfoCat->getName(),
            'description' => $getInfoCat->getDescription(),
            'slug' => $getInfoCat->getSlug(),
            'position' => $getInfoCat->getPosition(),
        ];

        $this->data['getForum'] = $this->getDoctrine()->getManager()->getRepository(Forum::class)->findAllWithoutParent($getInfoCat->getId());

        $this->stitle = $getInfoCat->getName();

        return $this->renderer('cat/show.html.twig');
    }
}
