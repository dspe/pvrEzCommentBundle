<?php

namespace pvr\EzCommentBundle\Controller;

use EzSystems\PlatformUIBundle\Controller\Controller as BaseController;
use pvr\EzCommentBundle\Comment\PvrEzCommentManager;

class DashboardController extends BaseController
{
    /**
     * Display Dashboard and get data
     *
     * @param $offset
     * @param $status
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction( $offset, $status )
    {
        $next = $previous = false;
        $offset = (int) $offset;
        $manager = $this->container->get( 'pvr_ezcomment.manager' );

        if ( !$manager->statusExists( $status ) ) $status = -1;

        // Get bundle configuration
        $config = $this->container->getParameter('pvr_ezcomment.config');
        $limit = $config['dashboard_limit'];

        // Get numbers of total comments
        $commentsCount = $manager->getCountComments( false, $this->container->get( 'ezpublish.connection' ), $status );

        // @TODO: switch to pagerfanta
        if ($offset > 0) $previous = max(0, $offset - $limit);
        if (($offset + $limit) < $commentsCount) $next = $offset + $limit;

        // Get Comments order by date
        $comments = $manager->getLastComments( $this->container->get( 'ezpublish.connection' ), $limit, $offset, $status );

        return $this->render(
            'PvrEzCommentBundle:Dashboard:index.html.twig', [
                'comments' => $comments,
                'config' => $config,
                'next' => $next,
                'previous' => $previous,
                'haveToPaginate' => (bool) ($commentsCount > $limit),
                'status' => $status
            ]
        );
    }
}