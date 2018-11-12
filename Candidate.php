<?php

require_once 'CandidateAbstract.php';
require_once 'Toolkit.php';

class Candidate extends CandidateAbstract
{
  public function run()
  {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'];
    $telephone = $data['telephone'];
    $address = $data['address'];

    $coordsCandidate = Toolkit::getCoords($address);

    $result = $this->calculateDistance($coordsCandidate);

    $result['telephone'] = Toolkit::getFormattedPhone($telephone);
    $result['name'] = $name;

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
  }

  protected function calculateDistance($coordsCandidate)
  {
    $latitude = $coordsCandidate['lat'];
    $longitude = $coordsCandidate['lng'];

    $r_distance = null;
    $r_city = null;

    $points = $this->getPoints();

    foreach ($points as $point) {
      $distance = $this->getDistance(
        $latitude, $point['latitude'],
        $longitude, $point['longitude']
      );
      if (is_null($r_distance) || $distance < $r_distance) {
        $r_city = $point['name'];
        $r_distance = round($distance, 1);
      }
    }

    return [
      'distance' => $r_distance,
      'city' => $r_city
    ];
  }

  private function getDistance($lat1, $lat2, $lng1, $lng2)
  {
    $lat1 = $lat1 * M_PI / 180;
    $lat2 = $lat2 * M_PI / 180;
    $long1 = $lng1 * M_PI / 180;
    $long2 = $lng2 * M_PI / 180;

    $cl1 = cos($lat1);
    $cl2 = cos($lat2);
    $sl1 = sin($lat1);
    $sl2 = sin($lat2);
    $delta = $long2 - $long1;
    $cdelta = cos($delta);
    $sdelta = sin($delta);

    $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
    $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

    $ad = atan2($y, $x);
    return $ad * 6372795 / 1000;
  }

  private function getPoints()
  {
    return $this->getPointsFromDB();
  }

  private function getPointsFromDB()
  {
    $points = [];

    $dbh = new PDO('mysql:host=127.0.0.1;dbname=runner_hh', 'secret', 'secret',[
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);

    foreach($dbh->query('SELECT * from points') as $row) {
      $points[] = $row;
    }

    return $points;
  }

  // for tests
  private function getPointsFromJSON()
  {
    return json_decode(file_get_contents('db.json'), true);
  }
}
