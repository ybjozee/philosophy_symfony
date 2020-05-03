<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;
    public const ADMIN_USER_REFERENCE = 'ADMIN_USER';
    public const RANDOM_USER_REFERENCE_1 = 'RANDOM_USER_REFERENCE_1';
    public const RANDOM_USER_REFERENCE_2 = 'RANDOM_USER_REFERENCE_2';
    public const RANDOM_USER_REFERENCE_3 = 'RANDOM_USER_REFERENCE_3';
    public const RANDOM_USER_REFERENCE_4 = 'RANDOM_USER_REFERENCE_4';
    public const RANDOM_USER_REFERENCE_5 = 'RANDOM_USER_REFERENCE_5';
    public const RANDOM_USER_REFERENCE_6 = 'RANDOM_USER_REFERENCE_6';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $adminUser = $this->getMainUser($manager);
        $randomUsers = $this->loadRandomUsers($manager);
        $manager->flush();
        $this->addReferences($adminUser, $randomUsers);
    }

    private function getMainUser(ObjectManager $manager): User
    {
        $user = (new User())->createFromArray([
            'username' => 'ybjozee',
            'email' => 'test@test.com',
            'roles' => [User::ADMIN_ROLE]
        ]);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, 'test123'));
        $manager->persist($user);

        return $user;
    }

    private function loadRandomUsers(ObjectManager $manager): array
    {
        $randomUsers = [];
        for ($i = 0; $i < 10; $i++) {
            $dummyUser = $this->generateDummyUser();
            $manager->persist($dummyUser);
            $randomUsers[] = $dummyUser;
        }
        return $randomUsers;
    }

    private function generateDummyUser(): User
    {
        $faker = Factory::create();
        $userArray = [
            'username' => $faker->userName,
            'email' => $faker->email,
            'roles' => [
                $faker->boolean(70) ?
                    User::ADMIN_ROLE : User::USER_ROLE
            ]
        ];

        $user = (new User())->createFromArray($userArray);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $faker->word));
        return $user;
    }

    /**
     * @param User $adminUser
     * @param array $randomUsers
     */
    private function addReferences(User $adminUser, array $randomUsers): void
    {
        $this->addReference(self::ADMIN_USER_REFERENCE, $adminUser);
        $this->addReference(self::RANDOM_USER_REFERENCE_1, $randomUsers[0]);
        $this->addReference(self::RANDOM_USER_REFERENCE_2, $randomUsers[2]);
        $this->addReference(self::RANDOM_USER_REFERENCE_3, $randomUsers[4]);
        $this->addReference(self::RANDOM_USER_REFERENCE_4, $randomUsers[5]);
        $this->addReference(self::RANDOM_USER_REFERENCE_5, $randomUsers[7]);
        $this->addReference(self::RANDOM_USER_REFERENCE_6, $randomUsers[8]);
    }

}