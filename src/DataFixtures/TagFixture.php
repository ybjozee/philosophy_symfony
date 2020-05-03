<?php


namespace App\DataFixtures;


use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TagFixture extends Fixture
{
    public const TAG_REFERENCE_1 = 'TAG_REFERENCE_1';
    public const TAG_REFERENCE_2 = 'TAG_REFERENCE_2';
    public const TAG_REFERENCE_3= 'TAG_REFERENCE_3';
    public const TAG_REFERENCE_4 = 'TAG_REFERENCE_4';
    public const TAG_REFERENCE_5 = 'TAG_REFERENCE_5';
    public const TAG_REFERENCE_6 = 'TAG_REFERENCE_6';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $tags = $this->loadTags($manager);
        $manager->flush();
        $this->addReferences($tags);
    }

    private function loadTags(ObjectManager $manager) : array
    {
        $tags = [];
        for ($i = 0; $i < 20; $i++) {
            $dummyTag = $this->generateDummyTag();
            $manager->persist($dummyTag);
            $tags[] = $dummyTag;
        }
        return $tags;
    }

    private function addReferences(array $tags) {
        $this->addReference(self::TAG_REFERENCE_1, $tags[0]);
        $this->addReference(self::TAG_REFERENCE_2, $tags[2]);
        $this->addReference(self::TAG_REFERENCE_3, $tags[3]);
        $this->addReference(self::TAG_REFERENCE_4, $tags[5]);
        $this->addReference(self::TAG_REFERENCE_5, $tags[7]);
        $this->addReference(self::TAG_REFERENCE_6, $tags[8]);
    }

    private function generateDummyTag(): Tag
    {
        return (new Tag())->setName(Factory::create()->firstNameFemale);
    }
}