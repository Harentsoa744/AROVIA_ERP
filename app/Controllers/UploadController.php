<?php

namespace App\Controllers;

class UploadController extends BaseController
{
    /**
     * Serve employee photos from writable directory
     */
    public function serveEmploye($filename)
    {
        $filePath = WRITEPATH . 'uploads/employes/' . $filename;
        
        if (!file_exists($filePath)) {
            // Return 404 or serve default avatar
            return $this->response->setStatusCode(404);
        }
        
        $mimeType = mime_content_type($filePath);
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }

    /**
     * Serve user photos from writable directory
     */
    public function serveUtilisateur($filename)
    {
        $filePath = WRITEPATH . 'uploads/utilisateurs/' . $filename;
        
        if (!file_exists($filePath)) {
            // Return 404 or serve default avatar
            return $this->response->setStatusCode(404);
        }
        
        $mimeType = mime_content_type($filePath);
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }
}
