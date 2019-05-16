<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2017 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */
namespace Slim\Http;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Slim\Interfaces\Http\HeadersInterface;

/**
 * Response
 *
 * This class represents an HTTP response. It manages
 * the response status, headers, and body
 * according to the PSR-7 standard.
 *
 * @link https://github.com/php-fig/http-message/blob/master/src/MessageInterface.php
 * @link https://github.com/php-fig/http-message/blob/master/src/ResponseInterface.php
 */
class Response extends Message implements ResponseInterface
{
    /**
     * Status code
     *
     * @var int
     */
    protected $status = StatusCode::HTTP_OK;

    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reasonPhrase = '';

    /**
     * Status codes and reason phrases
     *
     * @var array
     */
    protected static $messages = [
        //Informational 1xx
        StatusCode::HTTP_CONTINUE => 'Continuar',
        StatusCode::HTTP_SWITCHING_PROTOCOLS => 'Mudando protocolos',
        StatusCode::HTTP_PROCESSING => 'Processando',
        //Successful 2xx
        StatusCode::HTTP_OK => 'OK',
        StatusCode::HTTP_CREATED => 'Criado',
        StatusCode::HTTP_ACCEPTED => 'Aceito',
        StatusCode::HTTP_NONAUTHORITATIVE_INFORMATION => 'Informações não autorizadas',
        StatusCode::HTTP_NO_CONTENT => 'Sem conteúdo',
        StatusCode::HTTP_RESET_CONTENT => 'Redefinir conteúdo',
        StatusCode::HTTP_PARTIAL_CONTENT => 'Conteúdo Parcial',
        StatusCode::HTTP_MULTI_STATUS => 'Multi-Status',
        StatusCode::HTTP_ALREADY_REPORTED => 'Já foi reportado',
        StatusCode::HTTP_IM_USED => 'IM Used',
        //Redirection 3xx
        StatusCode::HTTP_MULTIPLE_CHOICES => 'Escolhas Múltiplas',
        StatusCode::HTTP_MOVED_PERMANENTLY => 'Movido permanentemente',
        StatusCode::HTTP_FOUND => 'Encontrado',
        StatusCode::HTTP_SEE_OTHER => 'Ver outro',
        StatusCode::HTTP_NOT_MODIFIED => 'Não modificado',
        StatusCode::HTTP_USE_PROXY => 'Usar Proxy',
        StatusCode::HTTP_UNUSED => '(Inutizado)',
        StatusCode::HTTP_TEMPORARY_REDIRECT => 'Redirecionado Temporariamente',
        StatusCode::HTTP_PERMANENT_REDIRECT => 'Redirecionado Permanentemente',
        //Client Error 4xx
        StatusCode::HTTP_BAD_REQUEST => 'Requisição errada',
        StatusCode::HTTP_UNAUTHORIZED => 'Não autorizado',
        StatusCode::HTTP_PAYMENT_REQUIRED => 'Pagamento Necessário',
        StatusCode::HTTP_FORBIDDEN => 'Forbidden',
        StatusCode::HTTP_NOT_FOUND => 'Não econtrado',
        StatusCode::HTTP_METHOD_NOT_ALLOWED => 'Método inaceitável',
        StatusCode::HTTP_NOT_ACCEPTABLE => 'Inaceitável',
        StatusCode::HTTP_PROXY_AUTHENTICATION_REQUIRED => 'Autenticação Proxy Requerida',
        StatusCode::HTTP_REQUEST_TIMEOUT => 'Tempo limite de requisição estourado',
        StatusCode::HTTP_CONFLICT => 'Conflito',
        StatusCode::HTTP_GONE => 'Foi-se',
        StatusCode::HTTP_LENGTH_REQUIRED => 'Comprimento necessário',
        StatusCode::HTTP_PRECONDITION_FAILED => 'Falha de Pré-condição',
        StatusCode::HTTP_REQUEST_ENTITY_TOO_LARGE => 'Entidade de Requisição Muito Grande',
        StatusCode::HTTP_REQUEST_URI_TOO_LONG => 'URI de Requisição Muito Longa',
        StatusCode::HTTP_UNSUPPORTED_MEDIA_TYPE => 'Tipo de mídia não suportado',
        StatusCode::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE => 'Requested Range Not Satisfiable',
        StatusCode::HTTP_EXPECTATION_FAILED => 'Falha de Expectativa',
        StatusCode::HTTP_IM_A_TEAPOT => 'I\'m a teapot',
        StatusCode::HTTP_MISDIRECTED_REQUEST => 'Requisição mal direcionada',
        StatusCode::HTTP_UNPROCESSABLE_ENTITY => 'Entidade não processável',
        StatusCode::HTTP_LOCKED => 'Fechado',
        StatusCode::HTTP_FAILED_DEPENDENCY => 'Falha de Dependência',
        StatusCode::HTTP_UPGRADE_REQUIRED => 'Atualização Necessária',
        StatusCode::HTTP_PRECONDITION_REQUIRED => 'Requisição de Pré-condição',
        StatusCode::HTTP_TOO_MANY_REQUESTS => 'Muitas Solicitações',
        StatusCode::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE => 'Requisição com Campos de Cabeçalho Muito Grandes',
        StatusCode::HTTP_CONNECTION_CLOSED_WITHOUT_RESPONSE => 'Conexão Fechada sem Resposta',
        StatusCode::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS => 'Indisponível por Razões Legais',
        StatusCode::HTTP_CLIENT_CLOSED_REQUEST => 'Cliente Fechou a Requisição',
        //Server Error 5xx
        StatusCode::HTTP_INTERNAL_SERVER_ERROR => 'Erro Interno do Servidor',
        StatusCode::HTTP_NOT_IMPLEMENTED => 'Não implementado',
        StatusCode::HTTP_BAD_GATEWAY => 'Gateway Ruim',
        StatusCode::HTTP_SERVICE_UNAVAILABLE => 'Serviço Indisponível',
        StatusCode::HTTP_GATEWAY_TIMEOUT => 'Tempo limite do gateway',
        StatusCode::HTTP_VERSION_NOT_SUPPORTED => 'versão HTTP não suportada',
        StatusCode::HTTP_VARIANT_ALSO_NEGOTIATES => 'Variant Also Negotiates',
        StatusCode::HTTP_INSUFFICIENT_STORAGE => 'Espaço de Armazenamento Insuficiente',
        StatusCode::HTTP_LOOP_DETECTED => 'Loop Detectado',
        StatusCode::HTTP_NOT_EXTENDED => 'Não estendido',
        StatusCode::HTTP_NETWORK_AUTHENTICATION_REQUIRED => 'Autenticação de rede necessária',
        StatusCode::HTTP_NETWORK_CONNECTION_TIMEOUT_ERROR => 'Erro de tempo limite de conexão de rede',
    ];

