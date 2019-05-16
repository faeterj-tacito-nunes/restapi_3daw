<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2017 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */
namespace Slim\Exception;

use Psr\Http\Message\ServerRequestInterface;

class InvalidMethodException extends \InvalidArgumentException
{
    protected $request;

    public function __construct(ServerRequestInterface $request, $method)
    {
        $this->request = $request;
        parent::__construct(sprintf('O método HTTP "%s" não é suportado nesta rota.', $method));
    }

    public function getRequest()
    {
        return $this->request;
    }
}
