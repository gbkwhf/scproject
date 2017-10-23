<?php

namespace Acme\Repository;

class Thumbnail
{
    private $image;
    private $imageType;

    /**
     *
     *加载图片
     * @param String $filename
     */
    public function load($filename) {
        $image_info = getimagesize($filename);
        /**
         *  {"0":480,"1":800,"2":2,"3":"width=\"480\" height=\"800\"","bits":8,"channels":3,"mime":"image/jpeg"}
         */
        \Log::info('Image info : ',$image_info);

        if(!$image_info)  return false;

        $this->imageType = $image_info['mime'];
        \Log::info('The picture type is: '.$this->imageType);
        switch ($this->imageType) {

            case 'image/jpeg':
                $this->image = imagecreatefromjpeg($filename);
                break;

            case 'image/gif':
                $this->image = imagecreatefromgif($filename);
                break;

            case 'image/png':
                $this->image = imagecreatefrompng($filename);
                break;

            case 'image/bmp':
                $this->image = $this->imageCreateFromBMP($filename);
                break;

            default:

        }

        return true;

    }

    /**
     *
     * 保存文件到给定的路径
     * @param String $filename
     * @param String $imageType
     * @param String $compression
     * @param String $permissions
     */
    public function save($filename, $imageType = 'image/jpeg', $compression = 75) {

        switch ($this->imageType) {

            case 'image/jpeg':
                return imagejpeg($this->image,$filename,$compression);
                break;

            case 'image/gif':
                return imagegif($this->image,$filename);
                break;

            case 'image/png':
                return imagepng($this->image,$filename);
                break;

            case 'image/bmp':
                return $this->imageBMP($this->image,$filename);
                break;

            default:
                return false;

        }

    }

    public function getWidth() {
        return imagesx($this->image);
    }

