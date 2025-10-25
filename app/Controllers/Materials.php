<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Materials extends Controller
{
    // ==============================
    // UPLOAD MATERIAL
    // ==============================
    public function upload($course_id)
    {
        helper(['form', 'url']);
        $session = session();
        $materialModel = new MaterialModel();

        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('material');

            // Check if file exists
            if (!$file || !$file->isValid()) {
                $error = $file ? $file->getErrorString() : 'No file selected';
                $session->setFlashdata('error', "Upload failed: $error");
                return redirect()->back()->withInput();
            }

            // Allowed file types
            $allowedTypes = ['pdf','doc','docx','ppt','pptx','xls','xlsx','zip','rar','png','jpg','jpeg','gif','txt'];
            $ext = strtolower($file->getClientExtension());
            if (!in_array($ext, $allowedTypes)) {
                $session->setFlashdata('error', 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes));
                return redirect()->back()->withInput();
            }

            // Ensure upload folder exists
            $uploadPath = FCPATH . 'uploads/materials';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0777, true)) {
                    $session->setFlashdata('error', 'Cannot create upload folder. Check folder permissions.');
                    return redirect()->back()->withInput();
                }
            }

            // Sanitize filename
            $originalName = $file->getClientName();
            $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
            $newName = time() . '_' . $safeName; // unique filename

            // Move file
            if ($file->move($uploadPath, $newName)) {
                // Save to DB
                $data = [
                    'course_id' => $course_id,
                    'file_name' => $originalName, // show original name
                    'file_path' => 'uploads/materials/' . $newName,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $materialModel->insert($data);

                $session->setFlashdata('success', '🎉 Material uploaded successfully!');
                return redirect()->to(base_url('dashboard'));

            } else {
                $errorCode = $file->getError();
                $errorMsg  = $file->getErrorString();
                $session->setFlashdata('error', "❌ Upload failed! Error code: $errorCode — $errorMsg");
                return redirect()->back()->withInput();
            }
        }

        // GET request: show form
        return view('materials/upload', ['course_id' => $course_id]);
    }

    // ==============================
    // DOWNLOAD MATERIAL
    // ==============================
    public function download($id)
    {
        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if ($material && file_exists(FCPATH . $material['file_path'])) {
            return $this->response->download(FCPATH . $material['file_path'], null);
        }

        session()->setFlashdata('error', 'File not found or already removed.');
        return redirect()->back();
    }

    // ==============================
    // DELETE MATERIAL
    // ==============================
    public function delete($id)
    {
        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if ($material) {
            $filePath = FCPATH . $material['file_path'];
            if (file_exists($filePath)) unlink($filePath);

            $materialModel->delete($id);
            session()->setFlashdata('success', '🗑️ Material deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Material not found.');
        }

        return redirect()->back();
    }

    // ==============================
    // GET MATERIALS (AJAX)
    // ==============================
    public function getMaterials($course_id)
    {
        $materialModel = new MaterialModel();
        $materials = $materialModel->where('course_id', $course_id)->findAll();

        $data = [];
        foreach ($materials as $mat) {
            $data[] = [
                'id' => $mat['id'],
                'file_name' => $mat['file_name'],
                'download_url' => base_url('materials/download/'.$mat['id'])
            ];
        }

        return $this->response->setJSON($data);
    }
}
