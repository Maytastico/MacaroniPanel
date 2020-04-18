<?php


abstract class ApiException extends Exception
{
    const AUTHENTICATION_FAILED = 1;
    const MALFORMED_INPUT = 2;
}