<?php
namespace LiteWebServiceLib;


abstract class HTTPStatusCode {
    const OK = 200;
    const BAD_REQUEST = 400;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const INTERNAL_SERVER_ERROR = 500;
}
