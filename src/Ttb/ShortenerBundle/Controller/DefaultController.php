<?php

namespace Ttb\ShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($url)
    {
//        $redis = $this->container->get('snc_redis.default');
//        $val = $redis->incr('foo:bar');
//        $redis->set('my_key', 'my_val');
//        $redis_cluster = $this->container->get('snc_redis.cluster');
//        $val = $redis_cluster->get('ab:cd');
//        $val = $redis_cluster->get('ef:gh');
//        $val = $redis_cluster->get('ij:kl');

        return $this->render('TtbShortenerBundle:Default:index.html.twig',
          array('url' => $url)
        );
    }
}
