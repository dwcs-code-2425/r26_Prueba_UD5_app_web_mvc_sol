<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Libro>
 */
class LibroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libro::class);
    }

    //    /**
    //     * @return Libro[] Returns an array of Libro objects
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

    //    public function findOneBySomeField($value): ?Libro
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    //En LibroRepository
public function buscarLibros(?string $titulo, ?int $unidades, ?string $autor): array
    {
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.autores', 'a')
            ->addSelect("a");
           

        if ($titulo) {
            $qb->andWhere('l.titulo LIKE :titulo')
                ->setParameter('titulo', '%' . $titulo . '%');
        }
        if($unidades){
            $qb->andWhere("l.unidadesVendidas >= :unidades ")
            ->setParameter("unidades", $unidades);
        }

          if ($autor) {
            $qb->andWhere('a.nombre LIKE :nombre')
                ->setParameter('nombre', '%' . $autor . '%');
        }

       return  $qb->getQuery()->getResult();

       
    }
}
