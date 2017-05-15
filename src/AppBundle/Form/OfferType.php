<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('imageUrl')
            ->add('expireDate')
            ->add('categories', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'group_by' => 'parent',
                'choice_label' => 'name',
                'placeholder' => 'Choose a Category',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repo) {
                    $qb = $repo->createQueryBuilder('l');
                    $qb->andWhere('l.parent IS NOT NULL');

                    return $qb;
                }
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Offer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_offer';
    }


}
