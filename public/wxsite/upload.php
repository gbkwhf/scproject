<?php

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers:'Origin, X-Requested-With, Content-Type, Accept'");

$base64_string = $_POST['base64_string'];

$savename = uniqid().'.png';//localResizeIMG压缩后的图片都是jpeg格式

$savepath = '../upload/'.$savename;
$image = base64_to_img( $base64_string, $savepath );
//$urlpath = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//$urlpath= str_replace("wx_site/upload.php","",$urlpath);
$urlpath = $_SERVER['HTTP_HOST'];
$urlpath = $urlpath."/upload/".$savename;

if($image){
    $result = array(
            "code"=> 1,
            "msg" => "",
            "data"=> $urlpath
        );
}else{
    $result = array(
        "code"=> 3,
        "msg" => "上传失败"
    );
}
echo json_encode($result);

function base64_to_img( $base64_string, $output_file ) {
    $img = str_replace('data:image/jpeg;base64,', '', $base64_string);
    $data = base64_decode($img);
    $success = file_put_contents($output_file, $data);
    return( $success );

//    $ifp = fopen( $output_file, "wb" );
//    fwrite( $ifp, base64_decode( $base64_string) );
//    fclose( $ifp );
//    return( $output_file );
}
//if(isset($_FILES['file'])){
//    if($_FILES['file']['error']==0){
//
//        $tmp_name = $_FILES["file"]["tmp_name"];
////        echo $tmp_name;
//        $name = time().$_FILES["file"]["name"];
////        echo $name;
//        move_uploaded_file($tmp_name, "../upload/".$name);
//        $urlpath = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//        $urlpath= str_replace("upload.php","",$urlpath);
//        $urlpath = $urlpath."../upload/".$name;
//        $result = array(
//            "code"=> 1,
//            "msg" => "",
//            "data"=> $urlpath
//        );
//    }else{
//        $result = array(
//            "code"=> 2,
//            "msg"=> "图片上传错误"
//        );
//    }
//}else{
//    $result = array(
//        "code"=> 3,
//        "msg"=> "上传失败"
//    );
//}
//
//echo json_encode($result);






?>