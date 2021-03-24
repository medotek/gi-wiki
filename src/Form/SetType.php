<?php
namespace App\Form;
use App\Entity\Artifact;
use App\Entity\Set;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetType extends AbstractType {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $artifacts = $this->entityManager->getRepository(Artifact::class)->findAll();

        $builder->add('artifacts',EntityType::class, [
            'label' => 'Sets',
            'class' => Artifact::class,
            'choices' => $artifacts,
            'multiple' => true,
            'expanded' => true,
            'mapped' => false
        ])
        ->addModelTransformer(new CallbackTransformer(
        function ($artifacts) {
            $set = new Set();
            foreach($artifacts as $artifact) {
                $set->addArtifact($artifact);
            }
            return $set;
        },
        function (Set $set) {
            return $set->getArtifacts();
        }
    ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class',Set::class);
    }
}