    /**
     * EOL characters used for HTTP response.
     *
     * @var string
     */
    const EOL = "\r\n";

    /**
     * Create new HTTP response.
     *
     * @param int                   $status  The response status code.
     * @param HeadersInterface|null $headers The response headers.
     * @param StreamInterface|null  $body    The response body.
     */
    public function __construct(
        $status = StatusCode::HTTP_OK,
        HeadersInterface $headers = null,
        StreamInterface $body = null
    ) {
        $this->status = $this->filterStatus($status);
        $this->headers = $headers ? $headers : new Headers();
        $this->body = $body ? $body : new Body(fopen('php://temp', 'r+'));
    }

    /**
     * This method is applied to the cloned object
     * after PHP performs an initial shallow-copy. This
     * method completes a deep-copy by creating new objects
     * for the cloned object's internal reference pointers.
     */
    public function __clone()
    {
        $this->headers = clone $this->headers;
    }

    /*******************************************************************************
     * Status
     ******************************************************************************/

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $code = $this->filterStatus($code);

        if (!is_string($reasonPhrase) && !method_exists($reasonPhrase, '__toString')) {
            throw new InvalidArgumentException('ReasonPhrase deve ser uma string');
        }

        $clone = clone $this;
        $clone->status = $code;
        if ($reasonPhrase === '' && isset(static::$messages[$code])) {
            $reasonPhrase = static::$messages[$code];
        }

        if ($reasonPhrase === '') {
            throw new InvalidArgumentException('ReasonPhrase deve ser fornecido para este código');
        }

        $clone->reasonPhrase = $reasonPhrase;

