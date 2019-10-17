<?php namespace App\Services;

use Storage;
use Image;
use Illuminate\Http\File;

class FilesStorage {

    /**
     * Create a new settings service instance.
     */
    public function __construct()
    {
    
    }

    public function getImages($type, $subFolder = null)
    {                
        $params = $this->getPathByType($type);
                
        $images = Storage::disk($params->disk)->files($params->path.($subFolder ? '/'.$subFolder : ''));
        $pathes = [];
        
        foreach($images as $key=>$image){                
            $images[$key] = Storage::disk($params->disk)->url($image);
            $pathes[$key] = $image;
        }
        
        $response = ['url'=>url(''), 'images'=>$images];
        
        if($params->disk == 'public'){
            $response['pathes'] = $pathes;
        }
        
        return $response;
    }
    
    public function getImageUrl($type, $path)
    {                
        $params = $this->getPathByType($type);   
        if(!Storage::disk($params->disk)->exists($path))
            return null;
                
        return Storage::disk($params->disk)->url($path);
    }
    
    public function getImageSize($type, $path)
    {                
        $params = $this->getPathByType($type);   
        if(!Storage::disk($params->disk)->exists($path))
            return null;
        
        $img = Image::make(Storage::disk($params->disk)->path($path));
        
        return ['width' => $img->width(), 'height' => $img->height()];
    }
        
    /**
     * Upload resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function saveImage($IMG, $type, $visibility = 'public', $subFolder = null)
    {                
        $params = $this->getPathByType($type);
                        
        $imageName = Storage::disk($params->disk)->putFile($params->path.($subFolder ? '/'.$subFolder : ''), $IMG, $visibility);
        
        $response = ['url'=>Storage::disk($params->disk)->url($imageName)];
        
        if($params->disk == 'public'){
            $response['pathes'] = $imageName;
        }
        
        return $response;        
    }
        
    /**
     * Move resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function moveImage($params, $from, $to)
    {                                           
        
        return Storage::disk($params->disk)->move($from, $to);
    }
    
    /**
     * Remove resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function deleteImage($IMG, $type, $subFolder = null)
    {                
        $params = $this->getPathByType($type);
                                        
        return Storage::disk($params->disk)->delete($IMG);

    }
    
    public function createDirectory($type, $subFolder = null)
    {
        $params = $this->getPathByType($type);
        
        Storage::disk($params->disk)->makeDirectory($params->path.($subFolder ? '/'.$subFolder : ''));
        
        return $params->path.($subFolder ? '/'.$subFolder : '');
    }
    
    public function deleteDirectory($type, $subFolder = null)
    {
        $params = $this->getPathByType($type);
        
        Storage::disk($params->disk)->deleteDirectory($params->path.($subFolder ? '/'.$subFolder : ''));
        
        return $params->path.($subFolder ? '/'.$subFolder : '');
    }    
    
    /**
     * Upload path.
     *
     * @param string $type
     * @return obj $params
     */
    private function getPathByType($type)
    {        
        $params = new \stdClass();        
        switch ($type){
            case 'rooms':
                $params->path = '/uploads/rooms';
                $params->disk = 'public';
                break;                        
            case 'avatars':
                $params->path = '/uploads/avatars';
                $params->disk = 'public';
                break;            
            default :
                $params->path = '/uploads';
                $params->disk = 'public';
        }        
        return $params;
    }
    
    /**
     * Convert Img from base64 string.
     *
     * @param string $imgStr
     * @return obj $img
     */
    private function decodeBase64Image($imgStr)
    {        
        $img = new \stdClass();                
        
        $splited = explode(',', substr( $imgStr , 5 ) , 2);
        
        $mime_split = explode('/', explode(';', $splited[0], 2)[0], 2);
        
        $img->extension = $mime_split[1] == 'jpeg' ? 'jpg' : $mime_split[1];
        $img->content = base64_decode($splited[1]);

        return $img;
    }
    
}