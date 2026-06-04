import 'dart:convert';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:geolocator/geolocator.dart';
import 'package:http/http.dart' as http;
import 'package:presensi/core/providers/shared_preferences_provider.dart';

class ScanQRController {

  // ── Ambil token auth user dari shared preferences ──────────
  static Future<Map<String, dynamic>> ambilTokenUser(WidgetRef ref) async {
    try {
      final prefs = ref.read(sharedPreferencesProvider);
      final userString = prefs.getString('user');

      if (userString == null) {
        return {
          'success': false,
          'messages': 'Sesi tidak ditemukan, silakan login ulang',
        };
      }

      final user = jsonDecode(userString);
      final token = user['remember_token'];

      if (token == null) {
        return {
          'success': false,
          'messages': 'Token autentikasi tidak ditemukan',
        };
      }

      return {
        'success': true,
        'token': token,
      };

    } catch (e) {
      return {
        'success': false,
        'messages': 'Gagal membaca sesi: $e',
      };
    }
  }

  // ── Ambil GPS user ─────────────────────────────────────────
  static Future<Map<String, dynamic>> ambilLokasi() async {
    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        return {
          'success': false,
          'messages': 'GPS tidak aktif, nyalakan GPS terlebih dahulu',
        };
      }

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          return {
            'success': false,
            'messages': 'Izin lokasi ditolak',
          };
        }
      }

      if (permission == LocationPermission.deniedForever) {
        return {
          'success': false,
          'messages': 'Izin lokasi ditolak permanen, buka pengaturan untuk mengizinkan',
        };
      }

      final posisi = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );

      return {
        'success': true,
        'lat': posisi.latitude,
        'lng': posisi.longitude,
      };

    } catch (e) {
      return {
        'success': false,
        'messages': 'Gagal ambil lokasi: $e',
      };
    }
  }

  // ── Kirim token QR + lokasi + token auth ke server ─────────
  static Future<Map<String, dynamic>> absen({
    required String tokenQr,
    required String tokenAuth,
    required double latUser,
    required double lngUser,
  }) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];

    try {
      final Uri url = Uri.parse('$apiEndpoint/presensi');
      final response = await http.post(
        url,
        body: jsonEncode({
          'remember_token' : tokenAuth,
          'qr_code'   : tokenQr,
          'lat_user': latUser,
          'lng_user': lngUser,
        }),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {
          'success' : true,
          'messages': data['messages'] ?? 'Absensi berhasil',
        };
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Absensi gagal',
      };

    } catch (e) {
      return {
        'success' : false,
        'messages': 'Tidak dapat terhubung ke server',
      };
    }
  }

  // ── Proses lengkap: token auth → GPS → kirim server ────────
  static Future<Map<String, dynamic>> prosesAbsen(
    String tokenQr,
    WidgetRef ref,
  ) async {
    // 1. Ambil token auth user
    final authResult = await ambilTokenUser(ref);
    if (!authResult['success']) return authResult;

    // 2. Ambil lokasi GPS
    final lokasiResult = await ambilLokasi();
    if (!lokasiResult['success']) return lokasiResult;

    // 3. Kirim ke server
    return await absen(
      tokenQr  : tokenQr,
      tokenAuth: authResult['token'],
      latUser  : lokasiResult['lat'],
      lngUser  : lokasiResult['lng'],
    );
  }
}