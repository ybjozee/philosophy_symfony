<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getFeaturedPosts(){
        return $this->findBy([], ['id' => 'DESC'], 3);
    }

    public function getPage(int $page = 1, Tag $tag = null): Paginator
    {
        $qb = $this->createQueryBuilder('post')
            ->addSelect('a', 't')
            ->innerJoin('post.author', 'a')
            ->leftJoin('post.tags', 't')
            ->orderBy('post.publishedAt', 'DESC');
        if ($tag !== null) {
            $qb->andWhere(':tag MEMBER OF post.tags')
                ->setParameter('tag', $tag);

        }
        return (new Paginator($qb))->paginate($page);
    }
    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
