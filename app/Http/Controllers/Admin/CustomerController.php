<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\CustomerModel;
use App\Models\Admin\UserModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(): View
    {
        $data['title'] = 'Customer';

        /** @var UserModel|null $user */
        $user   = Session::get('user');
        $roleId = $user?->role_id;

        $data['hakTambah'] = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id')
            ->where([
                'tbl_akses.role_id'   => $roleId,
                'tbl_menu.menu_judul' => 'Customer',
                'tbl_akses.akses_type' => 'create',
            ])
            ->count();

        return view('Admin.Customer.index', $data);
    }

    public function show(Request $request): ?JsonResponse
    {
        if (! $request->ajax()) {
            return null;
        }

        $data = CustomerModel::orderBy('customer_id', 'DESC')->get();

        /** @var UserModel|null $user */
        $user   = Session::get('user');
        $roleId = $user?->role_id;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('notelp', function ($row) {
                $notelp = $row->customer_notelp == '' ? '-' : $row->customer_notelp;

                return $notelp;
            })
            ->addColumn('alamat', function ($row) {
                $alamat = $row->customer_alamat == '' ? '-' : $row->customer_alamat;

                return $alamat;
            })
            ->addColumn('action', function ($row) use ($roleId) {
                // Pastikan subject preg_replace selalu string
                $namaSource = preg_replace(
                    '/[^A-Za-z0-9-]+/',
                    '_',
                    (string) $row->customer_nama
                );
                $customerNamaSlug = trim($namaSource ?? '');

                $alamatSource = preg_replace(
                    '/[^A-Za-z0-9-]+/',
                    '_',
                    (string) $row->customer_alamat
                );
                $customerAlamatSlug = trim($alamatSource ?? '');

                $array = [
                    'customer_id'     => $row->customer_id,
                    'customer_nama'   => $customerNamaSlug,
                    'customer_alamat' => $customerAlamatSlug,
                    'customer_notelp' => $row->customer_notelp,
                ];

                $button = '';

                $hakEdit = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id')
                    ->where([
                        'tbl_akses.role_id'   => $roleId,
                        'tbl_menu.menu_judul' => 'Customer',
                        'tbl_akses.akses_type' => 'update',
                    ])
                    ->count();

                $hakDelete = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id')
                    ->where([
                        'tbl_akses.role_id'   => $roleId,
                        'tbl_menu.menu_judul' => 'Customer',
                        'tbl_akses.akses_type' => 'delete',
                    ])
                    ->count();

                if ($hakEdit > 0 && $hakDelete > 0) {
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                           data-bs-original-title="Edit"
                           onclick=update(' . json_encode($array) . ')>
                           <span class="fe fe-edit text-success fs-14"></span>
                        </a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                        </div>
                    ';
                } elseif ($hakEdit > 0 && $hakDelete == 0) {
                    $button .= '
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                               data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                               data-bs-original-title="Edit"
                               onclick=update(' . json_encode($array) . ')>
                               <span class="fe fe-edit text-success fs-14"></span>
                            </a>
                        </div>
                    ';
                } elseif ($hakEdit == 0 && $hakDelete > 0) {
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                        </div>
                    ';
                } else {
                    $button .= '-';
                }

                return $button;
            })
            ->rawColumns(['action', 'notelp', 'alamat'])
            ->make(true);
    }

    public function proses_tambah(Request $request): JsonResponse
    {
        /** @var string|null $rawCustomer */
        $rawCustomer = $request->input('customer');
        $customerForSlug = is_string($rawCustomer) ? $rawCustomer : '';

        $slugSource = preg_replace(
            '/[^A-Za-z0-9-]+/',
            '-',
            $customerForSlug
        );
        $slug = strtolower(trim($slugSource ?? ''));

        // insert data
        CustomerModel::create([
            'customer_nama'   => $request->customer,
            'customer_slug'   => $slug,
            'customer_notelp' => $request->notelp,
            'customer_alamat' => $request->alamat,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_ubah(Request $request, CustomerModel $customer): JsonResponse
    {
        /** @var string|null $rawCustomer */
        $rawCustomer = $request->input('customer');
        $customerForSlug = is_string($rawCustomer) ? $rawCustomer : '';

        $slugSource = preg_replace(
            '/[^A-Za-z0-9-]+/',
            '-',
            $customerForSlug
        );
        $slug = strtolower(trim($slugSource ?? ''));

        // update data
        $customer->update([
            'customer_nama'   => $request->customer,
            'customer_slug'   => $slug,
            'customer_notelp' => $request->notelp,
            'customer_alamat' => $request->alamat,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, CustomerModel $customer): JsonResponse
    {
        // delete
        $customer->delete();

        return response()->json(['success' => 'Berhasil']);
    }
}
