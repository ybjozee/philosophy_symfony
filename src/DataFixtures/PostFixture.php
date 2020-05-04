<?php


namespace App\DataFixtures;


use App\Entity\Post;
use App\Utility\Constants;
use App\Utility\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PostFixture extends Fixture implements DependentFixtureInterface
{
    private string $newsAPIToken;
    public const POST_REFERENCE_1 = 'POST_REFERENCE_1';
    public const POST_REFERENCE_2 = 'POST_REFERENCE_2';
    public const POST_REFERENCE_3 = 'POST_REFERENCE_3';
    public const POST_REFERENCE_4 = 'POST_REFERENCE_4';
    public const POST_REFERENCE_5 = 'POST_REFERENCE_5';

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->newsAPIToken = $parameterBag->get('news_token');
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $posts = $this->loadPosts($manager);
        $manager->flush();
        $this->addReferences($posts);
    }

    private function loadPosts(ObjectManager $manager): array
    {
        $posts = [];
        $generator = Factory::create();
        $categories = Constants::CATEGORIES;
        $apiResponseArray = $this->getPosts();

        foreach ($apiResponseArray as $articleArray) {
            $title = $articleArray['title'];
            $post = (new Post())
                ->setTitle($title)
                ->setSlug(Slugger::slugify($generator->sentence(7)))
                ->setAbstract($articleArray['description'] . $generator->sentence)
                ->setContent($articleArray['content'] ?? implode("\n", $generator->paragraphs()))
                ->setAuthor($this->getReference(UserFixture::ADMIN_USER_REFERENCE))
                ->setHeaderImage($articleArray['urlToImage'])
                ->setIsLocalHeader(false)
                ->setReadMoreUrl($articleArray['url'])
                ->setCategory($categories[$generator->numberBetween(0, count($categories) - 1)]);
            $manager->persist($this->addTagsToPost($post));
            $posts[] = $post;
        }
        return $posts;
    }

    private function addReferences(array $posts)
    {
        $this->addReference(self::POST_REFERENCE_1, $posts[0]);
        $this->addReference(self::POST_REFERENCE_2, $posts[1]);
        $this->addReference(self::POST_REFERENCE_3, $posts[2]);
        $this->addReference(self::POST_REFERENCE_4, $posts[3]);
        $this->addReference(self::POST_REFERENCE_5, $posts[4]);
    }

    private function addTagsToPost(Post $post): Post{
        $tags = $this->getTags();
        foreach ($tags as $tag){
            $post->addTag($tag);
        }
        return $post;
    }

    private function getTags()
    {
        $tags = [];
        $generator = Factory::create();
        $referenceArray = [
            TagFixture::TAG_REFERENCE_1,
            TagFixture::TAG_REFERENCE_2,
            TagFixture::TAG_REFERENCE_3,
            TagFixture::TAG_REFERENCE_4,
            TagFixture::TAG_REFERENCE_5,
            TagFixture::TAG_REFERENCE_6,
        ];
        $maxIndex = count($referenceArray) - 1;
        $numberOfTags = $generator->numberBetween(0, $maxIndex);
        for ($i = 0; $i<=$numberOfTags; $i++){
            $tagIndex = $generator->numberBetween(0, $maxIndex);
            $tags[] = $this->getReference($referenceArray[$tagIndex]);
        }
        return $tags;
    }

    private function getPosts()
    {
        $curl = curl_init();

        $baseUrl = "https://newsapi.org/v2/top-headlines?country=us&category=business&sortBy=publishedAt";
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$baseUrl}&apiKey={$this->newsAPIToken}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cookie: __cfduid=d6dbafe066b18187275e698807022b0ae1588441432"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $pattern = '/\[\+[\d]+ chars\]/';
        $replacement = implode("", Factory::create()->paragraphs(7));
        $response = preg_replace($pattern, $replacement, $response);
        return json_decode($response, true)['articles'];
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            UserFixture::class,
            TagFixture::class
        ];
    }
}