        return $clone;
    }

    /**
     * Filter HTTP status code.
     *
     * @param  int $status HTTP status code.
     * @return int
     * @throws \InvalidArgumentException If an invalid HTTP status code is provided.
     */
    protected function filterStatus($status)
    {
        if (!is_integer($status) ||
            $status<StatusCode::HTTP_CONTINUE ||
            $status>StatusCode::HTTP_NETWORK_CONNECTION_TIMEOUT_ERROR
        ) {
            throw new InvalidArgumentException('Código de status HTTP inválido');
        }

        return $status;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase()
    {
        if ($this->reasonPhrase) {
            return $this->reasonPhrase;
        }
        if (isset(static::$messages[$this->status])) {
            return static::$messages[$this->status];
        }
        return '';
    }

    /*******************************************************************************
     * Headers
     ******************************************************************************/

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * If a Location header is set and the status code is 200, then set the status
     * code to 302 to mimic what PHP does. See https://github.com/slimphp/Slim/issues/1730
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value)
    {
        $clone = clone $this;
        $clone->headers->set($name, $value);

        if ($clone->getStatusCode() === StatusCode::HTTP_OK && strtolower($name) === 'location') {
            $clone = $clone->withStatus(StatusCode::HTTP_FOUND);
        }

        return $clone;
    }


    /*******************************************************************************
     * Body
     ******************************************************************************/

    /**
     * Write data to the response body.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * Proxies to the underlying stream and writes the provided data to it.
     *
     * @param string $data
     * @return $this
     */
    public function write($data)
    {
        $this->getBody()->write($data);

        return $this;
    }

    /*******************************************************************************
     * Response Helpers
     ******************************************************************************/

    /**
     * Redirect.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Redirect
     * response to the client.
     *
     * @param  string|UriInterface $url    The redirect destination.
     * @param  int|null            $status The redirect HTTP status code.
     * @return static
     */
    public function withRedirect($url, $status = null)
    {
        $responseWithRedirect = $this->withHeader('Location', (string)$url);

        if (is_null($status) && $this->getStatusCode() === StatusCode::HTTP_OK) {
            $status = StatusCode::HTTP_FOUND;
        }

        if (!is_null($status)) {
            return $responseWithRedirect->withStatus($status);
        }

        return $responseWithRedirect;
    }

    /**
     * Json.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Json
     * response to the client.
     *
     * @param  mixed  $data   The data
     * @param  int    $status The HTTP status code.
     * @param  int    $encodingOptions Json encoding options
     * @throws \RuntimeException
     * @return static
     */
    public function withJson($data, $status = null, $encodingOptions = 0)
    {
        $response = $this->withBody(new Body(fopen('php://temp', 'r+')));
        $response->body->write($json = json_encode($data, $encodingOptions));

        // Ensure that the json encoding passed successfully
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        $responseWithJson = $response->withHeader('Content-Type', 'application/json');
        if (isset($status)) {
            return $responseWithJson->withStatus($status);
        }
        return $responseWithJson;
    }

    /**
     * Is this response empty?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return in_array(
            $this->getStatusCode(),
            [StatusCode::HTTP_NO_CONTENT, StatusCode::HTTP_RESET_CONTENT, StatusCode::HTTP_NOT_MODIFIED]
        );
    }

    /**
     * Is this response informational?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isInformational()
    {
        return $this->getStatusCode() >= StatusCode::HTTP_CONTINUE && $this->getStatusCode() < StatusCode::HTTP_OK;
    }

    /**
     * Is this response OK?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isOk()
    {
        return $this->getStatusCode() === StatusCode::HTTP_OK;
    }

    /**
     * Is this response successful?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatusCode() >= StatusCode::HTTP_OK &&
            $this->getStatusCode() < StatusCode::HTTP_MULTIPLE_CHOICES;
    }

    /**
     * Is this response a redirect?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isRedirect()
    {
        return in_array(
            $this->getStatusCode(),
            [
                StatusCode::HTTP_MOVED_PERMANENTLY,
                StatusCode::HTTP_FOUND,
                StatusCode::HTTP_SEE_OTHER,
                StatusCode::HTTP_TEMPORARY_REDIRECT,
                StatusCode::HTTP_PERMANENT_REDIRECT
            ]
        );
    }

    /**
     * Is this response a redirection?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isRedirection()
    {
        return $this->getStatusCode() >= StatusCode::HTTP_MULTIPLE_CHOICES &&
            $this->getStatusCode() < StatusCode::HTTP_BAD_REQUEST;
    }

    /**
     * Is this response forbidden?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     * @api
     */
    public function isForbidden()
    {
        return $this->getStatusCode() === StatusCode::HTTP_FORBIDDEN;
    }

    /**
     * Is this response not Found?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isNotFound()
    {
        return $this->getStatusCode() === StatusCode::HTTP_NOT_FOUND;
    }

    /**
     * Is this response a client error?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isClientError()
    {
        return $this->getStatusCode() >= StatusCode::HTTP_BAD_REQUEST &&
            $this->getStatusCode() < StatusCode::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Is this response a server error?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isServerError()
    {
        return $this->getStatusCode() >= StatusCode::HTTP_INTERNAL_SERVER_ERROR && $this->getStatusCode() < 600;
    }

    /**
     * Convert response to string.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return string
     */
    public function __toString()
    {
        $output = sprintf(
            'HTTP/%s %s %s',
            $this->getProtocolVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        );
        $output .= Response::EOL;
        foreach ($this->getHeaders() as $name => $values) {
            $output .= sprintf('%s: %s', $name, $this->getHeaderLine($name)) . Response::EOL;
        }
        $output .= Response::EOL;
        $output .= (string)$this->getBody();

        return $output;
    }
}
