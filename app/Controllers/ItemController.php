<?php
namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ItemCategoryModel;
use Config\Validation;

class ItemController extends AppController {

    public function __construct() {
        $this->itemModel = new ItemModel();
        $this->catModel = new ItemCategoryModel();
    }

    public function index() {
        return view('items/index', ['user' => $this->getUser()]);
    }

    public function datatables() {
        $userId = $this->currentUserId();
        $pagination = $this->datatablePagination([
            0 => 'items.id',
            1 => 'items.name',
            2 => 'item_categories.name',
            3 => 'items.unit_price',
            4 => 'items.unit',
        ], 'items.name');

        $total = $this->itemModel->where('user_id', $userId)->countAllResults();
        $recordsFiltered = $this->itemModel->datatablesBuilder($userId, $pagination['search'])->countAllResults();
        $items = $this->itemModel->datatablesBuilder($userId, $pagination['search'])
            ->orderBy($pagination['orderColumn'], $pagination['orderDir'])
            ->limit($pagination['length'], $pagination['start'])
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($items as $index => $item) {
            $id = (int) $item['id'];
            $data[] = [
                $pagination['start'] + $index + 1,
                '<span class="item-name">' . esc($item['name']) . '</span>',
                '<span class="item-category" title="' . esc($item['category_name'] ?? '-') . '">' . esc($item['category_name'] ?? '-') . '</span>',
                '<span class="item-price">' . format_currency((float) $item['unit_price'], $item['currency'] ?? self::DEFAULT_CURRENCY) . '</span>',
                '<span class="item-unit">' . esc($item['unit']) . '</span>',
                '<div class="datatable-actions">'
                    . '<a href="' . site_url('items/' . $id . '/edit') . '" class="btn btn-sm btn-warning" title="Edit item" aria-label="Edit item"><i class="bi bi-pencil"></i></a>'
                    . '<form action="' . site_url('items/' . $id . '/delete') . '" method="POST" class="d-inline" data-confirm="Delete this item?">'
                    . csrf_field()
                    . '<button class="btn btn-sm btn-danger" title="Delete item" aria-label="Delete item"><i class="bi bi-trash"></i></button>'
                    . '</form>'
                    . '</div>',
            ];
        }

        return $this->response->setJSON([
            'draw' => $pagination['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function create() {
        return view('items/create', [
            'user' => $this->getUser(),
            'categories' => $this->catModel->getByUser($this->currentUserId()),
        ]);
    }

    public function store() {
        if (!$this->validate(Validation::$item)) {
            return $this->failValidation();
        }
        $this->itemModel->insert($this->itemPayload($this->currentUserId()));
        return redirect()->to('/items')->with('success', 'Item added!');
    }

    public function edit($id) {
        $item = $this->getOwnedEntity($this->itemModel, $id, '/items');
        if (!$item) return redirect()->to('/items');
        return view('items/edit', [
            'user' => $this->getUser(),
            'item' => $item,
            'categories' => $this->catModel->getByUser($this->currentUserId()),
        ]);
    }

    public function update($id) {
        $item = $this->getOwnedEntity($this->itemModel, $id, '/items');
        if (!$item) return redirect()->to('/items');

        if (!$this->validate(Validation::$item)) {
            return $this->failValidation();
        }
        $this->itemModel->update($id, $this->itemPayload());
        return redirect()->to('/items')->with('success', 'Item updated!');
    }

    public function delete($id) {
        $item = $this->getOwnedEntity($this->itemModel, $id, '/items');
        if (!$item) return redirect()->to('/items');
        $this->itemModel->delete($id);
        return redirect()->to('/items')->with('success', 'Item deleted!');
    }

    private function itemPayload(?int $userId = null): array {
        return [
            'user_id'     => $userId ?? $this->currentUserId(),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'unit_price'  => $this->request->getPost('unit_price'),
            'currency'    => $this->request->getPost('currency') ?: self::DEFAULT_CURRENCY,
            'unit'        => $this->request->getPost('unit') ?: 'pcs',
        ];
    }
}
