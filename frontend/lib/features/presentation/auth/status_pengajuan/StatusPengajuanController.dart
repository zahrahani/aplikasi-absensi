import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:presensi/core/providers/shared_preferences_provider.dart';
import 'package:presensi/features/presentation/auth/status_pengajuan/data_model.dart';

class StatusPengajuanController {

  // ── Ambil token dari shared preferences ───────────────────
  static String? _getToken(WidgetRef ref) {
    final prefs      = ref.read(sharedPreferencesProvider);
    final userString = prefs.getString('user');
    if (userString == null) return null;
    return jsonDecode(userString)['remember_token'];
  }

  // ── Get semua pengajuan ────────────────────────────────────
  static Future<Map<String, dynamic>> getPengajuan(WidgetRef ref) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];

    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      final response = await http.post(
        Uri.parse('$apiEndpoint/pengajuan'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({'remember_token': token}),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        final List<dynamic> raw = data['data'] ?? [];
        final list = raw
            .map((e) => PengajuanItem.fromJson(Map<String, dynamic>.from(e)))
            .toList();
        return {'success': true, 'data': list};
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal memuat pengajuan',
      };

    } on TimeoutException {
      return {'success': false, 'messages': 'Koneksi timeout, coba lagi'};
    } on SocketException {
      return {'success': false, 'messages': 'Tidak ada koneksi internet'};
    } catch (e) {
      return {'success': false, 'messages': 'Terjadi kesalahan, coba lagi'};
    }
  }

  // ── Buat pengajuan baru ────────────────────────────────────
  static Future<Map<String, dynamic>> buatPengajuan({
    required WidgetRef ref,
    required String jenisId,
    required String tanggalMulai,
    String? tanggalSelesai,
    String? alasan,
    bool isUrgent = false,
  }) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];

    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      final response = await http.post(
        Uri.parse('$apiEndpoint/pengajuan/buat'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({
          'remember_token' : token,
          'jenis_id'       : jenisId,
          'tanggal_mulai'  : tanggalMulai,
          'tanggal_selesai': tanggalSelesai,
          'alasan'         : alasan,
          'is_urgent'      : isUrgent ? 1 : 0,
        }),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {'success': true, 'messages': data['messages']};
      }

      
      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal membuat pengajuan',
      };

    } on TimeoutException {
      return {'success': false, 'messages': 'Koneksi timeout, coba lagi'};
    } on SocketException {
      return {'success': false, 'messages': 'Tidak ada koneksi internet'};
    } catch (e) {
      print(e);
      return {'success': false, 'messages': 'Terjadi kesalahan, coba lagi'};
    }
  }

  // ── Batalkan pengajuan ─────────────────────────────────────
  static Future<Map<String, dynamic>> batalPengajuan({
    required WidgetRef ref,
    required String pengajuanId,
  }) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];

    try {
      final token = _getToken(ref);
      if (token == null) {
        return {'success': false, 'messages': 'Sesi tidak ditemukan'};
      }

      final response = await http.post(
        Uri.parse('$apiEndpoint/pengajuan/batal'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({
          'remember_token': token,
          'pengajuan_id'  : pengajuanId,
        }),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {'success': true, 'messages': data['messages']};
      }

      return {
        'success' : false,
        'messages': data['errors_messages'] ?? 'Gagal membatalkan pengajuan',
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