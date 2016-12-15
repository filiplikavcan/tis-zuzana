<?php

include_once 'cleopas/code/Libraries/ImageUrlBuilder.php';
include_once 'vendor/autoload.php';

$allowed_dirs = array('assets');

class ImageServer extends League\Glide\Server
{
    protected $source_paths = array();

    public function getSourcePath($path)
    {
        if (empty($this->source_paths[$path]))
        {
            $this->source_paths[$path] = $this->processPath($path, function($params_query, $dirs) {
                if ($params_query !== false)
                {
                    $last_index = count($dirs) - 1;
                    $dirs[$last_index] = preg_replace('/\.[^\.]+(\.[^\.]+)$/', '$1', $dirs[$last_index]);

                    unset($dirs[0]);
                }

                return implode('/', $dirs);
            });
        }

        return $this->source_paths[$path];
    }

    public function retrieveParamsFromPath($path)
    {
        return $this->processPath($path, function($params_query, $dirs) {
            $result = array();

            if ($params_query !== false)
            {
                parse_str($params_query, $result);

                $last_index = count($dirs) - 1;

                preg_match('/\.([^\.]+)\.[^\.]+$/', $dirs[$last_index], $matches);

                if (count($matches) == 2)
                {
                    $result['signature'] = $matches[1];
                }
            }

            return $result;
        });
    }

    protected function processPath($path, $result_callback)
    {
        $params_query = false;
        $dirs = explode('/', $path);

        if (strpos($dirs[0], 'it-') === 0)
        {
            $raw = substr($dirs[0], 3);
            $encoded = str_pad($raw, floor(strlen($raw) / 4) * 4, '=', STR_PAD_RIGHT);

            $payload = base64_decode($encoded);

            if ($payload === false || !mb_check_encoding($payload, 'ASCII') || !preg_match('/^[a-zA-Z0-9]+$/', $raw))
            {
                throw new Exception('Image transformation payload is invalid.');
            }
            else
            {
                $payload_parts = explode('|', $payload);

                if (count($payload_parts) != 2 || (int) $payload_parts[0] != strlen($payload_parts[1]))
                {
                    throw new Exception('Image transformation query is invalid.');
                }
                else
                {
                    $params_query = $payload_parts[1];
                }
            }
        }

        return $result_callback($params_query, $dirs);
    }

    public function getCachePath($path, array $params = [])
    {
        return $path;
    }
}

// Set source filesystem
$source = new League\Flysystem\Filesystem(
    new League\Flysystem\Adapter\Local(__DIR__ . '/')
);

// Set cache filesystem
$cache = new League\Flysystem\Filesystem(
    new League\Flysystem\Adapter\Local(__DIR__ . '/images/')
);

// Set image manager
$imageManager = new Intervention\Image\ImageManager([
    'driver' => 'gd',
]);

// Set manipulators
$manipulators = array(
    new League\Glide\Manipulators\Orientation(),
    new League\Glide\Manipulators\Crop(),
    new League\Glide\Manipulators\Size(2000*2000),
    new League\Glide\Manipulators\Encode(),
);

// Set API
$api = new League\Glide\Api\Api($imageManager, $manipulators);

// Setup Glide server
$server = new ImageServer(
    $source,
    $cache,
    $api
);

try
{
    $prefix = '/images';

    $url = parse_url($_SERVER['REQUEST_URI']);

    $path = trim(substr($url['path'], strlen($prefix)), '/');
    $source_path = $server->getSourcePath($path);

    $allow = false;

    foreach ($allowed_dirs as $allowed_dir)
    {
        if (strpos($source_path, $allowed_dir . '/') === 0)
        {
            $allow = true;
            break;
        }
    }

    if ($allow)
    {
        $params = $server->retrieveParamsFromPath($path);

        if (!empty($params['signature']))
        {
            $image_url_builder = new ImageUrlBuilder();
            $image_url_builder->setRootDir(__DIR__);

            $url_params = $params;
            unset($url_params['signature']);

            $signed_path = $image_url_builder->url($source_path, $url_params);

            if ($signed_path != $url['path'])
            {
                throw new Exception('Image modification signature is invalid.');
            }
        }

        $server->outputImage($path, $params);
    }
    else
    {
        throw new Exception('Image directory is not allowed.');
    }
}
catch (Exception $e)
{
    header("HTTP/1.0 404 Not Found");
    exit;
}

