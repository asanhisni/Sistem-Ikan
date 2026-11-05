<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman detail profil user yang sedang login.
     */
    public function show()
    {
        $user = Auth::user();
        $user->load('cabangRelasi');
        return view('profile.show', compact('user'));
    }

    /**
     * Update data profil (Foto, Nama, Username, Email, No. HP, PIN Pemulihan, & Password).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'telepon'      => 'nullable|string|max:20',
            'recovery_pin' => 'required|string|max:20',
        ];

        // Jika user ingin mengganti password
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password']         = 'required|string|min:6|confirmed';
        }

        $request->validate($rules, [
            'foto.image'                => 'File yang diunggah harus berupa gambar.',
            'foto.mimes'                => 'Format foto harus JPEG, PNG, JPG, atau WEBP.',
            'foto.max'                  => 'Ukuran foto maksimal 10 MB.',
            'name.required'             => 'Nama lengkap wajib diisi.',
            'username.required'         => 'Username wajib diisi.',
            'username.unique'           => 'Username sudah digunakan oleh akun lain.',
            'email.required'            => 'Email wajib diisi.',
            'email.unique'              => 'Email sudah terdaftar pada akun lain.',
            'recovery_pin.required'     => 'PIN Pemulihan Akun wajib diisi.',
            'current_password.required' => 'Password saat ini wajib diisi untuk verifikasi perubahan password.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        // ─── PROSES UPLOAD, CROP RASIO 1:1, & KOMPRESI GAMBAR ───
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $fotoPath = $this->cropAndCompressImage($file);

            if ($fotoPath) {
                // Hapus foto lama jika ada (dari public/avatars/)
                if ($user->foto) {
                    $oldFilePath = public_path($user->foto);
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                $user->foto = $fotoPath;
            }
        }

        // Validasi password saat ini jika ganti password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withInput()
                    ->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name         = $request->name;
        $user->username     = $request->username;
        $user->email        = $request->email;
        $user->telepon      = $request->telepon;
        $user->recovery_pin = $request->recovery_pin;
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profil dan foto akun Anda berhasil diperbarui!');
    }

    /**
     * Hapus foto profil user dan kembalikan ke avatar inisial.
     */
    public function destroyPhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->foto) {
            // Hapus file dari public/avatars/
            $filePath = public_path($user->foto);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $user->foto = null;
            $user->save();
        }

        return redirect()->route('profile.show')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Memotong gambar menjadi rasio persegi 1:1 (Center Crop) dan mengompres ukuran file.
     */
    private function cropAndCompressImage($uploadedFile): ?string
    {
        $realPath = $uploadedFile->getRealPath();
        $mime = $uploadedFile->getMimeType();

        // 1. Buat source resource GD sesuai tipe mime
        $sourceImage = null;
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = @imagecreatefromjpeg($realPath);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($realPath);
                break;
            case 'image/webp':
                $sourceImage = @imagecreatefromwebp($realPath);
                break;
            default:
                if (function_exists('imagecreatefromstring')) {
                    $sourceImage = @imagecreatefromstring(file_get_contents($realPath));
                }
                break;
        }

        if (!$sourceImage) {
            // Fallback: simpan langsung file aslinya ke public/avatars/ tanpa GD
            $extension = $uploadedFile->getClientOriginalExtension() ?: 'jpg';
            $filename  = 'avatars/' . uniqid('avatar_') . '_' . time() . '.' . $extension;
            $destination = public_path('avatars');
            if (!file_exists($destination)) {
                @mkdir($destination, 0777, true);
            }
            $uploadedFile->move($destination, basename($filename));
            return $filename;
        }

        // Koreksi orientasi EXIF (untuk foto kamera HP portrait/landscape)
        if (function_exists('exif_read_data') && in_array($mime, ['image/jpeg', 'image/jpg'])) {
            try {
                $exif = @exif_read_data($realPath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $sourceImage = imagerotate($sourceImage, 180, 0);
                            break;
                        case 6:
                            $sourceImage = imagerotate($sourceImage, -90, 0);
                            break;
                        case 8:
                            $sourceImage = imagerotate($sourceImage, 90, 0);
                            break;
                    }
                }
            } catch (\Exception $e) {
                // Abaikan jika tidak ada data EXIF
            }
        }

        $origWidth  = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // 2. Hitung Center Crop 1:1 (Square)
        // Ambil sisi terpendek agar gambar portrait / landscape terpotong simetris di tengah
        $minSide = min($origWidth, $origHeight);
        $cropX   = (int) floor(($origWidth - $minSide) / 2);
        $cropY   = (int) floor(($origHeight - $minSide) / 2);

        // Tentukan resolusi target (maksimal 500x500px, sangat tajam untuk avatar dan hemat storage)
        $targetSize = min(500, $minSide);

        $croppedCanvas = imagecreatetruecolor($targetSize, $targetSize);

        // Pertahankan kualitas rendering warna
        imagealphablending($croppedCanvas, false);
        imagesavealpha($croppedCanvas, true);

        // Lakukan cropping dan resize sekaligus
        imagecopyresampled(
            $croppedCanvas,
            $sourceImage,
            0, 0,
            $cropX, $cropY,
            $targetSize, $targetSize,
            $minSide, $minSide
        );

        // 3. Simpan dan kompres langsung ke public/avatars/
        $filename   = 'avatars/' . uniqid('avatar_') . '_' . time() . '.jpg';
        $targetDir  = public_path('avatars');
        $targetPath = public_path($filename);

        // Pastikan folder public/avatars ada dan writable
        if (!file_exists($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        // Kompresi kualitas JPEG 85%
        $saved = @imagejpeg($croppedCanvas, $targetPath, 85);

        // Bersihkan memori GD
        imagedestroy($sourceImage);
        imagedestroy($croppedCanvas);

        if (!$saved || !file_exists($targetPath)) {
            \Log::warning("imagejpeg gagal menulis ke {$targetPath}, mencoba copy fallback");
            try {
                $extension = $uploadedFile->getClientOriginalExtension() ?: 'jpg';
                $fallbackFilename = 'avatars/' . uniqid('avatar_') . '_' . time() . '.' . $extension;
                $uploadedFile->move($targetDir, basename($fallbackFilename));
                return $fallbackFilename;
            } catch (\Throwable $e) {
                \Log::error("Fallback upload juga gagal: " . $e->getMessage());
                return null;
            }
        }

        return $filename;
    }
}
