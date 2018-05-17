<?php

namespace App\AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\Container;

class PolicyValidator extends ConstraintValidator
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

        if (preg_match('/^[A-Z0-9]{4,}$/i', $value)) {
            $isInSystem = $this->container->get('app.car_repository')->findBy(['policyNumber' => $value]);

            if(count($isInSystem) > 0) {
                if($entity->getId() && $isInSystem[0]->getId() == $entity->getId()) {
                    return;
                }
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
            }
        } else {
            $this->context->buildViolation('Numer polisy "{{ string }}" jest nieprawidłowy!!')
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}