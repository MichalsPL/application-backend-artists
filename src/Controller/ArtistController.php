<?php

namespace App\Controller;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArtistController extends AbstractController
{
  /**
   * @return JsonResponse
   */
  public function showArtists()
  {


    return new JsonResponse(
      [
        $this->prepareArtistData()
      ],
      JsonResponse::HTTP_CREATED
    );

  }

  private function getAlbumsAsArray(Artist $artist)
  {
    $albums = $artist->getAlbums();
    $result = [];
    foreach ($albums as $album) {
      $result[] = [
        'token' => $album->getToken(),
        'title' => $album->getTitle(),
        'cover' => $album->getCover()
      ];
    }
    return $result;
  }

  private function prepareArtistData(string $token = null)
  {
    if ($token) {
      $artists[] = $this->getDoctrine()
        ->getRepository(Artist::class)
        ->findOneBy(['token' => $token]);
    } else {
      $artists = $this->getDoctrine()
        ->getRepository(Artist::class)
        ->findAll();
    }
    if (!$artists || !$artists[0]) {
      return ['error' => 'no data'];
    }
    $result = [];
    foreach ($artists as $artist) {
      $result[] = [
        'name' => $artist->getName(),
        'token' => $artist->getToken(),
        'albums' => $this->getAlbumsAsArray($artist)
      ];
    }
    ['error' => 'no data'];

    return $result;
  }

  public function showArtist($token)
  {

    return new JsonResponse(
      [
        $this->prepareArtistData($token)
      ],
      JsonResponse::HTTP_CREATED
    );

  }
}
