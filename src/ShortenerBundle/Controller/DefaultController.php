<?php

namespace ShortenerBundle\Controller;

use ShortenerBundle\Entity\Abbreviation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ShortenerBundle\Form\Type\UrlType;
use Symfony\Component\Security\Core\Security;

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
        $saved_urls = $this
            ->getDoctrine()
            ->getRepository('ShortenerBundle:Abbreviation')
            ->findByUserId($userId);
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

            $user = $this
                ->getDoctrine()
                ->getRepository('ShortenerBundle:User')
                ->findOneById($userId);
            $abb->setUserId($user);

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
        if ($this->get('request')->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(Security::AUTHENTICATION_ERROR);
        }

        return $this->render('ShortenerBundle:Default:login.html.twig', array(
          'last_username' => $this->get('request')->getSession()->get(Security::LAST_USERNAME),
          'error' => $error
        ));
    }
}
