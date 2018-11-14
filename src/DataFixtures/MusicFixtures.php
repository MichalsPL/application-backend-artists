<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 11/14/18
 * Time: 8:46 PM
 */

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use App\Utils\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MusicFixtures extends Fixture
{


  private function setSongs(object $albumData, Album $album, ObjectManager $manager)
  {
    if ($albumData->songs) {
      foreach ($albumData->songs as $songData) {
        $song = new Song();
        $song->setAlbum($album);
        $song->setTitle($songData->title);

        $splitToMinutesAndSeconds = explode(':', $songData->length);
        $song->setLength(\DateInterval::createFromDateString($splitToMinutesAndSeconds[0] . ' minutes + ' . $splitToMinutesAndSeconds[1] . ' seconds'));
        $manager->persist($song);
        $manager->flush();
      }
    }
  }

  private function setAlbums(object $artistData, Artist $artist, ObjectManager $manager)
  {
    if ($artistData->albums) {
      foreach ($artistData->albums as $albumData) {
        $album = new Album();
        $album->setToken(TokenGenerator::generateUnique($manager, 'App\Entity\Album', 6));
        $album->setTitle($albumData->title);
        $album->setCover($albumData->cover);
        $album->setDescription($albumData->description);
        $album->setArtist($artist);
        $manager->persist($album);
        $manager->flush();
        $this->setSongs($albumData, $album, $manager);
      }
    }
  }

  private function setArtists(array $data, $manager)
  {
    foreach ($data as $artistData) {
      $artist = new Artist();
      $artist->setToken(TokenGenerator::generateUnique($manager, 'App\Entity\Artist', 6));
      $artist->setName($artistData->name);
      $manager->persist($artist);
      $manager->flush();
      $this->setAlbums($artistData, $artist, $manager);
    }
  }

  public function load(ObjectManager $manager)
  {
    $jsonData = file_get_contents('https://gist.githubusercontent.com/fightbulc/9b8df4e22c2da963cf8ccf96422437fe/raw/8d61579f7d0b32ba128ffbf1481e03f4f6722e17/artist-albums.json');
    $data = json_decode($jsonData);
    if (!$data) {
      die('no data to import');
    }
    $this->setArtists($data, $manager);

  }
}