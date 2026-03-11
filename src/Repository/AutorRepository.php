<?php

namespace App\Repository;

use App\Entity\Autor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autor>
 */
class AutorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autor::class);
    }

    //    /**
    //     * @return Autor[] Returns an array of Autor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Autor
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //Ejemplos de los contenidos
    public function findAutoresDesdeFecha(\DateTimeInterface $fecha): array
    {
        $dql = "SELECT a 
            FROM App\Entity\Autor a 
            WHERE a.fechaNacimiento >= :fecha";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('fecha', $fecha)
            ->getResult();
    }

    public function findAutoresSuperVentas(int $min): array
    {
        $dql = "SELECT DISTINCT a, l.unidadesVendidas as valor
        FROM App\Entity\Autor a
        JOIN a.libros l
        WHERE l.unidadesVendidas > :min";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('min', $min)
            ->getResult();

    }

    public function findAutoresYCountLibros(): array
    {
        $dql = "SELECT a, COUNT(l.id) as valor
FROM App\Entity\Autor a
JOIN a.libros l
GROUP BY a.id";

        return $this->getEntityManager()->createQuery($dql)->getResult();

    }


    public function findAutoresYCountLibrosGroupByQB(): array
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('a, COUNT(l.id) AS valor')
            ->join('a.libros', 'l')
            ->groupBy('a.id');

        return $qb->getQuery()->getResult();


    }
 //En AutorRepository
    public function findAutoresYCountLibrosSubconsultaByQB(): array
    {
        //Para poder generar una subconsulta de otra entidad dentro de un repositorio, hay que crear un nuevo QueryBuilder a través del EntityManager, y luego incluir su DQL dentro del QueryBuilder principal.
        $subQuery = $this->getEntityManager()
        ->createQueryBuilder()
        ->select('l')
            ->from('App\Entity\Libro', 'l')
            ->select('COUNT(l.id)')
            ->join('l.autores', 'a2')
            ->where('a2 = a');

        $qb = $this->createQueryBuilder('a')
            //->select('a.id, a.nombre')
            ->addSelect('(' . $subQuery->getDQL() . ') AS valor');

      return $qb->getQuery()->getResult();


    }
}
