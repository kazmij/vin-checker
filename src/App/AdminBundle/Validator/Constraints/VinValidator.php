<?php

namespace App\AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\Container;

class VinValidator extends ConstraintValidator
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        $this->request = $container->get('request_stack')->getCurrentRequest();
    }

    public function validate($value, Constraint $constraint)
    {
        $entity = $this->context->getObject();

        if (preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $value)) {
            $isInSystem = $this->container->get('app.car_repository')->findBy(['vin' => $value]);

            if(count($isInSystem) > 0) {
                if($entity->getId() && $isInSystem[0]->getId() == $entity->getId()) {
                    return;
                }
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
            }
        } else {
            $this->context->buildViolation('Numer VIN "{{ string }}" jest nieprawidłowy!!')
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}