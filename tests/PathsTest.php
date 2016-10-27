<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class PathsTest extends TestCase
{
    public function testPathOfLengthOne()
    {
        $this->get('/paths/bogdanghervan/taylorotwell')
             ->seeJson([
                 'length' => 1
             ])
             ->assertResponseOk();
    }

    public function testPathOfGreaterLength()
    {
        $this->json('GET', '/paths/bogdanghervan/GrahamCampbell')
             ->seeJson([
                 'length' => 2
             ])
             ->assertResponseOk();
    }

    public function testUserDoesntExist()
    {
        $this->json('GET', '/paths/NOSUCHUSER/bogdanghervan')
             ->seeJsonStructure([
                 'error' => [
                     'code', 'message', 'param'
                 ]
             ])
             ->seeJson([
                 'code' => 'InvalidParameter',
                 'param' => 'startUser'
             ])
             ->assertResponseStatus(400);

        $this->json('GET', '/paths/bogdanghervan/NOSUCHUSER')
             ->seeJsonStructure([
                 'error' => [
                     'code', 'message', 'param'
                 ]
             ])
             ->seeJson([
                 'code' => 'InvalidParameter',
                 'param' => 'endUser'
             ])
             ->assertResponseStatus(400);
    }

    public function testInvalidUser()
    {
        $this->json('GET', '/paths/(*&(*&/12345')
             ->seeJson([
                 'error' => [
                     'code' => 'InvalidParameter',
                     'message' => 'The start user may only contain letters, numbers, and dashes.',
                     'param' => 'startUser'
                 ]
             ])
             ->assertResponseStatus(400);

        $this->json('GET', '/paths/12345/(*&(*&')
             ->seeJson([
                 'error' => [
                     'code' => 'InvalidParameter',
                     'message' => 'The end user may only contain letters, numbers, and dashes.',
                     'param' => 'endUser'
                 ]
             ])
             ->assertResponseStatus(400);
    }

    public function testGivenUsersAreTheSame()
    {
        $this->json('GET', '/paths/bogdanghervan/bogdanghervan')
             ->seeJsonEquals([
                 'length' => 0,
                 'segments' => []
             ])
             ->assertResponseOk();
    }

    public function testOneUserMissing()
    {
        $this->json('GET', '/paths/onlyStartUserGiven')
             ->seeJsonEquals([
                 'error' => [
                     'code' => 'ResourceNotFound',
                     'message' => 'Resource not found.'
                 ]
             ])
             ->assertResponseStatus(404);

        $this->json('GET', '/paths//onlyEndUserGiven')
             ->seeJsonEquals([
                 'error' => [
                     'code' => 'ResourceNotFound',
                     'message' => 'Resource not found.'
                 ]
             ])
             ->assertResponseStatus(404);
    }

    public function testWrongHttpVerb()
    {
        $this->post('/paths/bogdanghervan/taylorotwell')
            ->seeJsonEquals([
                'error' => [
                    'code' => 'MethodNotAllowed',
                    'message' => 'Requested method is not supported.'
                ]
            ])
            ->assertResponseStatus(405);
    }
}
