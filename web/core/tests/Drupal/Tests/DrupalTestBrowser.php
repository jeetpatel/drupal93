<?php

namespace Drupal\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\Response;

/**
 * Enables a BrowserKitDriver mink driver to use a Guzzle client.
 *
 * This code is heavily based on the following projects:
 * - https://github.com/FriendsOfPHP/Goutte
 * - https://github.com/minkphp/MinkGoutteDriver
 */
class DrupalTestBrowser extends AbstractBrowser
{
  /**
   * The Guzzle client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
    protected $client;

    /**
     * Sets the Guzzle client.
     *
     * @param \GuzzleHttp\ClientInterface $client
     *   The Guzzle client.
     *
     * @return $this
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        if ($this->getServerParameter('HTTP_HOST', null) !== null || $base_uri = $client->getConfig('base_uri') === null) {
            return $this;
        }

        $path = $base_uri->getPath();
        if ($path !== '' && $path !== '/') {
            throw new \InvalidArgumentException('Setting a path in the Guzzle "base_uri" config option is not supported by DrupalTestBrowser.');
        }

        if ($this->getServerParameter('HTTPS', null) === null && $base_uri->getScheme() === 'https') {
            $this->setServerParameter('HTTPS', 'on');
        }

        $host = $base_uri->getHost();
        if (($port = $base_uri->getPort()) !== null) {
            $host .= ":$port";
        }

        $this->setServerParameter('HTTP_HOST', $host);

        return $this;
    }

    /**
     * Gets the Guzzle client.
     *
     * @return \GuzzleHttp\ClientInterface
     *   The Guzzle client.
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client([
        'allow_redirects' => false,
        'cookies' => true,
      ]);
        }

        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    protected function doRequest($request)
    {
        $headers = [];
        foreach ($request->getServer() as $key => $val) {
            $key = strtolower(str_replace('_', '-', $key));
            $content_headers = [
        'content-length' => true,
        'content-md5' => true,
        'content-type' => true,
      ];
            if (strpos($key, 'http-') === 0) {
                $headers[substr($key, 5)] = $val;
            }
            // CONTENT_* are not prefixed with HTTP_
            elseif (isset($content_headers[$key])) {
                $headers[$key] = $val;
            }
        }

        $cookies = CookieJar::fromArray(
            $this->getCookieJar()->allRawValues($request->getUri()),
            parse_url($request->getUri(), PHP_URL_HOST)
        );

        $request_options = [
      'cookies' => $cookies,
      'allow_redirects' => false,
    ];

        if (!\in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
            if (null !== $content = $request->getContent()) {
                $request_options['body'] = $content;
            } else {
                if ($files = $request->getFiles()) {
                    $request_options['multipart'] = [];

                    $this->addPostFields($request->getParameters(), $request_options['multipart']);
                    $this->addPostFiles($files, $request_options['multipart']);
                } else {
                    $request_options['form_params'] = $request->getParameters();
                }
            }
        }

        if (!empty($headers)) {
            $request_options['headers'] = $headers;
        }

        $method = $request->getMethod();
        $uri = $request->getUri();

        // Let BrowserKit handle redirects
        try {
            $response = $this->getClient()->request($method, $uri, $request_options);
        }
        // Catch RequestException rather than TransferException because we want
        // to re-throw the exception whenever the response is NULL, and
        // ConnectException always has a NULL response.
        catch (RequestException $e) {
            if (!$e->hasResponse()) {
                throw $e;
            }
            $response = $e->getResponse();
        }

        return $this->createResponse($response);
    }

    /**
     * Adds files to the $multipart array.
     *
     * @param array $files
     *   The files.
     * @param array $multipart
     *   A reference to the multipart array to add the files to.
     * @param string $array_name
     *   Internal parameter used by recursive calls.
     */
    protected function addPostFiles(array $files, array &$multipart, $array_name = '')
    {
        if (empty($files)) {
            return;
        }

        foreach ($files as $name => $info) {
            if (!empty($array_name)) {
                $name = $array_name . '[' . $name . ']';
            }

            $file = [
        'name' => $name,
      ];

            if (\is_array($info)) {
                if (isset($info['tmp_name'])) {
                    if ($info['tmp_name'] !== '') {
                        $file['contents'] = fopen($info['tmp_name'], 'r');
                        if (isset($info['name'])) {
                            $file['filename'] = $info['name'];
                        }
                    } else {
                        continue;
                    }
                } else {
                    $this->addPostFiles($info, $multipart, $name);
                    continue;
                }
            } else {
                $file['contents'] = fopen($info, 'r');
            }

            $multipart[] = $file;
        }
    }

    /**
     * Adds form parameters to the $multipart array.
     *
     * @param array $formParams
     *   The form parameters.
     * @param array $multipart
     *   A reference to the multipart array to add the form parameters to.
     * @param string $array_name
     *   Internal parameter used by recursive calls.
     */
    public function addPostFields(array $formParams, array &$multipart, $array_name = '')
    {
        foreach ($formParams as $name => $value) {
            if (!empty($array_name)) {
                $name = $array_name . '[' . $name . ']';
            }

            if (\is_array($value)) {
                $this->addPostFields($value, $multipart, $name);
            } else {
                $multipart[] = [
          'name' => $name,
          'contents' => $value,
        ];
            }
        }
    }

    /**
     * Converts a Guzzle response to a BrowserKit response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The Guzzle response.
     *
     * @return \Symfony\Component\BrowserKit\Response
     *   A BrowserKit Response instance.
     */
    protected function createResponse(ResponseInterface $response)
    {
        return new Response((string) $response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }

    /**
     * Reads response meta tags to guess content-type charset.
     *
     * @param \Symfony\Component\BrowserKit\Response $response
     *   The origin response to filter.
     *
     * @return \Symfony\Component\BrowserKit\Response
     *   A BrowserKit Response instance.
     */
    protected function filterResponse($response)
    {
        $content_type = $response->getHeader('Content-Type');

        if (!$content_type || strpos($content_type, 'charset=') === false) {
            if (preg_match('/\<meta[^\>]+charset *= *["\']?([a-zA-Z\-0-9]+)/i', $response->getContent(), $matches)) {
                $headers = $response->getHeaders();
                $headers['Content-Type'] = $content_type . ';charset=' . $matches[1];

                $response = new Response($response->getContent(), $response->getStatus(), $headers);
            }
        }

        return parent::filterResponse($response);
    }
}
