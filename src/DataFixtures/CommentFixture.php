<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Utility\DateTimeUtility;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $this->loadComments($manager);
        $manager->flush();
    }

    private function loadComments(ObjectManager $manager)
    {
        $generator = Factory::create();
        for ($i = 0; $i < 1000; $i++) {
            $comment = Comment::createFromDateTime($this->getDateTime())
                ->setAuthor($this->getAuthor())
                ->setPost($this->getPost())
                ->setContent(implode(" ", $generator->sentences(5)));
            $manager->persist($comment);
        }

    }

    private function getDateTime(): DateTimeInterface
    {
        $generator = Factory::create();
        return DateTimeUtility::createDateTimeFromString("{$generator->numberBetween(1, 28)}" .
            "/{$generator->numberBetween(1, 12)}/2020");
    }

    private function getPost()
    {
        $postReferences = [
            PostFixture::POST_REFERENCE_1,
            PostFixture::POST_REFERENCE_2,
            PostFixture::POST_REFERENCE_3,
            PostFixture::POST_REFERENCE_4,
            PostFixture::POST_REFERENCE_5,
        ];
        return $this->getReference($postReferences[Factory::create()
            ->numberBetween(0, count($postReferences) - 1)]);
    }

    private function getAuthor()
    {
        $userReferences = [
            UserFixture::ADMIN_USER_REFERENCE,
            UserFixture::RANDOM_USER_REFERENCE_1,
            UserFixture::RANDOM_USER_REFERENCE_2,
            UserFixture::RANDOM_USER_REFERENCE_3,
            UserFixture::RANDOM_USER_REFERENCE_4,
            UserFixture::RANDOM_USER_REFERENCE_5,
            UserFixture::RANDOM_USER_REFERENCE_6,
        ];
        return $this->getReference($userReferences[Factory::create()
            ->numberBetween(0, count($userReferences) - 1)]);
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            UserFixture::class,
            PostFixture::class
        ];
    }
}
