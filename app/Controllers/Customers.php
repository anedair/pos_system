<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    protected CustomerModel $customers;

    public function __construct()
    {
        $this->customers = new CustomerModel();
    }

    public function index()
    {
        return view('customers/index', [
            'customers' => $this->customers
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    public function create()
    {
        return view('customers/create');
    }

    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[150]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'phone' => 'permit_empty|max_length[30]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = trim($this->request->getPost('email') ?? '');
        $phone = trim($this->request->getPost('phone') ?? '');

        $this->customers->insert([
            'full_name' => trim($this->request->getPost('full_name')),
            'email' => $email === '' ? null : $email,
            'phone' => $phone === '' ? null : $phone,
        ]);

        return redirect()->to('/customers')
            ->with('success', 'Customer added successfully.');
    }

    public function edit($id)
    {
        $customer = $this->customers->find($id);

        if (! $customer) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('customers/edit', [
            'customer' => $customer,
        ]);
    }

    public function update($id)
    {
        $customer = $this->customers->find($id);

        if (! $customer) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'full_name' => 'required|min_length[2]|max_length[150]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'phone' => 'permit_empty|max_length[30]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = trim($this->request->getPost('email') ?? '');
        $phone = trim($this->request->getPost('phone') ?? '');

        $this->customers->update($id, [
            'full_name' => trim($this->request->getPost('full_name')),
            'email' => $email === '' ? null : $email,
            'phone' => $phone === '' ? null : $phone,
        ]);

        return redirect()->to('/customers')
            ->with('success', 'Customer updated successfully.');
    }

    public function delete($id)
    {
        $customer = $this->customers->find($id);

        if (! $customer) {
            throw PageNotFoundException::forPageNotFound();
        }

        $salesCount = db_connect()
            ->table('sales')
            ->where('customer_id', $id)
            ->countAllResults();

        if ($salesCount > 0) {
            return redirect()->to('/customers')
                ->with('error', 'This customer has existing sales records and cannot be deleted.');
        }

        $this->customers->delete($id);

        return redirect()->to('/customers')
            ->with('success', 'Customer deleted successfully.');
    }
}
