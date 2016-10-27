<?php

namespace App\Services;

use Validator;

/**
 * Paths service layer.
 *
 * @author  Bogdan Ghervan <bogdan.ghervan@gmail.com>
 * @package App\Services
 */
class Paths
{
    /**
     * Assembles and returns validator.
     *
     * @param array $input
     * @return Validator
     */
    public function makeValidator(array $input)
    {
        $validator = Validator::make($input, [
            'user1' => 'required|alpha_dash',
            'user2' => 'required|alpha_dash'
        ]);

        return $validator;
    }

    public function findPath($user1, $user2) {

    }
}
