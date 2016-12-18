<?php

namespace Skobkin\Bundle\CopyPasteBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('text', TextareaType::class, ['label' => 'paste_add_form_text'])
            ->add('description', TextareaType::class, [
                'label' => 'paste_add_form_description',
                'required' => false,
            ])
            ->add('fileName', TextType::class, [
                'label' => 'paste_add_form_file_name',
                'required' => false,
            ])
            ->add('author', TextType::class, [
                'label' => 'paste_add_form_author',
                'required' => false,
            ])
            ->add('expire', ChoiceType::class, [
                'label' => 'paste_add_form_expire',
                'mapped' => false,
                'choices_as_values' => true,
                // @todo move to config
                'choices' => [
                    '5 minutes'=> 300,
                    '1 hour' => 3600,
                    '3 hours' => 10800,
                    '12 hours' => 43200,
                    '1 day' => 86400,
                    '1 week' => 604800,
                    '1 month' => 2419200,
                    '3 months' => 7257600,
                    '6 months' => 14515200,
                    '1 year' => 29030400,
                    'Never' => 0,
                ]
            ])
            ->add('private', CheckboxType::class, [
                'label' => 'paste_add_form_private',
                'required' => false, 
                'mapped' => false
            ])
            ->add('language', EntityType::class, [
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
            ->add('captcha', FakeCaptchaType::class, [
                'mapped' => false,
                'required' => true,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Skobkin\Bundle\CopyPasteBundle\Entity\Copypaste'
        ]);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'copypaste';
    }


}
