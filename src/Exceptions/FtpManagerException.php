<?php

namespace App\Exceptions;

use Exception;

class FtpManagerException extends Exception
{
}

class FtpConnectionException extends FtpManagerException
{
}

class FtpLoginException extends FtpManagerException
{
}

class FileUploadException extends FtpManagerException
{
}

class FileDownloadException extends FtpManagerException
{
}

class FtpDisconnectException extends FtpManagerException
{
}

class FileEditException extends FtpManagerException
{
}

class FileDeleteException extends FtpManagerException
{
}
