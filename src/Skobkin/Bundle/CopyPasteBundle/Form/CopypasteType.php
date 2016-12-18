<?php

namespace Skobkin\Bundle\CopyPasteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CopypasteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'textarea', ['label' => 'paste_add_form_text'])
            ->add('description', 'textarea', [
                'label' => 'paste_add_form_description',
                'required' => false,
            ])
            ->add('fileName', 'text', [
                'label' => 'paste_add_form_file_name',
                'required' => false,
            ])
            ->add('author', 'text', [
                'label' => 'paste_add_form_author',
                'required' => false,
            ])
            ->add('expire', 'choice', [
                'label' => 'paste_add_form_expire',
                'mapped' => false,
                // @todo move to config
                'choices' => [
                    300 => '5 minutes',
                    3600 => '1 hour',
                    10800 => '3 hours',
                    43200 => '12 hours',
                    86400 => '1 day',
                    604800 => '1 week',
                    2419200 => '1 month',
                    7257600 => '3 months',
                    14515200 => '6 months',
                    29030400 => '1 year',
                    0 => 'Never',
                ]
            ])
            ->add('private', 'checkbox', [
                'label' => 'paste_add_form_private',
                'required' => false, 
                'mapped' => false
            ])
            ->add('language', 'entity', [
                    'label' => 'paste_add_form_language',
                    'class' => 'Skobkin\Bundle\CopyPasteBundle\Entity\Language',
                    'query_builder' => function (EntityRepository $repo) {
                        /* @var $qb QueryBuilder */
                        return $repo->createQueryBuilder('lang')
                            ->where('lang.isEnabled = :enabled')
                            ->addOrderBy('lang.isPreferred', 'DESC')
                            ->addOrderBy('lang.code')
                            ->setParameter('enabled', true);
                    },
                    //'preferred_choices' => []
            ])
            ->add('captcha', 'skobkin_fake_captcha', [
                'mapped' => false,
                'required' => true,
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Skobkin\Bundle\CopyPasteBundle\Entity\Copypaste'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'skobkin_bundle_copypastebundle_copypaste';
    }
}
