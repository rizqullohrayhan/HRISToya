<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image; // Pastikan ini adalah namespace yang benar
use BaconQrCode\Writer;
use BaconQrCode\Renderer\GDLibRenderer;

class QRCodeController extends Controller
{
    public function download(Request $request)
    {
        $url = $request->url;
        if (is_null($url)) {
            return redirect()->back()->with('error', 'url tidak boleh kosong');
        }

        // Inisialisasi renderer untuk membuat gambar PNG
        $renderer = new GDLibRenderer(400);

        // Inisialisasi writer dan buat QR code
        $writer = new Writer($renderer);
        $qrPng = $writer->writeString($url); // Menggunakan writeString untuk mendapatkan data gambar

        // Menggunakan intervention/image untuk menambahkan teks di bawah QR code
        $image = Image::read($qrPng); // Menggunakan make untuk membuat objek gambar

        // Tambahkan ruang kosong untuk teks di bawah QR code
        $canvasHeight = $image->height() + 30;
        $canvas = Image::create($image->width(), $canvasHeight)->fill('ffffff');

        // Gabungkan QR code dengan canvas
        $canvas->place($image, 'top');

        // Tambahkan teks di bawah QR code
        $canvas->text($url, $canvas->width() / 2, $image->height(), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));  // Ganti dengan font yang sesuai
            $font->size(16);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $namefile = time() . '.png';
        $path = public_path(".qrcode/$namefile");
        $canvas->toPng()->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
}
