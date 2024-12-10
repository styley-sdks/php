<?php

use PHPUnit\Framework\TestCase;
use PingPong\Models\Models;
use PingPong\Models\Model;
use PingPong\HttpClient\HttpClient;

class ModelsTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testList()
    {
        $httpClient = $this->createMock(HttpClient::class);

        // Simulated API response
        $response = [
            [
                'id' => 'model_id',
                'name' => 'model_name',
                'description' => 'description',
                'args' => [
                    'class_names' => [
                        'type' => 'string',
                        'optional' => 1,
                        'example' => 'dog, eye, tongue, ear, leash, backpack, person, nose',
                        'description' => 'Enter the classes to be detected, separated by comma',
                        'value' => 'dog, eye, tongue, ear, leash, backpack, person, nose',
                        'json_path' => 'input.class_names',
                    ],
                    'input_media' => [
                        'type' => 'file',
                        'file_type' => 'image',
                        'description' => 'Path to the input image or video',
                        'json_path' => 'input.input_media',
                    ],
                    'max_num_boxes' => [
                        'type' => 'integer',
                        'min' => 1,
                        'max' => 300,
                        'optional' => 1,
                        'example' => 100,
                        'description' => 'Maximum number of bounding boxes to display',
                        'value' => 100,
                        'json_path' => 'input.max_num_boxes',
                    ],
                ],
            ]
        ];

        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/v1/models')
            ->willReturn($response);

        $models = new Models($httpClient);
        $result = $models->list();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);

        $model = $result[0];
        $this->assertEquals('model_id', $model->id);
        $this->assertEquals('model_name', $model->name);
        $this->assertEquals('description', $model->description);

        $args = $model->args;
        $this->assertIsArray($args);
        $this->assertArrayHasKey('class_names', $args);
        $this->assertArrayHasKey('input_media', $args);
        $this->assertArrayHasKey('max_num_boxes', $args);

        $classNames = $args['class_names'];
        $this->assertIsArray($classNames);
        $this->assertEquals('string', $classNames['type']);
        $this->assertEquals('dog, eye, tongue, ear, leash, backpack, person, nose', $classNames['value']);
        $this->assertEquals('Enter the classes to be detected, separated by comma', $classNames['description']);

        $inputMedia = $args['input_media'];
        $this->assertIsArray($inputMedia);
        $this->assertEquals('file', $inputMedia['type']);
        $this->assertEquals('image', $inputMedia['file_type']);

        $maxNumBoxes = $args['max_num_boxes'];
        $this->assertIsArray($maxNumBoxes);
        $this->assertEquals(100, $maxNumBoxes['value']);
        $this->assertEquals(300, $maxNumBoxes['max']);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetById()
    {
        $httpClient = $this->createMock(HttpClient::class);
        $modelId = 'model_id';
        $response = [
            'id' => 'model_id',
            'name' => 'model_name',
            'description' => 'model_description',
            'args' => ['arg1', 'arg2']
        ];

        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/v1/models/' . $modelId)
            ->willReturn($response);

        $models = new Models($httpClient);
        $model = $models->getById($modelId);

        $this->assertInstanceOf(Model::class, $model);
        $this->assertEquals('model_id', $model->id);
        $this->assertEquals('model_name', $model->name);
        $this->assertEquals('model_description', $model->description);
        $this->assertEquals(['arg1', 'arg2'], $model->args);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetModelByName()
    {
        $httpClient = $this->createMock(HttpClient::class);

        $modelName = 'model_name';
        $response = [
            'id' => 'model_id',
            'name' => 'mode_name',
            'description' => 'model_description',
            'args' => [
                'class_names' => [
                    'type' => 'string',
                    'optional' => 1,
                    'example' => 'dog, eye, tongue, ear, leash, backpack, person, nose',
                    'description' => 'Enter the classes to be detected, separated by comma',
                    'value' => 'dog, eye, tongue, ear, leash, backpack, person, nose',
                    'json_path' => 'input.class_names',
                ],
                'input_media' => [
                    'type' => 'file',
                    'file_type' => 'image',
                    'description' => 'Path to the input image or video',
                    'json_path' => 'input.input_media',
                ],
            ],
        ];

        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/v1/models/name/'.$modelName)
            ->willReturn($response);
        $models = new Models($httpClient);
        $result = $models->getByName($modelName);

        $this->assertInstanceOf(Model::class, $result);
        $this->assertEquals('model_id', $result->id);
        $this->assertEquals('mode_name', $result->name);
        $this->assertEquals('model_description', $result->description);

        $args = $result->args;
        $this->assertIsArray($args);

        $classNames = $args['class_names'];
        $this->assertEquals('string', $classNames['type']);
        $this->assertEquals('dog, eye, tongue, ear, leash, backpack, person, nose', $classNames['value']);
        $this->assertEquals('Enter the classes to be detected, separated by comma', $classNames['description']);

        $inputMedia = $args['input_media'];
        $this->assertEquals('file', $inputMedia['type']);
        $this->assertEquals('image', $inputMedia['file_type']);
        $this->assertEquals('Path to the input image or video', $inputMedia['description']);
    }

}