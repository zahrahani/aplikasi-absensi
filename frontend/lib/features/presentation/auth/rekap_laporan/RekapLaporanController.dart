import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:presensi/core/providers/shared_preferences_provider.dart';

class RekapLaporanController {

    static Future<Map<String, dynamic>> getRekap({
    required WidgetRef ref,
    required int month,
    required int year,
  }) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];

    try {
      final prefs      = ref.read(sharedPreferencesProvider);
      final userString = prefs.getString('user');

      if (userString == null) {
        return {
          'success' : false,
          'messages': 'Sesi tidak ditemukan, silakan login ulang',
        };
      }

      final token = jsonDecode(userString)['remember_token'];

      final response = await http.post(
        Uri.parse('$apiEndpoint/rekap-laporan'),
        headers: {
          'Content-Type': 'application/json',
          'Accept'      : 'application/json',
        },
        body: jsonEncode({
          'remember_token': token,
          'month'         : month,
          'year'          : year,
        }),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {
          'success': true,
          'data'   : data['data'],
        };
      }

      return {
        'success' : false,
        'messages': data['messages'] ?? 'Gagal memuat rekap',
      };

    } on TimeoutException {
      return {'success': false, 'messages': 'Koneksi timeout, coba lagi'};
    } on SocketException {
      return {'success': false, 'messages': 'Tidak ada koneksi internet'};
    } on FormatException {
      return {'success': false, 'messages': 'Respons server tidak valid'};
    } catch (e) {
      return {'success': false, 'messages': 'Terjadi kesalahan, coba lagi'};
    }
  }
}