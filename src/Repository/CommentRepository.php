<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getPage(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('comment')
            ->addSelect('author', 'post')
            ->innerJoin('comment.author', 'author')
            ->innerJoin('comment.post', 'post')
            ->orderBy('comment.publishedAt', 'DESC');
        return (new Paginator($qb))->paginate($page);
    }

    public function getCommentsForPost(int $post)
    {
        return $this->createQueryBuilder('comment')
            ->where('comment.post = :post')
            ->setParameter('post', $post)
            ->orderBy('comment.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
