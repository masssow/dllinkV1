<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserRegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    /**
     * @param User  $user           Entité déjà hydratée depuis le Form (email/prénom/nom)
     * @param string $plainPassword Mot de passe en clair depuis le Form
     *
     * @throws \Throwable  En cas d'erreur Doctrine, la transaction est rollback
     */
    public function register(
        User $user,
        string $plainPassword,
    ): User {
        // 1) Normaliser l’e-mail (défense en profondeur, en plus du contrôleur/FormEvents)
        $user->setEmail(strtolower(trim((string) $user->getEmail())));
        

        // 2) Hash du mot de passe
        $user->setPassword(
            $this->hasher->hashPassword($user, $plainPassword)
        );

     
         // 3) Persister l’utilisateur
            $this->em->persist($user);
            $this->em->flush();
   

        return $user;
    }
}
