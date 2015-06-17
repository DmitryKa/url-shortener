<?php

namespace ShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ShortenerBundle\Form\Type\UrlType;

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
            return $this->forward('ShortenerBundle:Default:shrink',
              array('full_url' => $full_url));
        }
        return $this->render('ShortenerBundle:Default:index.html.twig',
          array('form' => $form->createView()));
    }

    public function expandAction($key) {
        $redis = $this->container->get('snc_redis.default');
        $full_url = $redis->get(self::PREFIX_KEY . $key);
        if(!$full_url) {
            return $this->forward('ShortenerBundle:Default:index');
        }
        return $this->redirect($full_url);
    }

    public function shrinkAction($full_url) {
        $converter = $this->get('converter');
        $redis = $this->container->get('snc_redis.default');

        $hash_hex = hash('sha256', $full_url);
        $trimmed_hash_dec = substr(gmp_strval(gmp_init($hash_hex,16)), 0, 19);
        $key = substr($converter->base62_encode($trimmed_hash_dec), 0, 6);
        $redis->set(self::PREFIX_KEY . $key, $full_url);

        return $this->render('ShortenerBundle:Default:shrink.html.twig',
          array('key' => $key)
        );
    }
}
