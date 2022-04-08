<?php

abstract class MyException extends Exception
{

}

class UserException extends MyException
{

}

class SystemException extends MyException
{

}

class InvalidConfigException extends MyException
{
    
}

class InvalidActionException extends MyException
{

}