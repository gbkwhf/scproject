<?php

namespace Acme\Repository;


class FastDFSClient
{
    private $fastDFS;
    private $fastConnected = false;

    public function __construct()
    {
        $this->fastDFS = new \FastDFS();
    }

    public function initServer()
    {
        if (!$this->fastDFS->tracker_make_all_connections()) {
            \Log::error(sprintf("FastDFS server init failed. "));
            return false;
        }

        $fastConnection = $this->fastDFS->tracker_get_connection();

        if (!$fastConnection) {
            \Log::error(sprintf("FastDFS get connection fail. "));
            return false;
        }

        $this->fastConnected = true;

        return true;
    }

    public function fastDFSIsConnected()
    {
        return $this->fastConnected;
    }

    public function uploadFile($filePath)
    {
        if (!$this->fastConnected) {

            \Log::error(sprintf("uploadFile fail. no fastConnection "));
            return false;
        }

        $uploadResult = $this->fastDFS->storage_upload_by_filename($filePath);
        if (!$uploadResult) {
            \Log::error(sprintf("uploadFile fail. file path=%s", $filePath));
            return false;
        }

        \Log::info(sprintf("uploadFile info=%s ", json_encode($uploadResult)));
        return $uploadResult;

    }

    public function downloadFile($fileName, $group)
    {
        if (!$this->fastConnected) {
            \Log::error(sprintf("downloadFile fail. no fastConnection "));
            return false;
        }
        $downloadResult = $this->fastDFS->storage_download_file_to_buff($group, $fileName);
        if (!$downloadResult) {
            \Log::error(sprintf("downloadFile fail. error=%s", $this->fastDFS->get_last_error_info()));
            return false;
        } else {
            \Log::info(sprintf("downloadFile success. filename=%s, group=%s", $fileName, $group));
            return $downloadResult;
        }
    }

    public function deleteFile($group, $fileName)
    {
        if (!$this->fastConnected) {
            \Log::error(sprintf("deleteFile fail. no fastConnection "));
            return false;
        }
        $deleteResult = $this->fastDFS->storage_delete_file($group, $fileName);
        if (!$deleteResult) {
            \Log::error(sprintf("deleteFile fail. error=%s", $this->fastDFS->get_last_error_info()));
            return false;
        } else {
            \Log::info(sprintf("deleteFile success. filename=%s, group=%s", $fileName, $group));
            return $deleteResult;
        }
    }

    public function disConnect()
    {
        $this->fastDFS->tracker_close_all_connections();
    }
}
