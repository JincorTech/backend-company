<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 31/03/2017
 * Time: 13:36
 */

namespace App\Core\ValueObjects;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use GeoJson\Geometry\Point;

/**
 * Class Coordinates
 *
 * @package App\Core\ValueObjects
 *
 * @ODM\EmbeddedDocument
 */
class Coordinates
{

    /**
     * @var float
     * @ODM\Field(type="float")
     */
    public $x;

    /**
     * @var float
     * @ODM\Field(type="float")
     */
    public $y;

    /**
     * Make new location object to store in database
     *
     * Location constructor.
     * @param Point $point
     */
    public function __construct(Point $point)
    {
        $this->x = $point->getCoordinates()[0];
        $this->y = $point->getCoordinates()[1];
    }


}