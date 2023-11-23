<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Model\FtpManager;
use App\Exceptions\FtpDisconnectException;
use App\Exceptions\FileUploadException;
use App\Exceptions\FileDownloadException;
use App\Exceptions\FileEditException;
use App\Exceptions\FileDeleteException;

class FtpManagerTest extends TestCase
{
    private $ftp;

    public function setUp(): void
    {
        // Replace these values with your FTP server details
        $this->ftp = new FtpManager('ftp.example.com', 'your_username', 'your_password');
    }

    public function tearDown(): void
    {
        // Disconnect from the FTP server
        try {
            $this->ftp->disconnect();
        } catch (FtpDisconnectException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }
    }

    public function testUploadAndDownloadFile()
    {
        // Prepare a file for upload
        $localFile = __DIR__ . '/test.txt';
        file_put_contents($localFile, 'Hello, FTP!');

        // Upload the file to the FTP server
        $remoteFile = '/remote/test.txt';
        try {
            $this->ftp->uploadFile($localFile, $remoteFile);
        } catch (FileUploadException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }

        // Download the file from the FTP server
        $downloadedFile = __DIR__ . '/downloaded_test.txt';
        try {
            $this->ftp->downloadFile($remoteFile, $downloadedFile);
        } catch (FileDownloadException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }

        // Open the file and check its content
        $content = file_get_contents($downloadedFile);
        $this->assertEquals('Hello, FTP!', $content);
    }

    public function testEditFile()
    {
        // Prepare a file for edit
        $remoteFile = '/remote/edit_test.txt';
        $newContent = 'New content for editing';

        // Upload the file to the FTP server
        try {
            $this->ftp->uploadFile(__DIR__ . '/test.txt', $remoteFile);
        } catch (FileUploadException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }

        // Edit the file on the FTP server
        try {
            $this->ftp->editFile($remoteFile, $newContent);
        } catch (FileEditException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }

        // Download the edited file from the FTP server
        $downloadedFile = __DIR__ . '/downloaded_edit_test.txt';
        try {
            $this->ftp->downloadFile($remoteFile, $downloadedFile);
        } catch (FileDownloadException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }

        // Open the file and check its content
        $content = file_get_contents($downloadedFile);
        $this->assertEquals($newContent, $content);
    }

    public function testDeleteFile()
    {
        // Prepare a file for deletion
        $remoteFile = '/remote/delete_test.txt';

        // Upload the file to the FTP server
        try {
            $this->ftp->uploadFile(__DIR__ . '/test.txt', $remoteFile);
        } catch (FileUploadException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }

        // Delete the file from the FTP server
        try {
            $this->ftp->deleteFile($remoteFile);
        } catch (FileDeleteException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }

        // Attempt to download the deleted file (expecting failure)
        $downloadedFile = __DIR__ . '/downloaded_delete_test.txt';
        try {
            $this->ftp->downloadFile($remoteFile, $downloadedFile);
            $this->fail('FileDownloadException should have been thrown');
        } catch (FileDownloadException $e) {
            // FileDownloadException should be thrown since the file was deleted
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }
    }
}