<?php

namespace Ttb\ShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ttb\Entity\Url;
use Ttb\Form\Type\UrlType;

class DefaultController extends Controller
{
    const PREFIX_KEY = 'key:';
    const PREFIX_HASH = 'hash:';
    const PREFIX_CONFIG = 'config:';

    public function indexAction(Request $request)
    {
        $form = $this->createForm(new UrlType());

        $form->handleRequest($request);

        if($form->isValid())
        {
            $full_url = $form->getData()['full_url'];
            return $this->forward('TtbShortenerBundle:Default:shrink',
              array('full_url' => $full_url));
        }
        return $this->render('TtbShortenerBundle:Default:index.html.twig',
          array('form' => $form->createView()));
    }

    public function expandAction($key) {
        $redis = $this->container->get('snc_redis.default');
        $full_url = $redis->get(self::PREFIX_KEY . $key);
        if(!$full_url) {
            return $this->forward('TtbShortenerBundle:Default:index');
        }
        return $this->redirect($full_url);
    }

    public function shrinkAction($full_url) {
        $converter = $this->get('converter');
        $redis = $this->container->get('snc_redis.default');

        $hash = hash('sha256', $full_url);
        $keys = $redis->smembers(self::PREFIX_HASH . $hash);
        $created = false;

        foreach($keys as $key) {
            $full_url_existent = $redis->get(self::PREFIX_KEY . $key);
            if ($full_url == $full_url_existent) {
                $created = true;
            }
        }
        if(!$created) {
            $counter = $redis->incr(self::PREFIX_CONFIG . 'counter');
            $key = $converter->base62_encode($counter);
            $redis->set(self::PREFIX_KEY . $key, $full_url);
            $redis->sadd(self::PREFIX_HASH . $hash, $key);
        }

        return $this->render('TtbShortenerBundle:Default:shrink.html.twig',
          array('key' => $key)
        );
    }
}
