<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use App\Form\Type\TagsInputType;
use App\Repository\UserRepository;
use App\Utility\Constants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private array $categoryChoices;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->categoryChoices = $this->configureCategoryOptions();
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => ['autofocus' => true],
                'label' => 'Title',
            ])
            ->add('abstract', null, [
                'attr' => ['rows' => 3],
                'help' => 'Give a brief description of the post',
                'label' => 'Abstract'
            ])
            ->add('content', null, [
                'attr' => ['rows' => 20],
                'help' => 'Tell us what you want to tell us then 
                tell us what you told us',
                'label' => 'Content',
            ])
            ->add('headerImage', null, [
                'label' => 'URL for header image'
            ])
            ->add('readMoreUrl', null, [
                'required' => false
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $this->categoryChoices
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choices' => $this->userRepository->getAllInAlphabeticalOrder(),
                'choice_label' => 'username',
                'placeholder' => 'Select Author'
            ])
            ->add('tags',TagsInputType::class, [
                'label' => 'Tags (Separate tags with \',\')',
                'required' => false,
            ])
            ->add('saveAndCreateNew', SubmitType::class);
    }

    private function configureCategoryOptions()
    {
        $categories = [];
        foreach (Constants::CATEGORIES as $category) {
            $categories[$category] = $category;
        }
        return $categories;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
