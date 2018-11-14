<?php

namespace App\Utils;

use Doctrine\ORM\EntityManager;
use RandomLib\Factory;

class TokenGenerator
{
  const LENGTH = 16;
  const VOCAB = 'ABCDEFGHIJKLMNOPQRSTUVXYZ0123456789';

  public static function generate(int $length = self::LENGTH, string $vocab = self::VOCAB): string
  {
    $generator = (new Factory())->getMediumStrengthGenerator();

    return $generator->generateString($length, $vocab);
  }

  public static function generateUnique(EntityManager $manager, string $className, int $length = self::LENGTH, string $vocab = self::VOCAB)
  {
    $isNotUnique = true;
    while ($isNotUnique) {
      $token = self::generate($length, $vocab);
      $isNotUnique = $manager->getRepository($className)->findOneBy(['token' => $token]);
    }
    return $token;
  }
}