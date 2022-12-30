<?php

namespace App\Repository;

use App\Entity\Auteur;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function save(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByTitle(string $titre): array{
       return $this->createQueryBuilder('l')
           ->andWhere('l.titre LIKE :titre')
           ->setParameter('titre', "%{$titre}%")
           ->orderBy('l.titre', 'ASC')
           ->getQuery()
           ->getResult()
       ;
    }

    public function findByAuteur(Auteur $auteur): array
    {
       return $this->createQueryBuilder('l')
           ->join('l.auteur', 'a')
           ->andWhere('a.id = :id')
           ->setParameter('id', $auteur->getId())
           ->orderBy('l.titre', 'ASC')
           ->getQuery()
           ->getResult()
       ;
    }

    public function findByNomGenre(string $nomGenre): array
    {
       return $this->createQueryBuilder('l')
           ->join('l.genres', 'g')
           ->andWhere('g.nom LIKE :nomGenre')
           ->setParameter('nomGenre', "%{$nomGenre}%")
           ->orderBy('l.titre', 'ASC')
           ->getQuery()
           ->getResult()
       ;
    }
//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
