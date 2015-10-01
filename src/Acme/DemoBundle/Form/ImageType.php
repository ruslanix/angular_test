<?php

namespace Acme\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label')
            ->add('file', 'file');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Acme\DemoBundle\Model\Image',
            'intention'          => 'image',
            'translation_domain' => 'AcmeDemoBundle'
        ));
    }

    public function getName()
    {
        return 'image';
    }
}