    public function getHeight() {
        return imagesy($this->image);
    }

    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }

    public function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }

    /**
     *
     * 计算实际生成的缩略图的大小
     * @param int $scale
     */
    private function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width,$height);
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $width
     * @param unknown_type $height
     */
    private function resize($width,$height) {
        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $newImage;
    }

    /**
     *
     * 返回图片文件类型的后缀名
     * @param String $filename
     */
    public function getImageTypeSuffix() {

        switch ($this->imageType) {

            case 'image/jpeg':
                return ".jpg";
                break;

            case 'image/gif':
                return ".gif";
                break;

            case 'image/png':
                return ".png";
                break;

            case 'image/bmp':
                return '.bmp';
                break;

            default:
                return '';
        }

    }

    private function imageCreateFromBMP( $filename ) {
        // Ouverture du fichier en mode binaire
        if ( ! $f1 = @fopen ($filename, "rb")) return FALSE ;
        // 1 : Chargement des ent?tes FICHIER
        $FILE = unpack ( "vfile_type/Vfile_size/Vreserved/Vbitmap_offset" , fread($f1 ,14));
        if ( $FILE ['file_type'] != 19778 ) return FALSE ;
        // 2 : Chargement des ent?tes BMP
        $BMP = unpack ( 'Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
            '/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
            '/Vvert_resolution/Vcolors_used/Vcolors_important' , fread ( $f1 , 40 ));
        $BMP [ 'colors' ] = pow ( 2 , $BMP['bits_per_pixel' ]);
        if ( $BMP ['size_bitmap'] == 0 ) $BMP ['size_bitmap']=$FILE ['file_size']-$FILE ['bitmap_offset'];
        $BMP ['bytes_per_pixel'] = $BMP ['bits_per_pixel'] / 8 ;
        $BMP ['bytes_per_pixel2'] = ceil ( $BMP ['bytes_per_pixel']);
        $BMP ['decal'] = ( $BMP ['width']*$BMP ['bytes_per_pixel'] / 4 );
        $BMP ['decal'] -= floor ( $BMP ['width'] * $BMP ['bytes_per_pixel'] / 4 );
        $BMP ['decal'] = 4 - ( 4 * $BMP ['decal']);
        if ( $BMP ['decal'] == 4 ) $BMP ['decal'] = 0 ;
        // 3 : Chargement des couleurs de la palette
        $PALETTE = array ();
        if ( $BMP ['colors'] < 16777216 ){
            $PALETTE = unpack ( 'V' . $BMP ['colors'] , fread ( $f1 , $BMP ['colors'] * 4 ));
        }
        // 4 : Cr?ation de l'image
        $IMG = fread ( $f1 , $BMP ['size_bitmap']);
        $VIDE = chr ( 0 );
        $res = imagecreatetruecolor( $BMP ['width'] , $BMP ['height']);
        $P = 0 ;
        $Y = $BMP ['height'] - 1 ;
        while ( $Y >= 0 ){
            $X = 0 ;
            while ( $X < $BMP ['width']){
                if ( $BMP ['bits_per_pixel'] == 24 )
                    $COLOR = @unpack ( "V" , substr($IMG,$P,3).$VIDE );
                elseif ( $BMP['bits_per_pixel']== 16 ){
                    $COLOR = unpack ( "n" , substr ( $IMG , $P , 2 ));
                    $COLOR [1] = $PALETTE [ $COLOR [ 1 ] + 1 ];
                }elseif ( $BMP['bits_per_pixel']== 8 ){
                    $COLOR = unpack ( "n" , $VIDE . substr ( $IMG , $P , 1 ));
                    $COLOR [1] = $PALETTE [ $COLOR [ 1 ] + 1 ];
                }elseif ( $BMP['bits_per_pixel']== 4 ){
                    $COLOR = unpack ( "n" , $VIDE . substr ( $IMG , floor ( $P ) , 1 ));
                    if (( $P * 2 ) % 2 == 0 )
                        $COLOR [1] = ( $COLOR [1] >> 4 ) ;
                    else
                        $COLOR [1] = ( $COLOR [1] & 0x0F );
                    $COLOR [1] = $PALETTE [ $COLOR [1] + 1 ];
                }elseif ( $BMP['bits_per_pixel']== 1 ){
                    $COLOR = unpack ( "n" , $VIDE . substr ( $IMG , floor ( $P ) , 1 ));
                    if (( $P * 8 ) % 8 == 0 ) $COLOR [ 1 ] = $COLOR [ 1 ] >> 7 ;
                    elseif (( $P * 8 ) % 8 == 1 ) $COLOR [1] = ( $COLOR [1] & 0x40 ) >> 6 ;
                    elseif (( $P * 8 ) % 8 == 2 ) $COLOR [1] = ( $COLOR [1] & 0x20 ) >> 5 ;
                    elseif (( $P * 8 ) % 8 == 3 ) $COLOR [1] = ( $COLOR [1] & 0x10 ) >> 4 ;
                    elseif (( $P * 8 ) % 8 == 4 ) $COLOR [1] = ( $COLOR [1] & 0x8 ) >> 3 ;
                    elseif (( $P * 8 ) % 8 == 5 ) $COLOR [1] = ( $COLOR [1] & 0x4 ) >> 2 ;
                    elseif (( $P * 8 ) % 8 == 6 ) $COLOR [1] = ( $COLOR [1] & 0x2 ) >> 1 ;
                    elseif (( $P * 8 ) % 8 == 7 ) $COLOR [1] = ( $COLOR [1] & 0x1 );
                    $COLOR [1] = $PALETTE [ $COLOR [1] + 1 ];
                }else return FALSE ;
                imagesetpixel( $res , $X , $Y , $COLOR [ 1 ]);
                $X ++ ;
                $P += $BMP['bytes_per_pixel'];
            }
            $Y -- ;
            $P += $BMP['decal'];
        }
        // Fermeture du fichier
        fclose ( $f1 );
        return $res ;
    }

    private function imageBMP(&$img, $filename=""){
        $widthOrig = imagesx($img);
        // width = 16*x
        $widthFloor = ((floor($widthOrig/16))*16);
        $widthCeil = ((ceil($widthOrig/16))*16);
        $height = imagesy($img);

        $size = ($widthCeil*$height*3)+54;

        // Bitmap File Header
        $result = 'BM';     // header (2b)
        $result .= $this->int_to_dword($size); // size of file (4b)
        $result .= $this->int_to_dword(0); // reserved (4b)
        $result .= $this->int_to_dword(54);  // byte location in the file which is first byte of IMAGE (4b)
        // Bitmap Info Header
        $result .= $this->int_to_dword(40);  // Size of BITMAPINFOHEADER (4b)
        $result .= $this->int_to_dword($widthCeil);  // width of bitmap (4b)
        $result .= $this->int_to_dword($height); // height of bitmap (4b)
        $result .= $this->int_to_word(1);  // biPlanes = 1 (2b)
        $result .= $this->int_to_word(24); // biBitCount = {1 (mono) or 4 (16 clr ) or 8 (256 clr) or 24 (16 Mil)} (2b)
        $result .= $this->int_to_dword(0); // RLE COMPRESSION (4b)
        $result .= $this->int_to_dword(0); // width x height (4b)
        $result .= $this->int_to_dword(0); // biXPelsPerMeter (4b)
        $result .= $this->int_to_dword(0); // biYPelsPerMeter (4b)
        $result .= $this->int_to_dword(0); // Number of palettes used (4b)
        $result .= $this->int_to_dword(0); // Number of important colour (4b)

        // is faster than chr()
        $arrChr = array();
        for($i=0; $i<256; $i++){
            $arrChr[$i] = chr($i);
        }

        // creates image data
        $bgfillcolor = array("red"=>0, "green"=>0, "blue"=>0);

        // bottom to top - left to right - attention blue green red !!!
        $y=$height-1;
        for ($y2=0; $y2<$height; $y2++) {
            for ($x=0; $x<$widthFloor;  ) {
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x++, $y));
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
            }
            for ($x=$widthFloor; $x<$widthCeil; $x++) {
                $rgb = ($x<$widthOrig) ? imagecolorsforindex($img, imagecolorat($img, $x, $y)) : $bgfillcolor;
                $result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
            }
            $y--;
        }
        // see imagegif
        if(!$filename) echo $result;

        $file = fopen($filename, "wb");
        fwrite($file, $result);
        fclose($file);
    }

    private function int_to_dword($n){
        return chr($n & 255).chr(($n >> 8) & 255).chr(($n >> 16) & 255).chr(($n >> 24) & 255);
    }

    private function int_to_word($n){
        return chr($n & 255).chr(($n >> 8) & 255);
    }

}