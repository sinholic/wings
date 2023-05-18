<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TransactionHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validasi user apakah ada atau tidak
            User::where('username', $request->username)->firstOrFail();

            // Memulai transaksi database
            DB::transaction(function () use ($request) {
                // Ambil data yang dikirimkan dari permintaan AJAX
                $requestData = $request->all();

                // Simpan data ke database dengan model TransactionHeader
                $transaction = new TransactionHeader;
                $transaction->document_code = 'TRX';
                $transaction->document_number = rand(1, 99999);
                $transaction->username = $request->username;
                $transaction->total = 0;
                $transaction->date = date('Y-m-d');
                $transaction->save();
                $total = 0;
                foreach ($requestData['data'] as $item) { 
                    $price = $item['detail']['price'];
                    if ($item['detail']['discount'] > 0) {
                        $price = $price - ($price * $item['detail']['discount'] / 100); 
                    }
                    $total += $price * $item['qty']; 
                    $transaction->details()->create([
                        'transaction_id' => $transaction->id,
                        'product_code' => $item['detail']['product_code'],
                        'product_name' => $item['detail']['product_name'], 
                        'price' => $price,
                        'quantity' => $item['qty'],
                        'unit' => $item['detail']['unit'],
                        'sub_total' => $price * $item['qty'],
                        'currency' => $item['detail']['currency'],
                    ]);
                }
                $transaction->total = $total;
                $transaction->save();
            });

            // Berikan respons kembali ke permintaan AJAX
            return response()->json(['message' => 'Data telah berhasil dibuat', 'user' => ''], 201);
        } catch (\Exception $e) {
            // Tangani kesalahan
            // Jika deadlock terjadi, tangani sesuai kebutuhan
            if (strpos($e->getMessage(), 'deadlock detected')) {
                // Tangani deadlock timeout 5 detik
                sleep(5);
            }

            // Tangani kesalahan lainnya
            return response()->json(['message' => 'Terjadi kesalahan saat membuat data'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TransactionHeader  $transactionHeader
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionHeader $transactionHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransactionHeader  $transactionHeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionHeader $transactionHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransactionHeader  $transactionHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionHeader $transactionHeader)
    {
        //
    }
}
