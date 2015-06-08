<?php

namespace Ttb\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('full_url', 'text', array('label' => 'URL:'))
          ->add('save', 'submit', array('label'=>'Make it short!'));
    }

    public function getName()
    {
        return 'url';
    }
}