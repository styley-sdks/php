## PingPong PHP SDK

### Example

```php

use \PingPong\Client;

$client = new Client();
$deployment = $client->deployments->create(new \PingPong\Deployments\DeploymentInput(
    'my-deployment',
    'pingpongai/<model-name>',
    'input_image_file' => '<url>/my-file.jpg'
));

```
