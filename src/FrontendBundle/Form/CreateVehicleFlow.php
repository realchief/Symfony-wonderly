<?php

namespace FrontendBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

/**
 * Class CreateVehicleFlow
 *
 * @package FrontendBundle\Form
 */
class CreateVehicleFlow extends FormFlow
{
    protected function loadStepsConfig()
    {
        return array(
            array(
                'label' => 'organizer',
                'form_type' => 'FrontendBundle\Form\CreateVehicleForm',
            ),
            array(
                'label' => 'event1',
                'form_type' => 'FrontendBundle\Form\CreateVehicleForm',
                'form_options' => array(
                    'validation_groups' => array('Default'),
                ),
                'skip' => function ($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $estimatedCurrentStepNumber > 1 && !$flow->getFormData();
                },
            ),
            array(
                'label' => 'event2',
                'form_type' => 'FrontendBundle\Form\CreateVehicleForm',
            ),
        );
    }
}
