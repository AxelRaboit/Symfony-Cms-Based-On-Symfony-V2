<?php

namespace App\Repository;

use App\Entity\UserBackend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<UserBackend>
 *
 * @implements PasswordUpgraderInterface<UserBackend>
 *
 * @method UserBackend|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBackend|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBackend[]    findAll()
 * @method UserBackend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBackendRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBackend::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserBackend) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByCriteria(string $criteria, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT u FROM App\Entity\UserBackend u WHERE u.email LIKE :criteria OR u.username LIKE :criteria OR u.id LIKE :criteria ORDER BY u.id ' . $order);
        $query->setParameter('criteria', '%' . $criteria . '%');

        return $query->getResult();
    }

    public function findAllOrderBy(string $order = 'ASC'): array
    {
        $query = $this->em()->createQuery('SELECT u FROM App\Entity\UserBackend u ORDER BY u.id ' . $order);

        return $query->getResult();
    }

    // base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
