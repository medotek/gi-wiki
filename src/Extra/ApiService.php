<?php


namespace App\Extra;


use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateAndCreate($data, $entityClassName){

        $objectNormalizer = new ObjectNormalizer();
        $normalizers = [$objectNormalizer];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        $result = $serializer->deserialize($data, $entityClassName, 'json');
        $errors = $this->validator->validate($result);

        if(count($errors) > 0){
            $result = 'error';
        }

        return $result;

    }

}
