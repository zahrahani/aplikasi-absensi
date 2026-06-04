import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:presensi/core/providers/shared_preferences_provider.dart';

class ProfileController {

  // Logout
  static Future<Map<String, dynamic>> logout(WidgetRef ref) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];
    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      final response = await http.post(
        Uri.parse('$apiEndpoint/logout'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({'remember_token': token}),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        // ✅ Hapus shared preferences
        final prefs = ref.read(sharedPreferencesProvider);
        await prefs.remove('user');

        return {'success': true, 'messages': data['messages']};
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal logout',
      };

    } on TimeoutException {
      // Tetap hapus shared preferences meski server tidak response
      final prefs = ref.read(sharedPreferencesProvider);
      await prefs.remove('user');
      return {'success': true, 'messages': 'Logout berhasil'};
    } on SocketException {
      // Tetap hapus shared preferences meski tidak ada internet
      final prefs = ref.read(sharedPreferencesProvider);
      await prefs.remove('user');
      return {'success': true, 'messages': 'Logout berhasil'};
    } catch (e) {
      final prefs = ref.read(sharedPreferencesProvider);
      await prefs.remove('user');
      return {'success': true, 'messages': 'Logout berhasil'};
    }
  }


  static String? _getToken(WidgetRef ref) {
    final prefs      = ref.read(sharedPreferencesProvider);
    final userString = prefs.getString('user');
    if (userString == null) return null;
    return jsonDecode(userString)['remember_token'];
  }

  // ── Update shared preferences ─────────────────────────────
  static Future<void> _updateSharedPreferences(
    WidgetRef ref,
    Map<String, dynamic> updatedFields,
  ) async {
    final prefs      = ref.read(sharedPreferencesProvider);
    final userString = prefs.getString('user');
    if (userString == null) return;

    final user = jsonDecode(userString) as Map<String, dynamic>;

    // Merge field yang diupdate ke data user yang ada
    updatedFields.forEach((key, value) {
      user[key] = value;
    });

    await prefs.setString('user', jsonEncode(user));
  }

  // ── Get profile ───────────────────────────────────────────
  static Future<Map<String, dynamic>> getProfile(WidgetRef ref) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];
    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      final response = await http.post(
        Uri.parse('$apiEndpoint/profile'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({'remember_token': token}),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {'success': true, 'data': data['data']};
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal memuat profil',
      };

    } on TimeoutException {
      return {'success': false, 'messages': 'Koneksi timeout, coba lagi'};
    } on SocketException {
      return {'success': false, 'messages': 'Tidak ada koneksi internet'};
    } catch (e) {
      return {'success': false, 'messages': 'Terjadi kesalahan, coba lagi'};
    }
  }

  // ── Update profile ────────────────────────────────────────
  static Future<Map<String, dynamic>> updateProfile({
    required WidgetRef ref,
    String? namaLengkap,
    String? username,
    String? email,
    String? noHandphone,
    String? alamat,
  }) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];
    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      final response = await http.post(
        Uri.parse('$apiEndpoint/profile/update'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({
          'remember_token': token,
          if (namaLengkap != null) 'nama_lengkap' : namaLengkap,
          if (username    != null) 'username'     : username,
          if (email       != null) 'email'        : email,
          if (noHandphone != null) 'no_handphone' : noHandphone,
          if (alamat      != null) 'alamat'       : alamat,
        }),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        // ✅ Update shared preferences
        await _updateSharedPreferences(ref, {
          if (namaLengkap != null) 'nama_lengkap' : namaLengkap,
          if (username    != null) 'username'     : username,
          if (email       != null) 'email'        : email,
          if (noHandphone != null) 'no_handphone' : noHandphone,
          if (alamat      != null) 'alamat'       : alamat,
        });

        return {'success': true, 'messages': data['messages']};
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal memperbarui profil',
      };

    } on TimeoutException {
      return {'success': false, 'messages': 'Koneksi timeout, coba lagi'};
    } on SocketException {
      return {'success': false, 'messages': 'Tidak ada koneksi internet'};
    } catch (e) {
      return {'success': false, 'messages': 'Terjadi kesalahan, coba lagi'};
    }
  }

  // ── Update foto profil ────────────────────────────────────
  static Future<Map<String, dynamic>> updateFoto({
    required WidgetRef ref,
    required File foto,
  }) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];
    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      // ── Debug ─────────────────────────────────────────────
      print('==== UPLOAD FOTO DEBUG ====');
      print('File path : ${foto.path}');
      print('File exists: ${await foto.exists()}');
      print('File size : ${await foto.length()} bytes');
      print('API endpoint: $apiEndpoint/profile/foto');

      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$apiEndpoint/profile/foto'),
      );

      request.fields['remember_token'] = token;
      request.files.add(
        await http.MultipartFile.fromPath(
          'foto_profil',  // ← nama field harus sama dengan $_FILES['foto_profil'] di PHP
          foto.path,
        ),
      );

      // ── Debug headers ─────────────────────────────────────
      print('Fields: ${request.fields}');
      print('Files : ${request.files.map((f) => f.filename).toList()}');

      final streamed = await request.send().timeout(const Duration(seconds: 30));
      final response = await http.Response.fromStream(streamed);

      // ── Debug response ────────────────────────────────────
      print('Status code: ${response.statusCode}');
      print('Response   : ${response.body}');

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        await _updateSharedPreferences(ref, {
          'foto_profil': data['foto_profil'],
        });

        return {
          'success'    : true,
          'messages'   : data['messages'],
          'foto_profil': data['foto_profil'],
        };
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal memperbarui foto',
      };

    } on TimeoutException {
      return {'success': false, 'messages': 'Koneksi timeout, coba lagi'};
    } on SocketException {
      return {'success': false, 'messages': 'Tidak ada koneksi internet'};
    } catch (e) {
      print('Upload foto error: $e');
      return {'success': false, 'messages': 'Terjadi kesalahan, coba lagi'};
    }
  }

  // ── Ganti password ────────────────────────────────────────
  // Password tidak perlu disimpan di shared preferences
  // karena alasan keamanan
  static Future<Map<String, dynamic>> gantiPassword({
    required WidgetRef ref,
    required String passwordLama,
    required String passwordBaru,
    required String konfirmasiPassword,
  }) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];
    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      final response = await http.post(
        Uri.parse('$apiEndpoint/profile/ganti-password'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({
          'remember_token'     : token,
          'password_lama'      : passwordLama,
          'password_baru'      : passwordBaru,
          'konfirmasi_password': konfirmasiPassword,
        }),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {'success': true, 'messages': data['messages']};
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal mengganti password',
      };

    } on TimeoutException {
      return {'success': false, 'messages': 'Koneksi timeout, coba lagi'};
    } on SocketException {
      return {'success': false, 'messages': 'Tidak ada koneksi internet'};
    } catch (e) {
      return {'success': false, 'messages': 'Terjadi kesalahan, coba lagi'};
    }
  }
  
}