<?php
namespace App\Controllers;

use App\Models\ItemCategoryModel;
use Config\Validation;

class ItemCategoryController extends AppController {

    public function __construct() {
        $this->catModel = new ItemCategoryModel();
    }

    public function index() {
        $categories = $this->catModel->getByUser($this->currentUserId());
        return view('items/categories', [
            'user' => $this->getUser(),
            'categories' => $categories,
        ]);
    }

    public function store() {
        if (!$this->validate(Validation::$itemCategory)) {
            return redirect()->back()->with('error', $this->validator->listErrors());
        }
        $this->catModel->insert([
            'user_id' => $this->currentUserId(),
            'name'    => trim((string) $this->request->getPost('name')),
        ]);
        return redirect()->to('/items/categories')->with('success', 'Category added!');
    }

    public function delete($id) {
        $cat = $this->getOwnedEntity($this->catModel, $id, '/items/categories');
        if (!$cat) return redirect()->to('/items/categories');
        $this->catModel->delete($id);
        return redirect()->to('/items/categories')->with('success', 'Category deleted!');
    }
}
