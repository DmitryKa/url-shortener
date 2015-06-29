<?php

namespace ShortenerBundle\Controller;

use ShortenerBundle\Entity\Abbreviation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ShortenerBundle\Form\Type\UrlType;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    const PREFIX_KEY = 'key:';

    public function indexAction(Request $request)
    {
        $form = $this->createForm(new UrlType());

        $form->handleRequest($request);

        if($form->getData()['full_url'] != NULL)
        {
            $full_url = $form->getData()['full_url'];
            $comment = $form->getData()['comment'];
            return $this->forward('ShortenerBundle:Default:shrink',
              array('full_url' => $full_url, 'comment' => $comment));
        }

        $userId = $request->getSession()->get('id');
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from('ShortenerBundle:Abbreviation', 'u')
            ->where('u.userId = :userId')
            ->setParameter('userId', $userId);
        $saved_urls = $qb->getQuery()->getResult();
//        var_dump($saved_urls[0]->getClue()); die;
        return $this->render('ShortenerBundle:Default:index.html.twig',
          array('form' => $form->createView(), 'urls' =>$saved_urls));
    }

    public function expandAction($key) {
        $redis = $this->container->get('snc_redis.default');
        $full_url = $redis->get(self::PREFIX_KEY . $key);
        if(!$full_url) {
            return $this->createNotFoundException('URL with such key is not found');
        }
        return $this->redirect($full_url);
    }

    public function shrinkAction($full_url, $comment, Request $request) {
        $converter = $this->get('converter');
        $redis = $this->container->get('snc_redis.default');

        $hash_hex = hash('sha256', $full_url);
        $trimmed_hash_dec = substr(gmp_strval(gmp_init($hash_hex,16)), 0, 19);
        $clue = substr($converter->base62_encode($trimmed_hash_dec), 0, 6);
        $redis->set(self::PREFIX_KEY . $clue, $full_url);

        $securityContext = $this->get('security.context');
        if ($securityContext->isGranted('ROLE_AUTHENTICATED_USER')) {
            $em = $this->getDoctrine()->getManager();

            $abb = new Abbreviation();
            $abb->setClue($clue);
            $abb->setFullUrl($full_url);
            $abb->setComment($comment);

            $userId = $request->getSession()->get('id');
            $qb = $em->createQueryBuilder();
            $qb->select('u')
                ->from('ShortenerBundle:User', 'u')
                ->where('u.id = :id')
                ->setParameter('id', $userId);
            $users = $qb->getQuery()->getResult();

            $abb->setUserId($users[0]);

            $em->persist($abb);
            $em->flush();
        }

        return $this->render('ShortenerBundle:Default:shrink.html.twig',
          array('key' => $clue)
        );
    }

    public function protectedAction() {
        return $this->render('ShortenerBundle:Default:protected.html.twig');
    }

    public function loginAction() {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('ShortenerBundle:Default:login.html.twig', array(
          'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
          'error' => $error
        ));
    }
}
