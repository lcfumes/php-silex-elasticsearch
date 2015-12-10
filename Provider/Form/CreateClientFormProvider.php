<?php

namespace Provider\Form;

use Symfony\Component\Validator\Constraints;
use Silex\Application;

class CreateClientFormProvider
{

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create()
    {
        $data = array(
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'age' => '',
        );

        return $this->app['form.factory']->createBuilder('form', $data)
            ->add('first_name', 'text', [
                'constraints' => [new Constraints\NotBlank(), new Constraints\Length(array('min' => 2))]
            ])
            ->add('last_name', 'text', [
                'constraints' => [new Constraints\NotBlank(), new Constraints\Length(array('min' => 2))]
            ])
            ->add('email', 'text', [
                'constraints' => new Constraints\Email()
            ])
            ->add('age', 'text', [
                'constraints' => [new Constraints\NotBlank(), new Constraints\Length(array('min' => 1))]
            ])
            ->getForm();
    }

}