<?php

namespace Ttb\ShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ttb\Form\Type\UrlType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new UrlType());

        $form->handleRequest($request);

        if($form->isValid())
        {
            var_dump('valid!'); die;
        }
        return $this->render('TtbShortenerBundle:Default:index.html.twig',
          array('form' => $form->createView()));
    }

    public function shrinkAction($url) {
//        $redis = $this->container->get('snc_redis.default');
//        $val = $redis->incr('foo:bar');
//        $redis->set('my_key', 'my_val');
//        $redis_cluster = $this->container->get('snc_redis.cluster');
//        $val = $redis_cluster->get('ab:cd');
//        $val = $redis_cluster->get('ef:gh');
//        $val = $redis_cluster->get('ij:kl');

        return $this->render('TtbShortenerBundle:Default:shrink.html.twig',
          array('url' => $url)
        );
    }
}
