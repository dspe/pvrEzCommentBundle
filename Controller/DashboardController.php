<?php

namespace pvr\EzCommentBundle\Controller;

use EzSystems\PlatformUIBundle\Controller\Controller as BaseController;

class DashboardController extends BaseController
{
    /**
     * Display Dashboard and get data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction()
    {
        // Get bundle configuration
        $config = $this->container->getParameter('pvr_ezcomment.config');

        // Get Comments order by date
        $comments = $this->container->get( 'pvr_ezcomment.manager' )->getLastComments(
            $this->container->get( 'ezpublish.connection' ),
            5,
            true
        );

        return $this->render(
            'PvrEzCommentBundle:Dashboard:index.html.twig', [
                'comments' => $comments,
                'config' => $config
            ]
        );
    }
}