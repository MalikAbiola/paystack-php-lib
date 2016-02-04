<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 22:04
 * IDE: PhpStorm
 */

namespace Paystack\Contracts;


interface ResourceInterface
{

    public function get($id);

    public function getAll();

    public function save($body);

    public function update($id, $body);

    public function delete($id);
}