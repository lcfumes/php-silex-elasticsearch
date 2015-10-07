<?php

namespace Provider\Form;

use Symfony\Component\Validator\Constraints;
use Silex\Application;

class SearchClientFormProvider
{

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create()
    {
        // some default data for when the form is displayed the first time
        $data = array(
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'age' => '',
        );

        return $this->app['form.factory']->createBuilder('form', $data)
            ->add('first_name', 'text', ['required' => false])
            ->add('last_name', 'text', ['required' => false])
            ->add('email', 'text', ['required' => false])
            ->add('age', 'text', ['required' => false])
            ->getForm();
    }

}