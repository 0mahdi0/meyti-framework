<?php

namespace App\Model;

use App\Exceptions\FtpConnectionException;
use App\Exceptions\FtpLoginException;
use App\Exceptions\FileUploadException;
use App\Exceptions\FileDownloadException;
use App\Exceptions\FtpDisconnectException;
use App\Exceptions\FileEditException;
use App\Exceptions\FileDeleteException;

class FtpManager
{
    private $host;
    private $username;
    private $password;
    private $port;
    private $ftp;

    public function __construct($host, $username, $password, $port = 21)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;

        try {
            $this->connect();
        } catch (FtpConnectionException | FtpLoginException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('FTP connection failed: ' . $e->getMessage());
        }
    }

    private function connect()
    {
        try {
            $this->ftp = ftp_connect($this->host, $this->port);
            if (!$this->ftp) {
                throw new FtpConnectionException('FTP connection failed');
            }

            if (!ftp_login($this->ftp, $this->username, $this->password)) {
                throw new FtpLoginException('FTP login failed');
            }
        } catch (FtpConnectionException | FtpLoginException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('Error establishing FTP connection: ' . $e->getMessage());
        }
    }

    public function uploadFile($localFile, $remoteFile)
    {
        try {
            if (!ftp_put($this->ftp, $remoteFile, $localFile, FTP_ASCII)) {
                throw new FileUploadException('File upload failed');
            }
        } catch (FileUploadException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new FileUploadException('Error uploading file: ' . $e->getMessage());
        }
    }

    public function downloadFile($remoteFile, $localFile)
    {
        try {
            if (!ftp_get($this->ftp, $localFile, $remoteFile, FTP_ASCII)) {
                throw new FileDownloadException('File download failed');
            }
        } catch (FileDownloadException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new FileDownloadException('Error downloading file: ' . $e->getMessage());
        }
    }

    public function editFile($remoteFile, $newContent)
    {
        try {
            if (!ftp_put($this->ftp, $remoteFile, $newContent, FTP_ASCII)) {
                throw new FileEditException('File edit failed');
            }
        } catch (FileEditException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new FileEditException('Error editing file: ' . $e->getMessage());
        }
    }

    public function deleteFile($remoteFile)
    {
        try {
            if (!ftp_delete($this->ftp, $remoteFile)) {
                throw new FileDeleteException('File deletion failed');
            }
        } catch (FileDeleteException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new FileDeleteException('Error deleting file: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        try {
            ftp_close($this->ftp);
        } catch (\Exception $e) {
            throw new FtpDisconnectException('Error disconnecting from FTP: ' . $e->getMessage());
        }
    }
}