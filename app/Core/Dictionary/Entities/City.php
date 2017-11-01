<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 31/03/2017
 * Time: 13:09
 */

namespace App\Core\Dictionary\Entities;

use App\Core\ValueObjects\Coordinates;
use App\Core\ValueObjects\TranslatableString;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Core\Dictionary\Traits\HasTranslatableName;
use GeoJson\Geometry\Point;
use Ramsey\Uuid\Uuid;

/**
 * Class City
 * @package App\Core\Dictionary\Entities
 *
 * @ODM\Document(
 *     collection="cities",
 *     repositoryClass="App\Core\Dictionary\Repositories\CityRepository",
 *     indexes={
 *          @ODM\Index(keys={"coordinates"="2d"}, options={"unique"=false})
 *     }
 * )
 */
class City
{

    use HasTranslatableName;

    /**
     * @var string
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /** @var TranslatableString
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Core\ValueObjects\TranslatableString"
     * )
     */
    protected $names;

    /**
     * @var Country
     *
     * @ODM\ReferenceOne(
     *     targetDocument="App\Core\Dictionary\Entities\Country"
     * )
     */
    protected $country;

    /**
     * @var Coordinates
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Core\ValueObjects\Coordinates"
     * )
     */
    protected $coordinates;

    /**
     * City constructor.
     * @param array $names
     * @param Country $country
     */
    public function __construct(array $names, Country $country)
    {
        $this->id = Uuid::uuid4();
        $this->setNames($names);
        $this->country = $country;
    }

    /**
     * @param Coordinates $coordinates
     */
    private function setCoordinates(Coordinates $coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCountry() : Country
    {
        return $this->country;
    }

    /**
     * @return Coordinates
     */
    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

}