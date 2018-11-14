<?php

namespace App\Controller;

use App\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class AlbumController extends AbstractController
{
  public function showAlbum($token)
  {
    $album = $this->getDoctrine()
      ->getRepository(Album::class)
      ->findOneBy(['token' => $token]);

    if (!$album) {
      $result = ['error' => 'no album'];
    } else {
      $result = [
        'token' => $album->getToken(),
        'title' => $album->getTitle(),
        'description' => $album->getDescription(),
        'cover' => $album->getCover(),
        'artist' => ['token' => $album->getArtist()->getToken(), 'name' => $album->getArtist()->getName()],
        'songs' => $this->getSongsAsArray($album)
      ];
    }

    return new JsonResponse(
      [
        $result
      ],
      JsonResponse::HTTP_CREATED
    );
  }

  private function getSongsAsArray(Album $album)
  {
    $songs = $album->getSongs();
    $result = [];
    foreach ($songs as $song) {
      $result[] = [
        'title' => $song->getTitle(),
        'cover' => $song->getLength()->format('I')
      ];
    }
    return $result;
  }
}
