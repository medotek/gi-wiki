<?php
namespace App\Form;
use App\Entity\Artifact;
use App\Entity\Set;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('set',EntityType::class, [
            'label' => 'artifacts',
            'class' => Artifact::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                    ->orderBy('a.setName', 'ASC');
            },
            'multiple' => true,
            'expanded' => true
        ])
        ->addModelTransformer(new CallbackTransformer(
        function ($artifacts) {
            $set = new Set();
            foreach ($artifacts as $artifact) {
                $set->addArtifact($artifact);
            }
            return $set;
        },
        function ($set) {
            return $set->getArtifacts();
        }
    ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class',Set::class);
    }
}