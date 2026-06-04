import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:presensi/core/providers/shared_preferences_provider.dart';

class DashboardController {

  // Ambil data home dari server
  static Future<Map<String, dynamic>> getHomeData(WidgetRef ref) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];

    try {
      // Ambil token auth dari shared preferences
      final prefs      = ref.read(sharedPreferencesProvider);
      final userString = prefs.getString('user');

      if (userString == null) {
        return {
          'success' : false,
          'messages': 'Sesi tidak ditemukan, silakan login ulang',
        };
      }

      final token = jsonDecode(userString);

      final response = await http.post(
        Uri.parse('$apiEndpoint/dashboard'),
        body: jsonEncode({
          'remember_token' : token['remember_token']
        })
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
        'messages': data['errors_messages'] ?? 'Gagal memuat data',
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