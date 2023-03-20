<?php

namespace App\Controller\Admin;


/**
 * Images Controller
 *
 * @property \App\Model\Table\ImagesTable $Images
 *
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImagesController extends AppController {
    
    
    public $thumb;
    public $fileName;
    public $fileExt;
    public $actualWidth;
    public $actualHeight;
    
    
    public function media($type = "Users", $category = "Profile", $userId = 3) {
        $this->viewBuilder()->setLayout(false);
        $this->set(compact('type', 'userId', 'category'));
    }
    
    public function upload() {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            
            $file = $this->request->getData('file');
            $this->fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $this->fileName = uniqid() . "." . $this->fileExt;
            $imagePath = WWW_ROOT . 'files/images/' . $this->fileName;
            $imageUrl = SITE_URL . 'files/images/' . $this->fileName;
            if (in_array(strtolower($this->fileExt), ['jpg', 'jpeg', 'png'])) {
                if (move_uploaded_file($file["tmp_name"], $imagePath)) {
                    $image = $this->Images->newEntity();
                    
                    $image->image = 'files/images/' . $this->fileName;
                    $image->user_id = 0;
                    $image->category = empty($this->request->getData('category')) ? 'Profile' : $this->request->getData('category');
                    
                    $this->loadComponent('Thumb');
                    $this->thumb = $this->Thumb;
                    
                    list($this->actualWidth, $this->actualHeight) = getimagesize($imageUrl);
                    
                    $image->small_thumb = $this->createThumb('small', SMALL_THUMB_WIDTH);
                    $image->medium_thumb = $this->createThumb('medium', MEDIUM_THUMB_WIDTH);
                    $image->large_thumb = $this->createThumb('large', LARGE_THUMB_WIDTH);
                    
                    if ($this->Images->save($image)) {
                        $this->responseData = $image;
                        $this->responseMessage = __('<b>' . $file['name'] . '</b> - Thank you! Uploaded successfully');
                        $this->responseCode = SUCCESS_CODE;
                    } else {
                        $this->responseMessage = $image->getErrors();
                    }
                    
                } else {
                    $this->responseMessage = __("Only JPG, JPEG, PNG & GIF files are allowed.");
                }
            }
        }
        echo $this->responseFormat();
        exit;
    }
    
    
    public function createThumb($thumbName = "small", $newWidth) {
        $imageUrl = SITE_URL . 'files/images/' . $this->fileName;
        $thumbPath = WWW_ROOT . 'files/images/thumbs/';
        
        $newHeight = $newWidth * ($this->actualHeight / $this->actualWidth);
        $options = [
            'destinationPath' => $thumbPath,
            'image' => ['type' => "image/" . ((in_array(strtolower($this->fileExt), ['jpg', 'jpeg'])) ? "jpeg" : "png")],
            'tmpname' => $imageUrl,
            'name' => $thumbName . "_" . $this->fileName,
            'width' => $newWidth,
            'argHeight' => $newHeight
        ];
        
        $this->thumb->create($options);
        
        return 'files/images/thumbs/' . $thumbName . "_" . $this->fileName;
    }
    
    
    public function getImages($page = 1) {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $offset = ($page - 1) * PAGE_LIMIT;
        
        $images = $this->Images->find('all')
            ->where(['Images.user_id' => 0])
            ->order(['Images.created' => 'DESC'])
            ->offset($offset)
            ->limit(PAGE_LIMIT)
            ->all();
        
        
        if (!empty($images)) {
            $this->responseData['images'] = $images;
            $this->responseCode = SUCCESS_CODE;
        }
        echo $this->responseFormat();
        exit;
    }

    public function getUserImages($page = 1) {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $offset = ($page - 1) * PAGE_LIMIT;

        $images = $this->Images->find('all')
            ->where(['Images.user_id !=' => 0])
            ->order(['Images.created' => 'DESC'])
            ->offset($offset)
            ->limit(PAGE_LIMIT)
            ->all();


        if (!empty($images)) {
            $this->responseData['images'] = $images;
            $this->responseCode = SUCCESS_CODE;
        }
        echo $this->responseFormat();
        exit;
    }
    
    public function crop() {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        
        $cropFromImage = $this->Images->get($this->request->getData('id'));
        if (!empty($cropFromImage)) {
            $this->fileExt = pathinfo($cropFromImage->large_thumb, PATHINFO_EXTENSION);
            
            $this->fileName = uniqid() . "." . $this->fileExt;
            $imagePath = WWW_ROOT . 'files/images/' . $this->fileName;
            $imageUrl = SITE_URL . 'files/images/' . $this->fileName;
            
            $this->loadComponent('Thumb');
            $this->thumb = $this->Thumb;
            
            
            $options = [
                'destinationPath' => WWW_ROOT . 'files/images/',
                'image' => ['type' => "image/" . ((in_array(strtolower($this->fileExt), ['jpg', 'jpeg'])) ? "jpeg" : "png")],
                'tmpname' => SITE_URL . $cropFromImage->large_thumb,
                'name' => $this->fileName,
                'width' => empty($this->request->getData('w')) ? 200 : $this->request->getData('w'),
                'argHeight' => empty($this->request->getData('h')) ? 200 : $this->request->getData('h'),
                'imageX' => empty($this->request->getData('x1')) ? 0 : $this->request->getData('x1'),
                'imageY' => empty($this->request->getData('y1')) ? 0 : $this->request->getData('y1'),
                'imageCrop' => true
            ];
            
            $this->thumb->create($options);
            
            $image = $this->Images->newEntity();
            
            $image->image = 'files/images/' . $this->fileName;
            $image->user_id = empty($this->request->getData('user')) ? 0 : $this->request->getData('user');
            $image->category = empty($this->request->getData('category')) ? 'Profile' : $this->request->getData('category');
            
            list($this->actualWidth, $this->actualHeight) = getimagesize($imageUrl);
            
            $image->small_thumb = $this->createThumb('small', SMALL_THUMB_WIDTH);
            $image->medium_thumb = $this->createThumb('medium', MEDIUM_THUMB_WIDTH);
            $image->large_thumb = $this->createThumb('large', LARGE_THUMB_WIDTH);
            
            if ($this->Images->save($image)) {
                $this->responseData = $image;
                $this->responseMessage = __('<b>' . $this->fileName . '</b> - Thank you! cropped successfully');
                $this->responseCode = SUCCESS_CODE;
            } else {
                $this->responseMessage = $image->getErrors();
            }
        }
        
        echo $this->responseFormat();
        exit;
    }
    
    /**
     * Delete method
     *
     * @param string|null $id Category Image id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $this->request->allowMethod(['post', 'delete']);
        $image = $this->Images->get($id);
        if ($this->Images->delete($image)) {
            $this->responseMessage = __('Image Deleted');
            $this->responseCode = SUCCESS_CODE;
        }
        
        echo $this->responseFormat();
        exit;
    }
    
}
