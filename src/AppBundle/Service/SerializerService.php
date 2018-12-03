<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.12.2018
 * Time: 1:26
 */

namespace AppBundle\Service;


use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    private $serializer;

    public function __construct()
    {
        $objectNormalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
        $normalizers = [$objectNormalizer, new DateTimeNormalizer()];
        $encoders = [new JsonEncoder()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

}