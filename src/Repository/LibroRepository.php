<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
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
        if ($unidades) {
            $qb->andWhere("l.unidadesVendidas >= :unidades ")
                ->setParameter("unidades", $unidades);
        }

        if ($autor) {
            $qb->andWhere('a.nombre LIKE :nombre')
                ->setParameter('nombre', '%' . $autor . '%');
        }

        return $qb->getQuery()->getResult();


    }

    //Ejemplos de consultas de los contenidos: 

    public function findLibrosSuperVentas(float $minUnidadesVendidas): array
    {
        $dql = "SELECT l
            FROM App\Entity\Libro l
            WHERE l.unidadesVendidas> :minimo";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('minimo', $minUnidadesVendidas)
            ->getResult();
    }

    public function findLibrosSuperVentasQB(string $unidadesMinimas): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.unidadesVendidas> :min')
            ->setParameter('min', $unidadesMinimas)
            ->getQuery()
            ->getResult();
    }

    public function findLibrosPorEditorial(string $editorial): array
    {
        $dql = "SELECT l 
        FROM App\Entity\Libro l
        JOIN l.editorial e
        WHERE e.nombre = :nombreEditorial";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('nombreEditorial', $editorial)
            ->getResult();
    }



    public function findLibrosConEditorial(string $nombreEditorial): array
    {
        $dql = "SELECT l, e
            FROM App\Entity\Libro l
            JOIN l.editorial e
            WHERE e.nombre = :nombre";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('nombre', $nombreEditorial)
            ->getResult();
    }

    public function findLibrosConEditorialQB(): array
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.editorial', 'e')
            ->addSelect('e')
            ->getQuery()
            ->getResult();
    }

    public function findLibrosConAutores(): array
    {
        $dql = "SELECT l, a
            FROM App\Entity\Libro l
            LEFT JOIN l.autores a";

        return $this->getEntityManager()->createQuery($dql)
            ->getResult();
    }



    public function countLibros(): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id) as valor')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalUnidadesVendidas(): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('SUM(l.unidadesVendidas) as valor')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLibrosPorIdOTituloConExpr(int $id, string $titulo): array
    {
        $qb = $this->createQueryBuilder('l');
        $qb->where($qb->expr()->orX(
            $qb->expr()->eq('l.id', ':id'),
            $qb->expr()->like('l.titulo', ':titulo')
        ))
            ->orderBy('l.titulo', 'ASC');


        $qb->setParameters(
            new ArrayCollection([
                new Parameter('id', $id),
                new Parameter('titulo', '%' . $titulo . '%')
            ]
            )
        ); 

        return $qb->getQuery()->getResult();
    }
}
