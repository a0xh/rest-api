<?php declare(strict_types=1);

namespace App\Services;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

final class AuthService implements UserProvider
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function retrieveById($identifier)
    {
        return $this->em->find(User::class, $identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        // Не используется в JWT
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Не используется в JWT
    }

    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'];

        return $this->em
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $password = $credentials['password'];

        return Hash::check($password, $user->getPassword());
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        if (Hash::needsRehash($user->getPassword())) {
            $user->setPassword(Hash::make($user->getPassword()));
            $this->em->persist($user);
            $this->em->flush();
        }
    }
}