<?php

namespace Acme\Repository;

use Acme\Repository\FastDFSClient;
use Acme\Repository\Thumbnail;
use App\FastDFSIdMapping;
use Carbon\Carbon;

class MMS_FileManager
{
    private $isEnableDFS;
    private $fastDFDClient;
    private $thumbnail;

    public function __construct(FastDFSClient $fastDFSClient, Thumbnail $thumbnail){
        $this->fastDFDClient = $fastDFSClient;
        $this->thumbnail = $thumbnail;
        $this->fastDFDClient->initServer();
        $this->fastDFDClient->fastDFSIsConnected() or die('fastDFS is not connected.');
    }

    public function uploadFile($file, $fileId, $params){
        return $this->uploadFileByFastDFS($file, $fileId, $params);
    }

    public function downLoadFile($fileId){
        return $this->downloadFileByFastDFS($fileId);
    }

    public function uploadFileByFastDFS($file, $fileId, array $params){

        $uploadResult = $this->fastDFDClient->uploadFile(realpath($file));
        /* print_r($uploadResult);die();
        Array (
                [group_name] => group1
                [filename] => M00/00/07/wKgUDVZOkieAU09cAAAa7vC1f4w0925211
                )
        */
        if(!$uploadResult){
            \Log::error(sprintf("upload file err. filepath=%s",realpath($file)));
            return false;
        }

        $saveResult = $this->saveFileIdMappingToDB($uploadResult["filename"],$fileId,$uploadResult["group_name"]);
        if(!$saveResult){
            \Log::error(sprintf("save file id mapping error. dfsid=%s, fileid=%s",$uploadResult["filename"],$fileId));
            return false;
        }


        if(strpos($params["mime"],"image") !== false){
            // $startTime = time();
            $thumbWidth = getenv('THUMBNAIL_WIDTH');
            $thumbHeight = getenv('THUMBNAIL_HEIGHT');

            $this->thumbnail->load(realpath($file));
            $imgWidth = $this->thumbnail->getWidth();
            $imgHeight = $this->thumbnail->getHeight();

            if($imgWidth >= $imgHeight){
                if($imgWidth > $thumbWidth){
                    $this->thumbnail->resizeToWidth($thumbWidth);
                } else {
                    $this->thumbnail->resizeToWidth($imgWidth);
                }
            } else {
                if($imgHeight > $thumbHeight){
                    $this->thumbnail->resizeToHeight($thumbHeight);
                } else {
                    $this->thumbnail->resizeToHeight($imgHeight);
                }
            }
            // $endTime = time();
            // echo $params['id'];die();//输出结果是消息id：125040004-1447140130-29
            $thumbnailFileName = $params["msg_id"] . "-thumbnail";

            $thumbPath = storage_path('upload') .DIRECTORY_SEPARATOR. $thumbnailFileName;

            if(strpos($params["mime"],"image") !== false){
                $this->thumbnail->save($thumbPath);
            }

            $uploadResult = $this->fastDFDClient->uploadFile(realpath($thumbPath));
            if($uploadResult){
                $saveResult = $this->saveFileIdMappingToDB($uploadResult["filename"],$thumbnailFileName,$uploadResult["group_name"]);
                if(!$saveResult){
                    \Log::error(sprintf("save file id mapping error. dfsid=%s, fileid=%s",$uploadResult["filename"],$thumbnailFileName));
                }
            }
            unlink($thumbPath);
        }
        $this->fastDFDClient->disConnect();
        return true;
    }

    public function downloadFileByFastDFS($fileId){

        $fastDFSFileId = $this->getFastDFSFIleIdByFileId($fileId);

        \Log::info('this FastDFS file id is :',[$fastDFSFileId]);

        if(!$fastDFSFileId){
            \Log::error(sprintf("get FastDFS fileId  %s is failed.",$fileId));
            return false;
        }
        
        $downloadResult = $this->fastDFDClient->downloadFile($fastDFSFileId["dfsid"],$fastDFSFileId["group"]);
        if(!$downloadResult){
            \Log::error(sprintf("download file error."));
            return false;
        }

        $this->fastDFDClient->disConnect();

        return $downloadResult;

    }

    public function saveFileIdMappingToDB($dfsId, $fileId, $group){

        $dfs = new FastDFSIdMapping();

        $dfs->dfsid  = $dfsId;
        $dfs->group  = $group;
        $dfs->fileid = $fileId;
        $dfs->sendtime = Carbon::now();

        return $dfs->save();

    }

    public function getFastDFSFIleIdByFileId($fileId){

        $fastDFSId = FastDFSIdMapping::where('fileid',$fileId)->first();

        if($fastDFSId){
            return [
                'dfsid' => $fastDFSId->dfsid,
                'group' => $fastDFSId->group,
            ];
        }

    }

}