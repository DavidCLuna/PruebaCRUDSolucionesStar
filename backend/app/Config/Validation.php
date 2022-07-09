<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation extends BaseConfig
{
    //--------------------------------------------------------------------
    // Setup
    //--------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    public $cantantes = [
        'nombre_natural' => 'required|min_length[3]|max_length[100]',
        'nombre_artistico' => 'min_length[3]|max_length[100]',
        'genero_musical' => 'min_length[3]|max_length[100]',
        'edad' => 'greater_than_equal_to[1]|less_than_equal_to[100]',
        'pais_nacimiento' => 'min_length[3]|max_length[100]'
    ];
    //--------------------------------------------------------------------
    // Rules
    //--------------------------------------------------------------------
}
